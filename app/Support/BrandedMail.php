<?php

namespace App\Support;

use Illuminate\Support\Facades\Mail;

/**
 * Mails del sitio con la marca de KosherMap (logo, tarjeta, botón) en vez del
 * texto plano de Mail::raw. Siempre lleva alternativa en texto plano.
 * Lanza la excepción del transporte: cada llamador decide cómo registrarla.
 */
class BrandedMail
{
    /**
     * @param array<int,string>     $paragraphs  texto libre (se escapa)
     * @param array<string,string>  $details     tabla etiqueta => valor
     * @param array{0:string,1:string}|null $button  [texto, url]
     * @param array{replyTo?:array{0:string,1:string}, bcc?:string} $opts
     */
    public static function send(
        string $to,
        string $subject,
        string $heading,
        array $paragraphs = [],
        array $details = [],
        ?array $button = null,
        array $opts = []
    ): void {
        $data = compact('heading', 'paragraphs', 'details', 'button');
        $html = view('emails.branded', $data)->render();

        $text = $heading . "\n\n" . implode("\n\n", $paragraphs);
        foreach ($details as $label => $value) {
            $text .= "\n{$label}: {$value}";
        }
        if ($button) {
            $text .= "\n\n{$button[0]}: {$button[1]}";
        }

        Mail::send([], [], function ($m) use ($to, $subject, $html, $text, $opts) {
            $m->from(config('mail.from.address'), 'KosherMap')
              ->to($to)
              ->subject($subject)
              ->html($html)
              ->text($text);

            if (!empty($opts['replyTo'])) {
                $m->replyTo($opts['replyTo'][0], $opts['replyTo'][1] ?? null);
            }
            if (!empty($opts['bcc'])) {
                $m->bcc($opts['bcc']);
            }
        });
    }
}
