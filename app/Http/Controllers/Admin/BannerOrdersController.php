<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerOrder;
use App\Services\BannerOrderService;
use Illuminate\Http\Request;

/**
 * Pedidos de banners por autoservicio: aprobar el pago de una transferencia y
 * revisar la imagen antes de que salga al aire.
 */
class BannerOrdersController extends Controller
{
    public function index()
    {
        $orders = BannerOrder::with(['banner', 'user'])->latest()->get();

        return view('admin.banner_orders.index', compact('orders'));
    }

    public function approvePayment(BannerOrder $order, BannerOrderService $service)
    {
        abort_unless($order->payment_method === BannerOrder::PAY_TRANSFER, 404);

        $order->update(['payment_status' => BannerOrder::APPROVED]);
        $service->activateIfReady($order);

        return back()->with('success', 'Pago aprobado.');
    }

    public function rejectPayment(Request $request, BannerOrder $order, BannerOrderService $service)
    {
        $reason = (string) $request->input('reason');
        $order->update(['payment_status' => BannerOrder::REJECTED, 'rejection_reason' => $reason ?: null]);

        $service->mail(
            $order->user->email,
            'No pudimos verificar tu pago - KosherMap',
            'No pudimos verificar tu pago',
            array_values(array_filter([
                'No pudimos verificar el pago de tu banner "' . $order->banner->name . '".',
                $reason ? 'Motivo: ' . $reason : null,
                'Escribinos a info@koshermap.org y lo resolvemos.',
            ]))
        );

        return back()->with('success', 'Pago rechazado.');
    }

    public function approveReview(BannerOrder $order, BannerOrderService $service)
    {
        $order->update(['review_status' => BannerOrder::APPROVED]);
        $service->activateIfReady($order);

        return back()->with('success', $order->fresh()->activated_at
            ? 'Banner aprobado y publicado.'
            : 'Imagen aprobada. Sale al aire cuando el pago esté aprobado.');
    }

    public function rejectReview(Request $request, BannerOrder $order, BannerOrderService $service)
    {
        $reason = (string) $request->input('reason');
        $order->update(['review_status' => BannerOrder::REJECTED, 'rejection_reason' => $reason ?: null]);
        $order->banner->update(['is_active' => false]);

        $service->mail(
            $order->user->email,
            'Tu banner no fue aprobado - KosherMap',
            'Tu banner no fue aprobado',
            array_values(array_filter([
                'No pudimos aprobar tu banner "' . $order->banner->name . '" porque no cumple las condiciones de publicación.',
                $reason ? 'Motivo: ' . $reason : null,
                $order->payment_status === BannerOrder::APPROVED
                    ? 'Como ya pagaste, escribinos a info@koshermap.org para devolverte el pago o cambiar la imagen.'
                    : null,
            ]))
        );

        return back()->with('success', 'Banner rechazado. Si ya pagó, hay que devolverle el pago.');
    }
}
