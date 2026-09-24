<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Sirve a los admins los archivos subidos por usuarios (comprobantes de
 * transferencia, documentos de respaldo de certificadoras). No se exponen
 * por una URL publica: son comprobantes bancarios y documentacion
 * personal, y ademas asi no hace falta ningun symlink en el servidor
 * (en produccion el Document Root es la raiz del repo y un /storage
 * pisaria la carpeta real de Laravel).
 */
class PrivateFileController extends Controller
{
    private const ALLOWED_DIRS = ['tier_payment_proofs', 'certifier_documents'];

    public function show(string $path)
    {
        $parts = explode('/', $path);

        abort_unless(
            count($parts) === 2
                && in_array($parts[0], self::ALLOWED_DIRS, true)
                && $parts[1] !== ''
                && ! in_array('..', $parts, true),
            404
        );

        $disk = Storage::disk('public');
        abort_unless($disk->exists($path), 404);

        return $disk->response($path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'private, max-age=0, no-store',
        ]);
    }
}
