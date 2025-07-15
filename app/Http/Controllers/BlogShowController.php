<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BlogRepository;
use App\Repositories\DestinosRepository;
use App\Repositories\PaquetesRepository;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Session;

class BlogShowController extends Controller
{

    public function __construct(
        protected BlogRepository $blogRepository,
        protected DestinosRepository $destinosRepository,
        protected PaquetesRepository $paquetesRepository,
        )
    {
    }

    public function index(String $slug){

        $idioma = Session::get('locale');

        $idioma = $idioma??'es';

        $blog = $this->blogRepository->findBySlug($slug);

        $this->blogRepository->update($blog->id, ['views'=>$blog->views+1]);

        $blogs =$this->blogRepository->searchBy(['idioma'=>$idioma], 3, true);
        $destinos =$this->destinosRepository->searchBy(['idioma'=>$idioma], 9, true);
        $paquetes = $this->paquetesRepository->searchBy(['idioma'=>$idioma], 9, true);
        $configuracion = Configuracion::first();

        return view('single_blog', compact('blog', 'blogs','destinos','paquetes', 'configuracion'));
    }
}
