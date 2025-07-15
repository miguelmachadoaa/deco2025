<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\ClientesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\PagosRepository;
use App\Repositories\AbonoRepository;
use App\Repositories\BlogRepository;
use App\Repositories\DestinosRepository;
use App\Repositories\PaquetesRepository;
use App\Repositories\PromocionesRepository;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly ClientesRepository $clientesRepository,
        private readonly ContratoRepository $contratoRepository,
        private readonly PagosRepository $pagosRepository,
        private readonly AbonoRepository $abonoRepository,
        private readonly ContratoDetallesRepository $contratoDetallesRepository,
        private readonly BlogRepository $blogRepository,
        private readonly DestinosRepository $destinosRepository,
        private readonly PaquetesRepository $paquetesRepository,
        private readonly PromocionesRepository $promocionesRepository,
    )
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $blogs = $this->blogRepository->all()->count();
        $destinos = $this->destinosRepository->all()->count();
        $paquetes = $this->paquetesRepository->all()->count();
        $promociones = $this->promocionesRepository->all()->count();
        

        return view('admin.home.home', compact(
            'blogs',
            'destinos',
            'paquetes',
            'promociones',
        ));
    }
}
