<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Mechanisms\HandleRequests\HandleRequests;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Livewire genera la URL de actualizacion relativa a la raiz del dominio y
        // descarta el subdirectorio donde se publica la app. En /superoptica eso
        // hace que el POST vaya a /livewire/update y devuelva 404. Se devuelve la
        // URL real, que si incluye el subdirectorio.
        $this->app->bind(HandleRequests::class, fn () => new class extends HandleRequests
        {
            public function getUpdateUri()
            {
                return url('/livewire/update');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
