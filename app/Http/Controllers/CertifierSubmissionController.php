<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CertifierSubmissionController extends Controller
{
    public function create(Request $request)
    {
        if (Certifier::where('owner_id', $request->user()->id)->exists()) {
            return redirect()->route('account.certifiers.my');
        }

        return view('certifiers.submit');
    }

    public function store(Request $request)
    {
        if (Certifier::where('owner_id', $request->user()->id)->exists()) {
            return redirect()->route('account.certifiers.my');
        }

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'rabbi_name'            => 'nullable|string|max:255',
            'founded_year'          => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'coverage_description'  => 'required|string|max:1000',
            'website'               => 'nullable|url|max:255',
            'reference_info'        => 'nullable|string|max:1000',
            'submitted_by_phone'    => 'nullable|string|max:50',
            'terms'                 => 'accepted',
        ]);

        $user = $request->user();

        $slug = Str::slug($validated['name']);
        $original = $slug;
        $n = 2;
        while (Certifier::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$n}";
            $n++;
        }

        $certifier = Certifier::create([
            'name'                 => $validated['name'],
            'slug'                 => $slug,
            'status'               => Certifier::STATUS_PENDING,
            'owner_id'             => $user->id,
            'rabbi_name'           => $validated['rabbi_name'] ?? null,
            'founded_year'         => $validated['founded_year'] ?? null,
            'coverage_description' => $validated['coverage_description'],
            'website'              => $validated['website'] ?? null,
            'reference_info'       => $validated['reference_info'] ?? null,
            'submitted_by_name'    => $user->name,
            'submitted_by_email'   => $user->email,
            'submitted_by_phone'   => $validated['submitted_by_phone'] ?? null,
        ]);

        try {
            Mail::raw(
                "Nueva solicitud de alta de certificadora en KosherMap.\n\n"
                . "Nombre: {$certifier->name}\n"
                . "Rabino a cargo: " . ($certifier->rabbi_name ?: '—') . "\n"
                . "Desde: " . ($certifier->founded_year ?: '—') . "\n"
                . "Cobertura: {$certifier->coverage_description}\n"
                . "Sitio web: " . ($certifier->website ?: '—') . "\n"
                . "Referencias: " . ($certifier->reference_info ?: '—') . "\n\n"
                . "Enviado por: {$user->name} ({$user->email})"
                . ($certifier->submitted_by_phone ? " / {$certifier->submitted_by_phone}" : '') . "\n\n"
                . "Revisar en: " . route('admin.certifiers.index'),
                function ($message) use ($certifier) {
                    $message->to('info@koshermap.org')
                            ->subject('Nueva certificadora pendiente: ' . $certifier->name);
                }
            );
        } catch (\Throwable $e) {
            Log::error('Error al enviar email de alta de certificadora: ' . $e->getMessage());
        }

        return redirect()->route('account.certifiers.my')
            ->with('success', '¡Gracias! Recibimos tu solicitud. La vamos a revisar y te contactamos en aproximadamente una semana.');
    }
}
