<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use App\Models\CertifierLead;
use App\Models\ContactMessage;
use App\Rules\Turnstile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'message'           => 'required|string|max:2000',
            'accepted_privacy'  => 'accepted',
            'cf-turnstile-response' => [new Turnstile],
        ]);

        ContactMessage::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'message'          => $request->message,
            'accepted_privacy' => true,
        ]);

        try {
            Mail::raw(
                "Nombre: {$request->name}\nEmail: {$request->email}\n\nMensaje:\n{$request->message}",
                function ($message) use ($request) {
                    $message->to('info@koshermap.org')
                            ->replyTo($request->email, $request->name)
                            ->subject('Nuevo mensaje de contacto - KosherMap');
                }
            );
        } catch (\Throwable $e) {
            Log::error('Error al enviar email de contacto: ' . $e->getMessage());
        }

        return back()->with('contact_sent', true);
    }

    public function certifierContact(Request $request, string $slug)
    {
        $certifier = Certifier::where('slug', $slug)->approved()->firstOrFail();
        $intent = $request->query('intent') === 'certify' ? 'certify' : 'general';

        return view('catalog.certifiers.contacto', compact('certifier', 'intent'));
    }

    public function storeCertifierLead(Request $request, string $slug)
    {
        $certifier = Certifier::where('slug', $slug)->approved()->firstOrFail();

        // Honeypot: campo invisible para usuarios reales, los bots de spam lo
        // completan igual. Si viene con valor, fingimos exito sin guardar nada.
        // El formulario de "querés certificar tu empresa" ya existió antes (ago
        // 2026) y se sacó por spam sin ninguna protección; esta vez se agrega.
        if ($request->filled('website')) {
            return redirect()->route('certifiers.contact', ['slug' => $slug, 'intent' => 'certify'])
                ->with('lead_sent', true);
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'company'      => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'product_type' => 'nullable|string|max:255',
            'message'      => 'nullable|string|max:1000',
            'cf-turnstile-response' => [new Turnstile],
        ]);

        unset($validated['cf-turnstile-response']);

        $lead = CertifierLead::create(array_merge($validated, ['certifier_id' => $certifier->id]));

        // Destinatario: el mail de contacto de la certificadora; si no tiene (las que
        // se dan de alta por el formulario solo guardan el email del solicitante,
        // nunca contact_email), el del solicitante o el del usuario dueño. Antes,
        // sin contact_email no se mandaba nada y el lead se perdia en silencio.
        $adminEmail  = 'info@koshermap.org';
        $notifyEmail = $certifier->contact_email
            ?: $certifier->submitted_by_email
            ?: $certifier->owner?->email
            ?: $adminEmail;

        try {
            Mail::raw(
                "Nueva empresa interesada en certificarse con {$certifier->name} via KosherMap.\n\n"
                . "Empresa: {$lead->company}\n"
                . "Contacto: {$lead->name}\n"
                . "Email: {$lead->email}\n"
                . "Telefono: " . ($lead->phone ?: '—') . "\n"
                . "Tipo de producto: " . ($lead->product_type ?: '—') . "\n"
                . "Mensaje: " . ($lead->message ?: '—'),
                function ($message) use ($lead, $notifyEmail, $adminEmail) {
                    $message->to($notifyEmail)
                            ->replyTo($lead->email, $lead->name)
                            ->subject('Empresa interesada en certificarse - via KosherMap');

                    // Copia oculta a KosherMap: cada lead queda registrado tambien
                    // en el mail del sitio, que es el dato con el que despues se le
                    // demuestra a cada certificadora cuantos clientes se le mandaron.
                    if (strcasecmp($notifyEmail, $adminEmail) !== 0) {
                        $message->bcc($adminEmail);
                    }
                }
            );
        } catch (\Throwable $e) {
            Log::error("Error al enviar lead #{$lead->id} de certificacion a {$notifyEmail}: " . $e->getMessage());
        }

        return redirect()->route('certifiers.contact', ['slug' => $slug, 'intent' => 'certify'])
            ->with('lead_sent', true);
    }
}
