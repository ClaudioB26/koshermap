<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BannerOrder;
use App\Services\BannerOrderService;
use App\Services\Billing\TierPricingService;
use App\Services\MercadoPago\MercadoPagoClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Compra de banners por autoservicio (cuenta del anunciante).
 */
class BannerOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = BannerOrder::with('banner')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('account.banners.index', compact('orders'));
    }

    public function create()
    {
        return view('account.banners.create', [
            'prices'  => collect(config('banner_plans.slots'))->map(fn ($s) => $s['price'])->all(),
            'pricesUsd' => collect(config('banner_plans.slots'))->map(fn ($s) => $s['price_usd'])->all(),
            'periods' => array_keys(TierPricingService::PERIOD_MONTHS),
        ]);
    }

    public function store(Request $request, BannerOrderService $service)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:120',
            'slot'           => 'required|in:' . implode(',', array_keys(Banner::SLOTS)),
            'pages'          => 'required|in:' . implode(',', array_keys(Banner::PAGES)),
            'target_url'     => 'required|url:http,https|max:500',
            'alt'            => 'nullable|string|max:255',
            'period'         => 'required|in:' . implode(',', array_keys(TierPricingService::PERIOD_MONTHS)),
            'payment_method' => 'required|in:mercadopago,transfer,transfer_usd',
            // Sin SVG a proposito: puede llevar scripts.
            'image'          => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048|dimensions:min_width=300,min_height=50',
        ]);

        $file = $request->file('image');
        [$w, $h] = getimagesize($file->getRealPath()) ?: [null, null];
        $user = $request->user();

        // El banner queda INACTIVO hasta que haya pago aprobado y revision de la imagen.
        $banner = Banner::create([
            'name'       => $data['name'],
            'advertiser' => $user->name,
            'slot'       => $data['slot'],
            'pages'      => $data['pages'],
            'image_path' => $file->store('banners', 'public'),
            'width'      => $w,
            'height'     => $h,
            'target_url' => $data['target_url'],
            'alt'        => $data['alt'] ?? null,
            'is_active'  => false,
            'notes'      => 'Autoservicio - ' . $user->email,
        ]);

        $months = TierPricingService::monthsFor($data['period']);
        // transfer_usd = transferencia bancaria pagada en dólares; el resto se cobra en pesos.
        $currency = $data['payment_method'] === 'transfer_usd' ? 'USD' : 'ARS';
        $method = $currency === 'USD' ? BannerOrder::PAY_TRANSFER : $data['payment_method'];
        $amount = BannerOrderService::total($data['slot'], $data['period'], $currency);

        $order = BannerOrder::create([
            'user_id'        => $user->id,
            'banner_id'      => $banner->id,
            'slot'           => $data['slot'],
            'months'         => $months,
            'amount'         => $amount,
            'currency'       => $currency,
            'payment_method' => $method,
        ]);

        if ($method === BannerOrder::PAY_TRANSFER) {
            return redirect()->route('account.banners.index')->with('success', __('banners.created_transfer'));
        }

        $accessToken = config('services.mercadopago.access_token');
        if (! $accessToken) {
            Log::error('MERCADOPAGO_ACCESS_TOKEN no configurado: no se puede crear la preferencia del banner.');

            return redirect()->route('account.banners.index')->withErrors(__('plans.err_mp_unavailable'));
        }

        try {
            $preference = (new MercadoPagoClient($accessToken))->createPreference(
                items: [[
                    'title'       => 'Banner ' . $data['slot'] . ' (' . $months . ' m) - KosherMap',
                    'quantity'    => 1,
                    'unit_price'  => $amount,
                    'currency_id' => 'ARS',
                ]],
                externalReference: 'banner_order:' . $order->id,
                backUrls: [
                    'success' => route('account.banners.index'),
                    'failure' => route('account.banners.index'),
                    'pending' => route('account.banners.index'),
                ],
                notificationUrl: route('webhooks.mercadopago'),
            );
        } catch (\Throwable $e) {
            Log::error('Error al crear preferencia de MP para banner: ' . $e->getMessage());

            return redirect()->route('account.banners.index')->withErrors(__('plans.err_start_payment'));
        }

        return redirect()->away($preference['init_point']);
    }

    /** Comprobante de transferencia para un pedido propio. */
    public function proof(Request $request, BannerOrder $order, BannerOrderService $service)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->payment_method === BannerOrder::PAY_TRANSFER && $order->payment_status === BannerOrder::PENDING, 404);

        $request->validate(['proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:8192']);

        $order->update(['transfer_proof_path' => $request->file('proof')->store('tier_payment_proofs', 'public')]);
        $order->load('banner');

        $service->notifyAdmin($order, $request->user()->name . ' subió un comprobante de transferencia por un banner (' . $order->amount . ' ' . $order->currency . ').');

        return redirect()->route('account.banners.index')->with('success', __('banners.proof_received'));
    }
}
