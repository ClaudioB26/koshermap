<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use App\Models\CertifierTierPayment;
use App\Models\PlaceTierPayment;
use App\Services\Billing\TierPricingService;
use App\Services\MercadoPago\MercadoPagoClient;
use App\Services\MercadoPago\WebhookSignatureValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $paymentId = $request->input('data.id') ?? $request->query('id');
        if (! $paymentId) {
            return response()->noContent();
        }

        $accessToken = config('services.mercadopago.access_token');
        if (! $accessToken) {
            return response()->noContent();
        }

        $webhookSecret = config('services.mercadopago.webhook_secret');
        if ($webhookSecret) {
            $valid = WebhookSignatureValidator::isValid(
                $request->header('x-signature'),
                $request->header('x-request-id'),
                $request->query('data.id') ?? (string) $paymentId,
                $webhookSecret,
            );

            if (! $valid) {
                Log::warning('Webhook MP: firma invalida, se ignora.');
                return response()->noContent();
            }
        }

        try {
            $payment = (new MercadoPagoClient($accessToken))->getPayment($paymentId);
        } catch (\Throwable $e) {
            Log::warning('Webhook MP: no se pudo consultar el pago', ['payment_id' => $paymentId, 'error' => $e->getMessage()]);
            return response()->noContent();
        }

        // external_reference viene como "certifier_tier_payment:{id}" o "place_tier_payment:{id}"
        $reference = $payment['external_reference'] ?? '';

        if (str_starts_with($reference, 'certifier_tier_payment:')) {
            $this->activateCertifierTier($reference, $paymentId, $payment['status']);
        } elseif (str_starts_with($reference, 'place_tier_payment:')) {
            $this->activatePlaceTier($reference, $paymentId, $payment['status']);
        }

        return response()->noContent();
    }

    private function activateCertifierTier(string $reference, string $paymentId, string $status): void
    {
        $tierPayment = CertifierTierPayment::find(substr($reference, strlen('certifier_tier_payment:')));
        if (! $tierPayment || $tierPayment->status === CertifierTierPayment::STATUS_APPROVED) {
            return;
        }

        if ($status === 'approved') {
            $tierPayment->update([
                'status'        => CertifierTierPayment::STATUS_APPROVED,
                'mp_payment_id' => $paymentId,
            ]);

            $certifier = $tierPayment->certifier;
            $certifier->update([
                'tier'                  => $tierPayment->tier,
                'tier_expires_at'       => TierPricingService::nextExpiry($certifier->tier_expires_at, $tierPayment->months),
                'tier_reminder_sent_at' => null,
            ]);
        }
    }

    private function activatePlaceTier(string $reference, string $paymentId, string $status): void
    {
        $tierPayment = PlaceTierPayment::find(substr($reference, strlen('place_tier_payment:')));
        if (! $tierPayment || $tierPayment->status === PlaceTierPayment::STATUS_APPROVED) {
            return;
        }

        if ($status === 'approved') {
            $tierPayment->update([
                'status'        => PlaceTierPayment::STATUS_APPROVED,
                'mp_payment_id' => $paymentId,
            ]);

            $place = $tierPayment->place;
            $place->update([
                'tier'                  => $tierPayment->tier,
                'tier_expires_at'       => TierPricingService::nextExpiry($place->tier_expires_at, $tierPayment->months),
                'tier_reminder_sent_at' => null,
            ]);
        }
    }
}
