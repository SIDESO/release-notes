<?php

namespace Sideso\ReleaseNotes\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sideso\ReleaseNotes\Services\GithubReleaseService;

class ReleaseNotesController extends Controller
{
    /**
     * Descarga un asset de GitHub y lo entrega como respuesta de descarga.
     */
    public function download(Request $request, GithubReleaseService $service)
    {
        $repo = $request->input('repo');
        $assetUrl = $request->input('asset_url');
        if (!$repo || !$assetUrl) {
            abort(400, 'Faltan parámetros requeridos.');
        }

        // Validar que no sea código fuente
        if (preg_match('/archive\/refs\/tags\/v[\d.]+\.(zip|tar\.gz|tar|gz|bz2|7z|rar)$/i', $assetUrl)) {
            abort(403, 'La descarga de código fuente no está permitida.');
        }

        // Obtener el contenido del asset desde GitHub
        $response = $service->downloadAsset($repo, $assetUrl);
        if (!$response || $response['status'] !== 200) {
            abort(404, 'No se pudo descargar el archivo.');
        }

        $filename = $request->input('file_name', basename($assetUrl));
        return response($response['body'], 200, [
            'Content-Type' => $response['content_type'] ?? 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
