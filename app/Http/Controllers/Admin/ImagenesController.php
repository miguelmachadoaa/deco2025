<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\ImagenesRepository;

use Auth;
use Illuminate\Support\Str;
use View;

class ImagenesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct( 
        private readonly ImagenesRepository $imagenesRepository
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
        $destinos = $this->imagenesRepository->all()->toArray();

        return view('admin.imagenes.home', compact('destinos'));
    }

    public function add()
    {
        return view('admin.imagenes.add');
    }

    public function store(Request $request, $id, $type)
    {

        $data = $request->all();

        $imagen = 'default.jpg';

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/imagenes/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

        }

        try {

            $this->imagenesRepository->create([
                'url'=>$imagen,
                'titulo'=>'', 
                'type'=>$type,
                'type_id'=>$id
            ]);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$id, 'type'=>$type]);

        $view= View::make('admin.imagenes.imagenes', compact('imagenes'));
  
        $data=$view->render();
  
          return $data;
    }

    public function edit($id)
    {
        $destino = $this->imagenesRepository->find($id);

        return view('admin.imagenes.edit', compact(
            'destino',
        ));
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();

        if(isset($data['_token'])){ unset($data['_token']);} 
        if(isset($data['fecha_creacion'])){ unset($data['fecha_creacion']);} 

        $data['id']=$id;

        try {

            $client = $this->imagenesRepository->update($data['id'], $data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }


        return redirect(route('admin.imagenes.index'));
    }
    

    public function actualizar(Request $request)
    {

        $data = $request->all();

        try {

            $destino = $this->imagenesRepository->update($data['id'], $data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        return ['respuesta' => 'exito', 'message' => 'Destino actualizado correctamente', 'id' => $destino->id];
    }


    public function detail($id)
    {
        $destino = $this->imagenesRepository->find($id);

        return view('admin.imagenes.detail', compact('destino'));
    }

    public function estatus(Request $request)
    {
        try {
            $this->imagenesRepository->update($request->id, [
                'estatus'=>$request->estatus
            ]);

        } catch (\Throwable $th) {

           return false;

        }
       
       return true;
    }

    public function delete(Request $request, $id)
    {

        $imagen = $this->imagenesRepository->find($id);

        if($imagen){
            $this->imagenesRepository->delete($id);
        }
        
        $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$imagen->type_id, 'type'=>$imagen->type]);

        $view= View::make('admin.imagenes.imagenes', compact('imagenes'));
  
        $data=$view->render();
  
        return $data;
    }

   

    

}
