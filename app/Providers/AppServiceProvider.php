<?php

namespace App\Providers;

use App\Models\Categorias;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CartRepository;
use App\Models\Destinos;
use App\Models\Paquete;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Session;

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

       /* $cartRepository = new CartRepository;
		view()->share('cart', $cartRepository->carrito());
		view()->share('categoriasMenu', Categorias::orderBy('titulo', 'asc')->get());
		view()->share('paquetesMenu', Paquete::where('type', 'itinerario')->get());
		view()->share('promocionesMenu', Paquete::where('type', 'promocion')->get());
		view()->share('configuracion', Configuracion::first());*/
    }
}
