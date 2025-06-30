<?php

namespace Sideso\ReleaseNotes\Services;

use Illuminate\Support\Facades\Http;

class GithubReleaseService
{
    protected $token;

    public function __construct()
    {
        $this->token = env('GITHUB_TOKEN');
    }

    public function getReleases($repo, $allowedAssets = [], $perPage = 10, $page = 1)
    {
        $url = "https://api.github.com/repos/{$repo}/releases?per_page={$perPage}&page={$page}";
        $response = Http::withToken($this->token)
            ->accept('application/vnd.github+json')
            ->get($url);

        if ($response->failed()) {
            return [];
        }

        $releases = collect($response->json())->map(function ($release) use ($allowedAssets) {
            $assets = collect($release['assets'] ?? [])->filter(function ($asset) use ($allowedAssets) {
                $name = $asset['name'] ?? '';
                // Excluir código fuente
                if (preg_match('/source\.(zip|tar\.gz|tar|gz|bz2|7z|rar)$/i', $name)) {
                    return false;
                }
                // Incluir si contiene "installer" o está en la lista personalizada
                if (stripos($name, 'installer') !== false) {
                    return true;
                }
                foreach ($allowedAssets as $pattern) {
                    if (preg_match($pattern, $name)) {
                        return true;
                    }
                }
                return false;
            })->values();
            $release['assets'] = $assets;
            return $release;
        })->filter(function ($release) {
            return count($release['assets']) > 0;
        })->values();

        return $releases;
    }

    /**
     * Descarga un asset de GitHub y retorna el contenido y tipo de contenido.
     */
    public function downloadAsset($repo, $assetUrl)
    {
        // Validar que la URL sea de github.com o subdominios y use https
        $parsed = parse_url($assetUrl);
        $host = $parsed['host'] ?? '';
        $scheme = $parsed['scheme'] ?? '';
        if ($scheme !== 'https' || !preg_match('/(^|\.)github\.com$/i', $host)) {
            return [
                'status' => 400,
                'body' => 'URL de asset no permitida.',
                'content_type' => 'text/plain',
            ];
        }

        $response = Http::withToken($this->token)
            ->withHeaders([
                'Accept' => 'application/octet-stream',
            ])
            ->get($assetUrl);

        return [
            'status' => $response->status(),
            'body' => $response->body(),
            'content_type' => $response->header('Content-Type'),
        ];
    }
}
