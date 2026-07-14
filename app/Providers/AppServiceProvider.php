<?php

namespace App\Providers;

use App\Models\Base\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        RateLimiter::for('api', function (Request $request) {
            $limit = env('API_RATE_LIMIT', 60);
            return Limit::perMinute($limit)->by($request->user()?->id ?: $request->ip());
        });

        // Configuración global para la generación de PDFs con Spatie (Browsershot)
        \Spatie\LaravelPdf\Facades\Pdf::default()
            ->withBrowsershot(function ($browsershot) {
                // Habilitar no-sandbox por defecto en producción o si se define en el .env
                if (config('app.env') === 'production' || env('PDF_NO_SANDBOX', true)) {
                    $browsershot->noSandbox();
                }

                // Cargar dinámicamente las rutas de Node/NPM si están definidas en el .env
                if ($nodePath = env('NODE_PATH')) {
                    $browsershot->setNodePath($nodePath);
                }
                if ($npmPath = env('NPM_PATH')) {
                    $browsershot->setNpmPath($npmPath);
                }
                if ($nodeBinary = env('NODE_BINARY')) {
                    $browsershot->setNodeBinary($nodeBinary);
                }
                if ($npmBinary = env('NPM_BINARY')) {
                    $browsershot->setNpmBinary($npmBinary);
                }
            });
    }
}
