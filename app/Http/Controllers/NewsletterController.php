<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use App\Rules\Turnstile;
use App\Support\BrandedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Suscripción al newsletter con doble confirmación (alta -> mail -> link).
 * Las campañas las administra la notebook (ver doc/plan-marketing-brevo.md);
 * acá solo se guarda el consentimiento.
 */
class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot: un bot lo completa, una persona no lo ve.
        if ($request->filled('website')) {
            return back()->with('newsletter_status', 'check_email');
        }

        $data = $request->validate([
            'email'                 => 'required|email:rfc|max:255',
            'cf-turnstile-response' => [new Turnstile],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $locale = in_array(app()->getLocale(), ['es', 'en', 'pt', 'fr', 'ru', 'he'], true) ? app()->getLocale() : 'es';

        $sub = NewsletterSubscriber::firstOrNew(['email' => $email]);

        if ($sub->exists && $sub->status === NewsletterSubscriber::CONFIRMED) {
            return $this->back('already');
        }

        $sub->fill([
            'locale'       => $locale,
            'status'       => NewsletterSubscriber::PENDING,
            'token'        => Str::random(40),
            'consent_ip'   => $request->ip(),
            'consented_at' => now(),
            'source'       => (string) $request->input('source', 'footer'),
        ])->save();

        try {
            BrandedMail::withLocale($locale, function () use ($sub) {
                BrandedMail::send(
                    $sub->email,
                    __('newsletter.mail_subject'),
                    __('newsletter.mail_heading'),
                    [__('newsletter.mail_body'), __('newsletter.mail_ignore')],
                    [],
                    [__('newsletter.mail_button'), route('newsletter.confirm', $sub->token)]
                );
            });
        } catch (\Throwable $e) {
            Log::error('Newsletter: no se pudo enviar la confirmación a ' . $sub->email . ': ' . $e->getMessage());
        }

        return $this->back('check_email');
    }

    public function confirm(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();

        if ($sub->status !== NewsletterSubscriber::CONFIRMED) {
            $sub->update(['status' => NewsletterSubscriber::CONFIRMED, 'confirmed_at' => now(), 'unsubscribed_at' => null]);
        }

        return view('newsletter.message', ['title' => __('newsletter.confirmed_title'), 'body' => __('newsletter.confirmed_body')]);
    }

    public function unsubscribeForm(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();

        return view('newsletter.unsubscribe', ['sub' => $sub]);
    }

    public function unsubscribe(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $sub->update(['status' => NewsletterSubscriber::UNSUBSCRIBED, 'unsubscribed_at' => now()]);

        return view('newsletter.message', ['title' => __('newsletter.unsub_done_title'), 'body' => __('newsletter.unsub_done_body')]);
    }

    private function back(string $status)
    {
        return redirect(url()->previous() . '#newsletter')->with('newsletter_status', $status);
    }
}
