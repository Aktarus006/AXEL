<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;
use Illuminate\Support\Facades\View;

class FolioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Folio::path(resource_path('views/pages'))
            ->middleware([
                '*' => [
                    \Illuminate\Routing\Middleware\SubstituteBindings::class,
                ],
            ]);

        // S'assurer que toutes les vues ont accès aux variables partagées
        View::composer('*', function ($view) {
            $variables = View::getShared();
            foreach ($variables as $key => $value) {
                if (!$view->offsetExists($key)) {
                    $view->with($key, $value);
                }
            }
        });
    }
}
