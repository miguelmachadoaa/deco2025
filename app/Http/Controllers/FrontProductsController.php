<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Productos;
use App\Models\ProductosImagenes;
use App\Models\CategoriasProductos;

use Illuminate\Http\Request;

class FrontProductsController extends Controller
{
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $productos = Productos::with('imagenes', 'categorias')->get();

        $categorias = Categorias::get();

        

        return view('products', compact('productos', 'categorias'));
    }

    public function detalle($slug)
    {
        $producto = Productos::with('categorias', 'imagenes')->where('slug', $slug)->first();

        $productos = Productos::with('imagenes', 'categorias')->limit(3)->inRandomOrder()->get();

        $categorias = Categorias::all();

        return view('single', compact('producto', 'categorias', 'productos'));
    }
}
