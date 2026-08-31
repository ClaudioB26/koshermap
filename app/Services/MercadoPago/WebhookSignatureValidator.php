<?php

namespace App\Services\MercadoPago;

/**
 * Valida la firma `x-signature` que manda Mercado Pago en cada webhook.
 *
 * No es la fuente de verdad del pago (eso lo sigue resolviendo
 * MercadoPagoClient::getPayment(), re-consultando la API -- ver comentario
 * en esa clase). Esto es una capa extra: rechazar de entrada una
 * notificación que no vino realmente de MP, sin gastar esa consulta.
 *
 * Implementado a mano siguiendo la spec pública de MP (manifest +
 * HMAC-SHA256) en vez de instalar el SDK oficial, mismo criterio que
 * MercadoPagoClient (evitar una dependencia de Composer que podría no
 * poder instalarse en hosting compartido).
 *
 * Spec: https://www.mercadopago.com.ar/developers/es/docs/your-integrations/notifications/webhooks
 * NO verificado contra un webhook real de MP todavía (hace falta el
 * "Secret" real de una cuenta configurada en su panel -- ver
 * `isValid()`, es opt-in: si el tenant no cargó `mp_webhook_secret`,
 * no se valida nada y el comportamiento es igual al de antes).
 */
class WebhookSignatureValidator
{
    /**
     * @param ?string $xSignatureHeader Header "x-signature" tal cual llega, ej. "ts=123,v1=abc..."
     * @param ?string $xRequestId Header "x-request-id"
     * @param ?string $dataId El "data.id" de la notificación (del query string)
     */
    public static function isValid(?string $xSignatureHeader, ?string $xRequestId, ?string $dataId, string $secret): bool
    {
        if (! $xSignatureHeader || ! $dataId) {
            return false;
        }

        $parts = [];
        foreach (explode(',', $xSignatureHeader) as $piece) {
            [$key, $value] = array_pad(explode('=', trim($piece), 2), 2, null);
            if ($key !== null && $value !== null) {
                $parts[trim($key)] = trim($value);
            }
        }

        $ts = $parts['ts'] ?? null;
        $v1 = $parts['v1'] ?? null;
        if (! $ts || ! $v1) {
            return false;
        }

        // El manifest solo incluye "request-id" si el header vino -- MP no
        // siempre lo manda en todos los tipos de notificación.
        $manifest = 'id:' . strtolower($dataId) . ';';
        if ($xRequestId) {
            $manifest .= "request-id:{$xRequestId};";
        }
        $manifest .= "ts:{$ts};";

        $computed = hash_hmac('sha256', $manifest, $secret);

        return hash_equals($computed, $v1);
    }
}
