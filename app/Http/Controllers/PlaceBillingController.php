<?php

namespace App\Http\Controllers;

use App\Models\KosherPlace;
use App\Models\PlaceTierPayment;
use App\Services\MercadoPago\MercadoPagoClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PlaceBillingController extends Controller
{
    private function ownedPlace(Request $request, KosherPlace $place): KosherPlace
    {
        abort_unless($place->owner_id === $request->user()->id && $place->status === KosherPlace::STATUS_APPROVED, 403);
        return $place;
    }

    public function plans(Request $request, KosherPlace $place)
    {
        $place = $this->ownedPlace($request, $place);
        $plans = config('place_plans');

        return view('account.place_plans', compact('place', 'plans'));
    }

    public function checkout(Request $request, KosherPlace $place)
    {
        $place = $this->ownedPlace($request, $place);

        $validated = $request->validate([
            'tier'           => 'required|in:destacada_rubro,premium',
            'payment_method' => 'required|in:mercadopago,transfer',
        ]);

        $plan = config("place_plans.{$validated['tier']}");
        abort_if(! $plan, 404);

        if ($validated['payment_method'] === 'transfer') {
            return redirect()->route('account.places.plan.transfer', ['place' => $place->id, 'tier' => $validated['tier']]);
        }

        $payment = PlaceTierPayment::create([
            'place_id'       => $place->id,
            'tier'           => $validated['tier'],
            'amount'         => $plan['price'],
            'currency'       => 'ARS',
            'payment_method' => PlaceTierPayment::METHOD_MERCADOPAGO,
            'status'         => PlaceTierPayment::STATUS_PENDING,
        ]);

        $accessToken = config('services.mercadopago.access_token');
        if (! $accessToken) {
            Log::error('MERCADOPAGO_ACCESS_TOKEN no configurado: no se puede crear la preferencia de pago.');
            return back()->withErrors('El pago con Mercado Pago no está disponible en este momento. Probá con transferencia o escribinos a info@koshermap.org.');
        }

        try {
            $preference = (new MercadoPagoClient($accessToken))->createPreference(
                items: [[
                    'title'      => "Plan {$plan['label']} - {$place->name} - KosherMap",
                    'quantity'   => 1,
                    'unit_price' => $plan['price'],
                    'currency_id' => 'ARS',
                ]],
                externalReference: "place_tier_payment:{$payment->id}",
                backUrls: [
                    'success' => route('account.places'),
                    'failure' => route('account.places.plan', $place),
                    'pending' => route('account.places'),
                ],
                notificationUrl: route('webhooks.mercadopago'),
            );
        } catch (\Throwable $e) {
            Log::error('Error al crear preferencia de MP: ' . $e->getMessage());
            return back()->withErrors('No pudimos iniciar el pago. Probá de nuevo en unos minutos.');
        }

        return redirect()->away($preference['init_point']);
    }

    public function transferForm(Request $request, KosherPlace $place)
    {
        $place = $this->ownedPlace($request, $place);
        $tier = $request->query('tier');
        $plan = config("place_plans.{$tier}");
        abort_if(! $plan, 404);

        return view('account.place_transfer', compact('place', 'tier', 'plan'));
    }

    public function transferStore(Request $request, KosherPlace $place)
    {
        $place = $this->ownedPlace($request, $place);

        $validated = $request->validate([
            'tier'  => 'required|in:destacada_rubro,premium',
            'proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:8192',
        ]);

        $plan = config("place_plans.{$validated['tier']}");
        $path = $request->file('proof')->store('tier_payment_proofs', 'public');

        PlaceTierPayment::create([
            'place_id'            => $place->id,
            'tier'                => $validated['tier'],
            'amount'              => $plan['price'],
            'currency'            => 'ARS',
            'payment_method'      => PlaceTierPayment::METHOD_TRANSFER,
            'status'              => PlaceTierPayment::STATUS_PENDING,
            'transfer_proof_path' => $path,
        ]);

        try {
            Mail::raw(
                "Nuevo comprobante de transferencia para revisar.\n\n"
                . "Local: {$place->name}\n"
                . "Plan: {$plan['label']} ({$plan['price']} ARS)\n\n"
                . "Revisar en: " . route('admin.places.index'),
                fn ($m) => $m->to('info@koshermap.org')->subject('Comprobante de transferencia - ' . $place->name)
            );
        } catch (\Throwable $e) {
            Log::error('Error al notificar comprobante de transferencia: ' . $e->getMessage());
        }

        return redirect()->route('account.places')
            ->with('success', 'Recibimos tu comprobante. Lo vamos a revisar y activamos tu plan en breve.');
    }
}
