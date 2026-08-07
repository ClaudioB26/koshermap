<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use App\Models\ContactMessage;
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
}
