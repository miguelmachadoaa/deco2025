<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Sliders;
use App\Models\Videos;
use App\Models\Categorias;
use App\Models\Productos;
use App\Models\ProductosImagenes;
use Auth;
use Illuminate\Support\Str;
use View;


class VideosController extends Controller
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
        $videos = Videos::get();
        return view('admin.videos.home', compact('videos'));
    }

    public function add()
    {
        return view('admin.videos.add');
    }


    public function store(Request $request)
    {

        $imagen = 'default.jpg';

        $request->descripcion = $this->generaImagenes($request->descripcion);

        

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/videos/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

        }

        Videos::create([
        'titulo'=>$request->titulo,
        'descripcion'=>$request->descripcion,
        'enlace'=>$request->enlace,
        'imagen'=>$imagen,
        'estatus'=>1,
        'user_id'=>Auth::getUser()->id
       ]);


      return redirect('admin/videos');
        
    }


    public function edit($id)
    {
        $video = Videos::where('id', $id)->first();

        return view('admin.videos.edit', compact('video'));
    }


    public function update(Request $request)
    {

        $video = Videos::where('id', $request->id)->first();

        $imagen = $video->imagen;


        $request->descripcion = $this->generaImagenes($request->descripcion);

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/videos/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;
        }

       $video->update([
        'titulo'=>$request->titulo,
        'enlace'=>$request->enlace,
        'descripcion'=>$request->descripcion,
        'imagen'=>$imagen,
        'estatus'=>$request->estatus,
        'user_id'=>Auth::getUser()->id
       ]);

      return redirect('admin/videos');
        
    }

    public function delete($id)
    {

        $categoria = Videos::where('id', $id)->delete();

        return redirect('admin/videos');
        
    }

}
