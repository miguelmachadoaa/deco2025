<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Categorias;
use App\Models\Productos;
use App\Models\ProductosImagenes;
use Auth;
use Illuminate\Support\Str;
use View;


class CategoriasController extends Controller
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
        $categorias = categorias::get();
        return view('admin.categorias.home', compact('categorias'));
    }

    public function add()
    {
        return view('admin.categorias.add');
    }


    public function store(Request $request)
    {

        $imagen = 'default.jpg';

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/categorias/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

        }

        $request->descripcion = $this->generaImagenes($request->descripcion);

        Categorias::create([
        'titulo'=>$request->titulo,
        'slug'=>Str::slug($request->titulo),
        'descripcion'=>$request->descripcion,
        'imagen'=>$imagen,
        'estatus'=>1,
        'user_id'=>Auth::getUser()->id
       ]);


      return redirect('admin/categorias');
        
    }


    public function edit($id)
    {
        $categoria = Categorias::where('id', $id)->first();


        return view('admin.categorias.edit', compact('categoria'));
    }


    public function update(Request $request)
    {

        $categoria = Categorias::where('id', $request->id)->first();

        $imagen = $categoria->imagen;

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/categorias/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

        }


        $request->descripcion = $this->generaImagenes($request->descripcion);


       $categoria->update([
        'titulo'=>$request->titulo,
        'descripcion'=>$request->descripcion,
        'slug'=>Str::slug($request->titulo),
        'imagen'=>$imagen,
       // 'estatus'=>$request->estatus,
        'user_id'=>Auth::getUser()->id
       ]);

      return redirect('admin/categorias');
        
    }

    public function delete($id)
    {

        $categoria = Categorias::where('id', $id)->delete();

        return redirect('admin/categorias');
        
    }

}
