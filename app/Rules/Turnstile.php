<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Valida el token de Cloudflare Turnstile (campo "cf-turnstile-response" que
 * agrega el widget al formulario) contra la API de Cloudflare.
 *
 * - Sin TURNSTILE_SECRET_KEY configurada no se exige nada: asi los formularios
 *   siguen andando en local y en el momento entre desplegar el codigo y cargar
 *   las claves en el .env.
 * - Si Cloudflare no responde (timeout, caida), se deja pasar y se loguea: un
 *   problema de ellos no deberia bloquear un contacto o un lead legitimo.
 */
class Turnstile implements ValidationRule
{
    // Implicita: tiene que correr aun cuando el campo venga vacio o falte.
    public bool $implicit = true;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret_key');
        if (! $secret) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Confirmá que no sos un robot.');
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret'   => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Turnstile: no se pudo verificar, se deja pasar. ' . $e->getMessage());
            return;
        }

        if ($response->serverError()) {
            Log::warning('Turnstile: Cloudflare respondio ' . $response->status() . ', se deja pasar.');
            return;
        }

        if (! $response->json('success')) {
            $fail('No pudimos verificar que no sos un robot. Probá de nuevo.');
        }
    }
}
