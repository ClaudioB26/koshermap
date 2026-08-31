<?php

namespace App\Services\MercadoPago;

use Illuminate\Support\Facades\Http;

/**
 * Cliente HTTP generico de Mercado Pago (Checkout Pro). Portado de
 * mayorista-platform (mismo patron, un solo access token: el de la cuenta
 * de MP de KosherMap, que es quien cobra a certificadoras/comercios).
 *
 * A proposito NO sabe nada de planes ni pagos — solo recibe datos
 * genericos (titulo, monto, referencia externa, URLs de retorno) y
 * devuelve la preferencia de pago. Que se hace del lado de negocio cuando
 * se confirma el pago vive en el controller, no aca.
 *
 * No usa el SDK oficial de Mercado Pago a proposito: son dos llamadas HTTP
 * simples y evita una dependencia de Composer que podria no instalarse en
 * hosting compartido sin acceso a shell completo.
 */
class MercadoPagoClient
{
    private const BASE_URL = 'https://api.mercadopago.com';

    public function __construct(private string $accessToken)
    {
    }

    /**
     * Crea una preferencia de pago (Checkout Pro) y devuelve el id y la
     * URL a la que hay que redirigir al comprador.
     *
     * @param array<int, array{title:string, quantity:int, unit_price:float}> $items
     * @param array{success:string, failure:string, pending:string} $backUrls
     * @return array{id:string, init_point:string}
     */
    public function createPreference(
        array $items,
        string $externalReference,
        array $backUrls,
        ?string $notificationUrl = null,
    ): array {
        $payload = [
            'items' => array_map(fn ($item) => [
                'title'      => $item['title'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'currency_id' => $item['currency_id'] ?? 'ARS',
            ], $items),
            'external_reference' => $externalReference,
            'back_urls' => $backUrls,
        ];

        // MP exige que back_urls.success sea https para poder usar
        // auto_return (si no, rechaza la preferencia entera con 400). En
        // local (http://*.test) no lo mandamos -- el comprador vuelve
        // clickeando el link en vez de que lo redirija solo. En
        // producción (https) esto sí manda auto_return normalmente.
        if (str_starts_with($backUrls['success'] ?? '', 'https://')) {
            $payload['auto_return'] = 'approved';
        }

        if ($notificationUrl) {
            $payload['notification_url'] = $notificationUrl;
        }

        $response = Http::withToken($this->accessToken)
            ->acceptJson()
            ->post(self::BASE_URL . '/checkout/preferences', $payload)
            ->throw();

        $data = $response->json();

        return [
            'id' => $data['id'],
            'init_point' => $data['init_point'],
        ];
    }

    /**
     * Consulta el estado real de un pago contra la API de MP (nunca hay
     * que confiar ciegamente en el contenido del webhook: solo indica que
     * "algo pasó", hay que ir a buscar el estado real con este método).
     *
     * @return array{status:string, external_reference:?string, transaction_amount:?float}
     */
    public function getPayment(string $paymentId): array
    {
        $response = Http::withToken($this->accessToken)
            ->acceptJson()
            ->get(self::BASE_URL . "/v1/payments/{$paymentId}")
            ->throw();

        $data = $response->json();

        return [
            'status' => $data['status'] ?? 'unknown',
            'external_reference' => $data['external_reference'] ?? null,
            'transaction_amount' => $data['transaction_amount'] ?? null,
        ];
    }
}
