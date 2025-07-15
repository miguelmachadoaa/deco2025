<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Productos;
use App\Models\Blogs;
use App\Models\Categorias;
use App\Models\ProductosImagenes;
use App\Models\Sliders;
use App\Models\Configuracion;
use App\Models\Contacto;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Repositories\BlogRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\VideosRepository;
use App\Repositories\DestinosRepository;
use App\Repositories\ImagenesRepository;
use App\Repositories\PaquetesRepository;
use App\Repositories\ItinerarioRepository;
use App\Repositories\EmailRepository;
use App\Repositories\PromocionesRepository;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected ProductRepository $productRepository,
        protected BlogRepository $blogRepository,
        protected CategoryRepository $categoryRepository,
        protected VideosRepository $videosRepository,
        protected DestinosRepository $destinosRepository,
        protected ImagenesRepository $imagenesRepository,
        protected PaquetesRepository $paquetesRepository,
        protected ItinerarioRepository $itinerarioRepository,
        protected EmailRepository $emailRepository,
        protected PromocionesRepository $promocionesRepository,
        )
    {

        $configuracion = Configuracion::first();

        if(!$configuracion->show_idioma){
            session(['locale' => 'es']);
        }

    }

    public function setlenguaje($locale)
    {

        $configuracion = Configuracion::first();

        if($configuracion->show_idioma){
            session(['locale' => $locale]);
        }else{
            session(['locale' => 'es']);
   
        }

        return back();
    }


    public function sendemail(Request $request){


        try {
            
            $contacto = Contacto::create([
                'nombre'=>$request->name,
                'apellido'=>$request->apellido,
                'email'=>$request->email,
                'telefono'=>$request->telefono??' ',
                'ciudad'=>$request->ciudad??' ',
                'pais'=>$request->pais??' ',
                'mensaje'=>$request->w3lMessage,
            ]);

            $this->emailRepository->contacto($contacto);
            
        } catch (\Throwable $th) {
            return redirect()->route('contacto')->with('error', __('main.errorEmail').' '.$th);    

        }

        return redirect()->route('contacto')->with('success', __('main.emailEnviado'));    


    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        session(['locale' => $idioma]);

        return view('index', [
            'productos'=>$this->productRepository->findRandom(9),
            'promociones'=>$this->paquetesRepository->searchBy(['idioma'=>$idioma, 'estatus'=>'1', 'type'=>'promocion'], 3, true),
            'blogs'=>$this->blogRepository->searchBy(['idioma'=>$idioma], 3, true),
            'categorias'=>$this->categoryRepository->findRandom(8),
            'videos'=>$this->videosRepository->findRandom(3),
            'sliders'=> Sliders::where('idioma', $idioma)->where('estatus', '1')->get(),
            'destinos'=>$this->destinosRepository->searchBy(['idioma'=>$idioma, 'estatus'=>'1'], 12, false),
            'paquetes'=>$this->paquetesRepository->searchBy(['idioma'=>$idioma, 'estatus'=>'1', 'type'=>'itinerario'], 12, true),
            'cart'=>$this->cartRepository->carrito(),
            'configuracion'=>Configuracion::first()
        ]);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contacto()
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        return view('contacto', [
            'productos'=>$this->productRepository->findRandom(9),
            'blogs'=>$this->blogRepository->findRandom(3),
            'categorias'=>$this->categoryRepository->findRandom(3),
            'videos'=>$this->videosRepository->findRandom(3),
            'sliders'=> Sliders::get(),
            'destinos'=>$this->destinosRepository->findRandom(8),
            'paquetes'=>$this->paquetesRepository->findRandom(8),
            'cart'=>$this->cartRepository->carrito(),
            'configuracion'=>Configuracion::first()
        ]);
    }



       /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function blogs()
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';


        return view('blogs', [
            'productos'=>$this->productRepository->findRandom(9),
            'blogs'=>$this->blogRepository->searchBy(['idioma'=>$idioma], null, true),
            'categorias'=>$this->categoryRepository->findRandom(3),
            'videos'=>$this->videosRepository->findRandom(3),
            'sliders'=> Sliders::where('idioma', $idioma)->get(),
            'destinos'=>$this->destinosRepository->searchBy(['idioma'=>$idioma], 8, true),
            'paquetes'=>$this->paquetesRepository->searchBy(['idioma'=>$idioma], 8, true),
            'cart'=>$this->cartRepository->carrito(),
            'configuracion'=>Configuracion::first()
        ]);
    }
    
         /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function categoriaDetalle($slug)
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        $categoria = $this->categoryRepository->searchOneBy(['slug'=>$slug]);

        return view('categorias', [
            'categoria'=>$categoria,
            'productos'=>$categoria->productos,
            'blogs'=>$this->blogRepository->searchBy(['idioma'=>$idioma], null, true),
            'categorias'=>$this->categoryRepository->findRandom(3),
            'videos'=>$this->videosRepository->findRandom(3),
            'cart'=>$this->cartRepository->carrito(),
            'configuracion'=>Configuracion::first()
        ]);
    }


     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destinosDetalle($slug)
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        $destino = $this->destinosRepository->searchOneBy(['slug'=>$slug]);

        $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$destino->id, 'type'=>'destino']);

        $paquetes = $this->destinosRepository->getPaquuetes($destino->id);

        return view('single_destino', [
            'destino'=>$destino,
            'paquetes'=>$paquetes,
            'imagenes'=>$imagenes,
            'productos'=>$this->productRepository->findRandom(9),
            'blogs'=>$this->blogRepository->searchBy(['idioma'=>$idioma], null, true),
            'categorias'=>$this->categoryRepository->findRandom(3),
            'videos'=>$this->videosRepository->findRandom(3),
            'sliders'=> Sliders::where('idioma', $idioma)->get(),
            'destinos'=>$this->destinosRepository->searchBy(['idioma'=>$idioma], 8, true),
            'cart'=>$this->cartRepository->carrito(),
            'configuracion'=>Configuracion::first()
        ]);
    }


    public function destinos()
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        $destinos = $this->destinosRepository->searchBy(['idioma'=>$idioma]);


        return view('list_destinos', [
            'destinos'=>$destinos,
            'blogs'=>$this->blogRepository->searchBy(['idioma'=>$idioma], null, true),
            'categorias'=>$this->categoryRepository->findRandom(3),
            'videos'=>$this->videosRepository->findRandom(3),
            'sliders'=> Sliders::where('idioma', $idioma)->get(),
            'destinos'=>$this->destinosRepository->searchBy(['idioma'=>$idioma], 8, true),
            'paquetes'=>$this->paquetesRepository->searchBy(['idioma'=>$idioma], 8, true),
            'configuracion'=>Configuracion::first()
        ]);
    }


    public function paquetesDetalle($slug)
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        $paquete = $this->paquetesRepository->searchOneBy(['slug'=>$slug]);

        $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$paquete->id, 'type'=>'paquete']);

        $itinerarios = $this->itinerarioRepository->getItinerario($paquete->id);

        return view('single_paquete', [
            'paquete'=>$paquete,
            'imagenes'=>$imagenes,
            'itinerarios'=>$itinerarios,
            'productos'=>$this->productRepository->findRandom(9),
            'blogs'=>$this->blogRepository->searchBy(['idioma'=>$idioma], null, true),
            'categorias'=>$this->categoryRepository->findRandom(3),
            'videos'=>$this->videosRepository->findRandom(3),
            'sliders'=> Sliders::where('idioma', $idioma)->get(),
            'destinos'=>$this->destinosRepository->searchBy(['idioma'=>$idioma], 8, true),
            'paquetes'=>$this->paquetesRepository->searchBy(['idioma'=>$idioma], 8, true),
            'cart'=>$this->cartRepository->carrito(),
            'configuracion'=>Configuracion::first()
        ]);
    }


    public function promocionesDetalle($slug)
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        $paquete = $this->paquetesRepository->searchOneBy(['slug'=>$slug]);

        $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$paquete->id, 'type'=>'paquete']);

        $itinerarios = $this->itinerarioRepository->getItinerario($paquete->id);

        return view('single_promocion', [
            'paquete'=>$paquete,
            'imagenes'=>$imagenes,
            'itinerarios'=>$itinerarios,
            'productos'=>$this->productRepository->findRandom(9),
            'blogs'=>$this->blogRepository->searchBy(['idioma'=>$idioma], null, true),
            'categorias'=>$this->categoryRepository->findRandom(3),
            'videos'=>$this->videosRepository->findRandom(3),
            'sliders'=> Sliders::where('idioma', $idioma)->get(),
            'destinos'=>$this->destinosRepository->searchBy(['idioma'=>$idioma], 8, true),
            'paquetes'=>$this->paquetesRepository->searchBy(['idioma'=>$idioma], 8, true),
            'cart'=>$this->cartRepository->carrito(),
            'configuracion'=>Configuracion::first()
        ]);
    }


    public function paquetes()
    {

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        $paquetes = $this->paquetesRepository->searchBy(['idioma'=>$idioma]);


        return view('list_paquete', [
            'paquetes'=>$paquetes,
            'blogs'=>$this->blogRepository->searchBy(['idioma'=>$idioma], null, true),
            'categorias'=>$this->categoryRepository->findRandom(3),
            'videos'=>$this->videosRepository->findRandom(3),
            'sliders'=> Sliders::where('idioma', $idioma)->get(),
            'destinos'=>$this->destinosRepository->searchBy(['idioma'=>$idioma], 8, true),
            'paquetes'=>$this->paquetesRepository->searchBy(['idioma'=>$idioma], 8, true),
            'cart'=>$this->cartRepository->carrito(),
            'configuracion'=>Configuracion::first()
        ]);
    }


    public function logout()
    {
        auth()->logout();
        return redirect(route('index'));
    }
}
