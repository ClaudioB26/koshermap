<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Exporta leads de certificacion a CSV. Lo usan el admin (todas las
 * certificadoras) y cada certificadora (solo los suyos).
 */
class LeadsCsv
{
    public static function download($query, bool $withCertifier): StreamedResponse
    {
        $filename = 'leads-koshermap-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($query, $withCertifier) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8: Excel reconoce los acentos

            $header = ['Fecha'];
            if ($withCertifier) {
                $header[] = 'Certificadora';
            }
            array_push($header, 'Empresa', 'Contacto', 'Email', 'Telefono', 'Producto', 'Mensaje');
            fputcsv($out, $header, ',', '"', '');

            foreach ($query->cursor() as $lead) {
                $row = [$lead->created_at->format('Y-m-d H:i')];
                if ($withCertifier) {
                    $row[] = $lead->certifier?->name;
                }
                array_push($row, $lead->company, $lead->name, $lead->email, $lead->phone, $lead->product_type, $lead->message);

                fputcsv($out, array_map([self::class, 'safeCell'], $row), ',', '"', '');
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Los datos los escribe cualquier visitante. Una celda que empiece con
     * = + - @ se interpreta como formula al abrir el CSV en Excel (inyeccion
     * de formulas), asi que se le antepone un apostrofe para que quede texto.
     */
    public static function safeCell(?string $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@\t\r]/', $value) ? "'" . $value : $value;
    }
}
