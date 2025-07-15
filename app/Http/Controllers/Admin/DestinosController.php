<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\DestinosRepository;
use App\Repositories\ImagenesRepository;
use App\Repositories\PaisesRepository;

use Illuminate\Support\Str;

class DestinosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct( 
        private readonly DestinosRepository $destinosRepository,
        private readonly ImagenesRepository $imagenesRepository,
        private readonly PaisesRepository $paisesRepository,
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
        $destinos = $this->destinosRepository->all();

        return view('admin.destinos.home', compact('destinos'));
    }

    public function add()
    {
        return view('admin.destinos.add', [
            'paises'=>$this->paisesRepository->all()
        ]);
    }

    public function store(Request $request)
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

            $destino  = $this->destinosRepository->create([
                'titulo'=>$request->titulo,
                'slug'=>Str::slug($request->titulo),
                'descripcion'=>$request->descripcion,
                'pais'=>$request->pais,
                'order'=>$request->order,
                'imagen'=>$imagen,
                'views'=>'0',
            ]);


            $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$request->id_imagen, 'type'=>'destino']);

            foreach($imagenes as $imagen){

                $this->imagenesRepository->update($imagen->id, ['type_id'=>$destino->id]);
            }


        } catch (\Exception $e) {

            return redirect()->back()->with('error',$e->getMessage() );

        }

        return redirect(route('admin.destinos.index'))->with('message', 'Destino creado satisfactoriamente!');
    }


    public function duplicar($id)
    {
        $original = $this->destinosRepository->find($id);

        $copy  = $this->destinosRepository->create([
            'titulo'=>$original->titulo,
            'slug'=>Str::slug($original->titulo.'_copy'),
            'descripcion'=>$original->descripcion,
            'pais'=>$original->pais,
            'imagen'=>$original->imagen,
            'views'=>'0',
        ]);

        $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$original->id]);

        foreach($imagenes as $imagen){

            $this->imagenesRepository->create([
                'url'=>$imagen->url,
                'titulo'=>$imagen->titulo, 
                'type'=>$imagen->type,
                'type_id'=>$copy->id
            ]);

        }

        return redirect(route('admin.destinos.edit', ['id'=>$copy->id]))->with('message', 'Destino clonado satisfactoriamente!');
    }

    public function edit($id)
    {
        $destino = $this->destinosRepository->find($id);

        $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$destino->id, 'type'=>'destino']);

        $paises = $this->paisesRepository->all();

        return view('admin.destinos.edit', compact(
            'destino',
            'imagenes',
            'paises',
        ));
    }

    public function update(Request $request, $id)
    {

        $imagen = 'default.jpg';

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/imagenes/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

            $destino  = $this->destinosRepository->update($id, [
                'imagen'=>$imagen,
            ]);

        }

        try {
            

            $destino  = $this->destinosRepository->update($id, [
                'titulo'=>$request->titulo,
                'slug'=>Str::slug($request->titulo),
                'descripcion'=>$request->descripcion, 
                'pais'=>$request->pais, 
                'idioma'=>$request->idioma, 
                'order'=>$request->order, 
            ]);


        } catch (\Exception $e) {

            return redirect()->back()->with('error',$e->getMessage() );

        }

        return redirect(route('admin.destinos.index'))->with('message', 'Destino actualizado satisfactoriamente!');
    }
    

    public function actualizar(Request $request)
    {

        $data = $request->all();

        try {

            $destino = $this->destinosRepository->update($data['id'], $data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        return ['respuesta' => 'exito', 'message' => 'Destino actualizado correctamente', 'id' => $destino->id];
    }


    public function detail($id)
    {
        $destino = $this->destinosRepository->find($id);

        return view('admin.destinos.detail', compact('destino'));
    }

    public function estatus(Request $request)
    {
        try {
            $this->destinosRepository->update($request->id, [
                'estatus'=>$request->estatus
            ]);

        } catch (\Throwable $th) {

           return false;

        }
       
       return true;
    }


    public function delete(Request $request, $id)
    {

        $imagen = 'default.jpg';


        try {

            $destino  = $this->destinosRepository->delete($id);


        } catch (\Exception $e) {

            return redirect()->back()->with('error',$e->getMessage() );

        }

        return redirect(route('admin.destinos.index'))->with('message', 'Destino Eliminado satisfactoriamente!');
    }


}
