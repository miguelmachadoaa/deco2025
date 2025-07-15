<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Colores;
use App\Models\Materiales;
use App\Models\Categorias;
use App\Models\Productos;
use App\Models\ProductosImagenes;
use App\Models\CategoriasProductos;
use Auth;
use Illuminate\Support\Str;
use View;


class ProductsController extends Controller
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
        $productos = Productos::get();

        return view('admin.products.home', compact('productos'));
    }

    public function add()
    {
      $categorias = Categorias::all();

      $colores = Colores::all();

      $materiales = Materiales::all();

      return view('admin.products.add', compact('categorias', 'colores', 'materiales'));
    }


    public function store(Request $request)
    {

      $input = $request->all();

      $request->descripcion = $this->generaImagenes($request->descripcion);
      $request->caracteristicas = $this->generaImagenes($request->caracteristicas);
      $request->condiciones = $this->generaImagenes($request->condiciones);

      $producto = Productos::create([
        'titulo'=>$request->titulo,
        'slug'=>Str::slug($request->titulo),
        'precio'=>$request->precio,
        'descripcion'=>$request->descripcion,
        'caracteristicas'=>$request->caracteristicas,
        'condiciones'=>$request->condiciones,
        'marca'=>$request->marca,
        'estatus'=>1,
        'disponible'=>$request->disponible,
        'user_id'=>'1'
       ]);

      $producto->colors()->sync($request->input('colors', []));
      $producto->materials()->sync($request->input('materials', []));

       ProductosImagenes::where('id_producto', $request->id_product)->update(['id_producto'=>$producto->id]);

       foreach($input as $key => $value){
        if(substr($key,0,4)=='cate'){
          CategoriasProductos::create([
            'producto_id'=>$producto->id,
            'categoria_id'=>$value
          ]);
        }
       }

      return redirect('admin/products');
        
    }


    public function edit($id)
    {
        $producto = Productos::with('colores', 'materiales')->where('id', $id)->first();

        $categorias = Categorias::all();

        $categorias_poroducto= CategoriasProductos::where('producto_id', $producto->id)
        ->pluck('categoria_id')
        ->toArray();

        $imagenes = ProductosImagenes::where('id_producto', $id)->get();

         $colores = Colores::all();

      $materiales = Materiales::all();

        //dd($producto);

        return view('admin.products.edit', compact('producto', 'imagenes', 'categorias', 'categorias_poroducto', 'colores', 'materiales'));
    }


    public function update(Request $request)
    {

      $input = $request->all();

      $producto = Productos::where('id', $request->id)->first();


      $request->descripcion = $this->generaImagenes($request->descripcion);
      $request->caracteristicas = $this->generaImagenes($request->caracteristicas);
      $request->condiciones = $this->generaImagenes($request->condiciones);

       $producto->update([
        'titulo'=>$request->titulo,
        'slug'=>Str::slug($request->titulo),
        'precio'=>$request->precio,
        'descripcion'=>$request->descripcion??' ',
        'caracteristicas'=>$request->caracteristicas??' ',
        'condiciones'=>$request->condiciones??' ',
        'marca'=>$request->marca,
        'estatus'=>$request->estatus,
        'disponible'=>$request->disponible??1,
        'user_id'=>'1'
       ]);

       $producto->colors()->sync($request->input('colors', []));
      $producto->materials()->sync($request->input('materials', []));

       CategoriasProductos::where('producto_id', $producto->id)->delete();

       foreach($input as $key => $value){
        if(substr($key,0,4)=='cate'){
          CategoriasProductos::create([
            'producto_id'=>$producto->id,
            'categoria_id'=>$value
          ]);
        }
       }

      return redirect('admin/products');
        
    }

    public function delete($id)
    {

        $producto = Productos::where('id', $id)->delete();

        return redirect('admin/products');
        
    }

    public function uploadimg(Request $request, $id){

  
          $p=Productos::where('id', $id)->first();
  
          if(isset($p->id)){
  
            $nombre_imagen=$p->titulo;
  
          }else{
  
            $nombre_imagen='default';
  
          }
            $imagen='default.png';
  
           $picture = "";
          
          if ($request->hasFile('myfile')) {
  
              $file = $request->file('myfile');
              $extension = $file->extension()?: 'png';
              $picture = Str::random(10) . '.' . $extension;
              $destinationPath = public_path('/uploads/productos/');
              $file->move($destinationPath, $picture);
              $imagen = $picture;
  
          }
  
          $data = array(
            'id_producto' => $id,
            'imagen' => $imagen,
            'order' => 0,
            'title' => $nombre_imagen,
            'alt' => $nombre_imagen,
            'user_id' => Auth::getUser()->id
          );
  
          ProductosImagenes::create($data);
  
          $imagenes=ProductosImagenes::where('id_producto', $id)->get();
  
          $view= View::make('admin.products.imagenes', compact('imagenes'));
  
        $data=$view->render();
  
          return $data;
      }
  
  
       public function delimagenes(Request $request, $id){
  
          $imagen=ProductosImagenes::where('id', $id)->first();
  
          $id_producto=$imagen->id_producto;
  
          $imagen->delete();
  
  
          return redirect('admin/products/'.$id_producto.'/edit');
  
      }


}
