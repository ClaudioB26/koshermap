<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certifier;
use App\Models\CertifierTierPayment;
use App\Models\User;
use App\Services\Billing\TierPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CertifiersModerationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $certifiers = Certifier::with('owner')
            ->withCount('leads')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(30)
            ->withQueryString();

        $counts = Certifier::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pendingTransfers = CertifierTierPayment::with('certifier')
            ->where('payment_method', CertifierTierPayment::METHOD_TRANSFER)
            ->where('status', CertifierTierPayment::STATUS_PENDING)
            ->orderBy('created_at')
            ->get();

        return view('admin.certifiers.index', compact('certifiers', 'counts', 'status', 'pendingTransfers'));
    }

    public function approveTransfer(CertifierTierPayment $payment)
    {
        $payment->update(['status' => CertifierTierPayment::STATUS_APPROVED]);

        $certifier = $payment->certifier;
        $certifier->update([
            'tier'                  => $payment->tier,
            'tier_expires_at'       => TierPricingService::nextExpiry($certifier->tier_expires_at, $payment->months),
            'tier_reminder_sent_at' => null,
        ]);

        return back()->with('success', "Transferencia aprobada, plan de \"{$payment->certifier->name}\" activado.");
    }

    public function rejectTransfer(CertifierTierPayment $payment)
    {
        $payment->update(['status' => CertifierTierPayment::STATUS_REJECTED]);

        return back()->with('success', "Transferencia de \"{$payment->certifier->name}\" rechazada.");
    }

    public function approve(Certifier $certifier)
    {
        $certifier->update(['status' => Certifier::STATUS_APPROVED, 'rejection_reason' => null]);

        if ($certifier->owner_id) {
            User::where('id', $certifier->owner_id)->update([
                'role'         => User::ROLE_CERTIFIER,
                'certifier_id' => $certifier->id,
            ]);
        }

        $this->notifyOwner($certifier, 'aprobada', "¡Buenas noticias! Tu certificadora \"{$certifier->name}\" fue aprobada en KosherMap. Ya podés cargar tus productos entrando a tu cuenta en koshermap.org.");

        return back()->with('success', "\"$certifier->name\" aprobada.");
    }

    public function updateTier(Request $request, Certifier $certifier)
    {
        $request->validate([
            'tier' => 'required|in:' . implode(',', [Certifier::TIER_FREE, Certifier::TIER_DESTACADA, Certifier::TIER_PRO]),
        ]);

        $certifier->update(['tier' => $request->tier]);

        return back()->with('success', "Plan de \"$certifier->name\" actualizado a {$request->tier}.");
    }

    public function reject(Request $request, Certifier $certifier)
    {
        $reason = $request->input('reason');

        $certifier->update([
            'status'           => Certifier::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);

        $this->notifyOwner($certifier, 'rechazada', "Tu solicitud de alta para \"{$certifier->name}\" en KosherMap no fue aprobada."
            . ($reason ? " Motivo: {$reason}" : '') . " Podés escribirnos a info@koshermap.org si querés más información.");

        return back()->with('success', "\"$certifier->name\" rechazada.");
    }

    private function notifyOwner(Certifier $certifier, string $verb, string $body): void
    {
        $email = $certifier->submitted_by_email ?? $certifier->owner?->email;
        if (!$email) {
            return;
        }

        try {
            Mail::raw($body, function ($message) use ($email, $certifier, $verb) {
                $message->to($email)->subject("Tu certificadora \"{$certifier->name}\" fue {$verb} - KosherMap");
            });
        } catch (\Throwable $e) {
            Log::error("Error al notificar al dueño de la certificadora #{$certifier->id}: " . $e->getMessage());
        }
    }
}
