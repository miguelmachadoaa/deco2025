<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Sliders;
use App\Models\Categorias;
use App\Models\Productos;
use App\Models\ProductosImagenes;
use Auth;
use Illuminate\Support\Str;
use View;


class SlidersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
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
        $sliders = Sliders::get();
        return view('admin.sliders.home', compact('sliders'));
    }

    public function add()
    {
        return view('admin.sliders.add');
    }


    public function store(Request $request)
    {

        $imagen = 'default.jpg';

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/sliders/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

        }

        Sliders::create([
        'titulo'=>$request->titulo,
        'descripcion'=>strip_tags( $request->descripcion),
        'enlace'=>$request->enlace,
        'idioma'=>$request->idioma,
        'imagen'=>$imagen,
        'estatus'=>1,
        'user_id'=>Auth::getUser()->id
       ]);


      return redirect('admin/sliders');
        
    }


    public function edit($id)
    {
        $categoria = Sliders::where('id', $id)->first();

        return view('admin.sliders.edit', compact('categoria'));
    }


    public function update(Request $request)
    {

        $categoria = Sliders::where('id', $request->id)->first();

        $imagen = $categoria->imagen;

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/sliders/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;
        }

       $categoria->update([
        'titulo'=>$request->titulo,
        'enlace'=>$request->enlace,
        'descripcion'=>strip_tags( $request->descripcion),
        'idioma'=>$request->idioma,
        'imagen'=>$imagen,
        'estatus'=>$request->estatus,
        'user_id'=>Auth::getUser()->id
       ]);

      return redirect('admin/sliders');
        
    }

    public function delete($id)
    {

        $categoria = Sliders::where('id', $id)->delete();

        return redirect('admin/sliders');
        
    }


}
