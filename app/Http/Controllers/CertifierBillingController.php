<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use App\Models\CertifierTierPayment;
use App\Services\Billing\TierPricingService;
use App\Services\MercadoPago\MercadoPagoClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CertifierBillingController extends Controller
{
    private function ownedCertifier(Request $request): Certifier
    {
        $certifier = Certifier::where('owner_id', $request->user()->id)->first();
        abort_unless($certifier && $certifier->isApproved(), 403);
        return $certifier;
    }

    public function plans(Request $request)
    {
        $certifier = $this->ownedCertifier($request);
        $plans = config('certifier_plans');
        $periods = TierPricingService::PERIOD_LABELS;

        return view('account.certifier_plans', compact('certifier', 'plans', 'periods'));
    }

    public function checkout(Request $request)
    {
        $certifier = $this->ownedCertifier($request);

        $validated = $request->validate([
            'tier'           => 'required|in:destacada,pro',
            'period'         => 'required|in:1,6,12',
            'payment_method' => 'required|in:mercadopago,transfer',
        ]);

        $plan = config("certifier_plans.{$validated['tier']}");
        abort_if(! $plan, 404);

        $months = TierPricingService::monthsFor($validated['period']);
        $amount = TierPricingService::priceFor($plan['price'], $validated['period']);
        $periodLabel = TierPricingService::PERIOD_LABELS[$validated['period']];

        if ($validated['payment_method'] === 'transfer') {
            return redirect()->route('account.certifiers.plan.transfer', ['tier' => $validated['tier'], 'period' => $validated['period']]);
        }

        $payment = CertifierTierPayment::create([
            'certifier_id'   => $certifier->id,
            'tier'           => $validated['tier'],
            'months'         => $months,
            'amount'         => $amount,
            'currency'       => 'ARS',
            'payment_method' => CertifierTierPayment::METHOD_MERCADOPAGO,
            'status'         => CertifierTierPayment::STATUS_PENDING,
        ]);

        $accessToken = config('services.mercadopago.access_token');
        if (! $accessToken) {
            Log::error('MERCADOPAGO_ACCESS_TOKEN no configurado: no se puede crear la preferencia de pago.');
            return back()->withErrors('El pago con Mercado Pago no está disponible en este momento. Probá con transferencia o escribinos a info@koshermap.org.');
        }

        try {
            $preference = (new MercadoPagoClient($accessToken))->createPreference(
                items: [[
                    'title'      => "Plan {$plan['label']} ({$periodLabel}) - {$certifier->name} - KosherMap",
                    'quantity'   => 1,
                    'unit_price' => $amount,
                    'currency_id' => 'ARS',
                ]],
                externalReference: "certifier_tier_payment:{$payment->id}",
                backUrls: [
                    'success' => route('account.certifiers.my'),
                    'failure' => route('account.certifiers.plan'),
                    'pending' => route('account.certifiers.my'),
                ],
                notificationUrl: route('webhooks.mercadopago'),
            );
        } catch (\Throwable $e) {
            Log::error('Error al crear preferencia de MP: ' . $e->getMessage());
            return back()->withErrors('No pudimos iniciar el pago. Probá de nuevo en unos minutos.');
        }

        return redirect()->away($preference['init_point']);
    }

    public function transferForm(Request $request)
    {
        $certifier = $this->ownedCertifier($request);
        $tier = $request->query('tier');
        $period = $request->query('period', '1');
        $plan = config("certifier_plans.{$tier}");
        abort_if(! $plan || ! isset(TierPricingService::PERIOD_MONTHS[$period]), 404);

        $amount = TierPricingService::priceFor($plan['price'], $period);
        $periodLabel = TierPricingService::PERIOD_LABELS[$period];

        return view('account.certifier_transfer', compact('certifier', 'tier', 'plan', 'period', 'amount', 'periodLabel'));
    }

    public function transferStore(Request $request)
    {
        $certifier = $this->ownedCertifier($request);

        $validated = $request->validate([
            'tier'   => 'required|in:destacada,pro',
            'period' => 'required|in:1,6,12',
            'proof'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:8192',
        ]);

        $plan = config("certifier_plans.{$validated['tier']}");
        $months = TierPricingService::monthsFor($validated['period']);
        $amount = TierPricingService::priceFor($plan['price'], $validated['period']);
        $periodLabel = TierPricingService::PERIOD_LABELS[$validated['period']];
        $path = $request->file('proof')->store('tier_payment_proofs', 'public');

        CertifierTierPayment::create([
            'certifier_id'         => $certifier->id,
            'tier'                 => $validated['tier'],
            'months'               => $months,
            'amount'               => $amount,
            'currency'             => 'ARS',
            'payment_method'       => CertifierTierPayment::METHOD_TRANSFER,
            'status'               => CertifierTierPayment::STATUS_PENDING,
            'transfer_proof_path'  => $path,
        ]);

        try {
            Mail::raw(
                "Nuevo comprobante de transferencia para revisar.\n\n"
                . "Certificadora: {$certifier->name}\n"
                . "Plan: {$plan['label']} ({$periodLabel}) - {$amount} ARS\n\n"
                . "Revisar en: " . route('admin.certifiers.index'),
                fn ($m) => $m->to('info@koshermap.org')->subject('Comprobante de transferencia - ' . $certifier->name)
            );
        } catch (\Throwable $e) {
            Log::error('Error al notificar comprobante de transferencia: ' . $e->getMessage());
        }

        return redirect()->route('account.certifiers.my')
            ->with('success', 'Recibimos tu comprobante. Lo vamos a revisar y activamos tu plan en breve.');
    }
}
