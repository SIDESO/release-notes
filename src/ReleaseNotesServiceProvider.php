<?php

namespace Sideso\ReleaseNotes;

use Illuminate\Support\ServiceProvider;

class ReleaseNotesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publicar configuración
        $this->publishes([
            __DIR__.'/../config/release-notes.php' => config_path('release-notes.php'),
        ], 'config');

        // Cargar vistas
        $this->loadViewsFrom(__DIR__.'/Views', 'release-notes');

        // Cargar rutas
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Registrar componente Blade
        \Illuminate\Support\Facades\Blade::component('release-notes', \Sideso\ReleaseNotes\View\Components\ReleaseNotes::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/release-notes.php', 'release-notes'
        );
    }
}
