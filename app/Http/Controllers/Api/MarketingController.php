<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API para la notebook (misma clave que sync:push). Producción NO habla con
 * Brevo: entrega los contactos con permiso y recibe las bajas.
 */
class MarketingController extends Controller
{
    /** Contactos que pueden recibir campañas, por segmento. */
    public function contacts(): JsonResponse
    {
        $consumers = NewsletterSubscriber::where('status', NewsletterSubscriber::CONFIRMED)
            ->get(['email', 'locale'])
            ->map(fn ($s) => ['email' => $s->email, 'locale' => $s->locale, 'name' => null]);

        $unsubscribedEmails = NewsletterSubscriber::where('status', NewsletterSubscriber::UNSUBSCRIBED)->pluck('email')->all();

        // Comercios: cuentas que tienen una relación real con el sitio, es decir,
        // son certificadoras o cargaron al menos un local. Quien solo inició sesión
        // con Google (rol place_owner por defecto) NO entra: no tiene ficha.
        $businesses = User::query()
            ->where('role', '!=', User::ROLE_ADMIN)
            ->where(function ($q) {
                $q->where('role', User::ROLE_CERTIFIER)
                  ->orWhereIn('id', \App\Models\KosherPlace::whereNotNull('owner_id')->select('owner_id'));
            })
            ->where('marketing_opt_out', false)
            ->whereNotNull('email')
            ->get(['email', 'name', 'locale', 'role'])
            ->reject(fn ($u) => in_array(mb_strtolower($u->email), $unsubscribedEmails, true))
            ->map(fn ($u) => ['email' => mb_strtolower($u->email), 'locale' => $u->locale ?: 'es', 'name' => $u->name, 'role' => $u->role])
            ->values();

        // Bajas hechas en el sitio (para bloquearlas también en Brevo).
        $unsubscribed = collect($unsubscribedEmails)
            ->merge(User::where('marketing_opt_out', true)->pluck('email')->map(fn ($e) => mb_strtolower($e)))
            ->unique()->values();

        return response()->json([
            'consumers'    => $consumers->values(),
            'businesses'   => $businesses,
            'unsubscribed' => $unsubscribed,
        ]);
    }

    /** Bajas detectadas en Brevo (link de baja de una campaña, rebote duro o queja). */
    public function unsubscribes(Request $request): JsonResponse
    {
        $emails = collect($request->input('emails', []))
            ->filter(fn ($e) => is_string($e) && filter_var($e, FILTER_VALIDATE_EMAIL))
            ->map(fn ($e) => mb_strtolower($e))
            ->unique()->values();

        $subs = NewsletterSubscriber::whereIn('email', $emails)
            ->where('status', '!=', NewsletterSubscriber::UNSUBSCRIBED)
            ->update(['status' => NewsletterSubscriber::UNSUBSCRIBED, 'unsubscribed_at' => now()]);

        $users = User::whereIn('email', $emails)->where('marketing_opt_out', false)->update(['marketing_opt_out' => true]);

        return response()->json(['subscribers' => $subs, 'users' => $users]);
    }
}
