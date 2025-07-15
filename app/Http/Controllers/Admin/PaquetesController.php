<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\PaquetesRepository;
use App\Repositories\ItinerarioRepository;
use App\Repositories\DestinosRepository;
use App\Repositories\ImagenesRepository;
use App\Repositories\PaisesRepository;

use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class PaquetesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct( 
        private readonly DestinosRepository $destinosRepository,
        private readonly ImagenesRepository $imagenesRepository,
        private readonly PaquetesRepository $paquetesRepository,
        private readonly ItinerarioRepository $itinerarioRepository,
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
        $paquetes = $this->paquetesRepository->searchBy(['type'=>'itinerario']);

        return view('admin.paquetes.home', compact('paquetes'));
    }

    public function add()
    {
        return view('admin.paquetes.add', [
            'paises'=>$this->paisesRepository->all(),
            'destinos'=>$this->destinosRepository->all(),
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

            $paquete  = $this->paquetesRepository->create([
                'destino_id'=>1,
                'titulo'=>$request->titulo,
                'slug'=>Str::slug($request->titulo),
                'descripcion'=>$request->descripcion,
                'include'=>$request->include,
                'noinclude'=>$request->noinclude,
                'informacion'=>$request->informacion,
                'dias'=>$request->dias,
                'pais'=>$request->pais,
                'imagen'=>$imagen,
                'dias'=>$request->dias,
                'views'=>'0',
            ]);

            foreach($data as $key => $value) {
                if (str_starts_with($key, 'destino_')) {
                    $destinoId = str_replace('destino_', '', $key);
                    if ($value) {
                        $this->paquetesRepository->addDestinos($paquete->id, [$destinoId]);
                    }
                }
            }


            $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$request->id, 'type'=>'paquetes']);

            foreach($imagenes as $imagen){

                $this->imagenesRepository->update($imagen->id, ['type_id'=>$paquete->id]);
            }


            $itinerarios = $this->itinerarioRepository->getItinerario($request->id);

            foreach($itinerarios as $i){
                $this->itinerarioRepository->attach($paquete->id, $i->id);
            }

        } catch (\Exception $e) {

            return redirect()->back()->withInput()->with('error',$e->getMessage() );

        }

        return redirect(route('admin.paquetes.index'))->with('message', 'Destino creado satisfactoriamente!');
    }


    public function duplicar($id)
    {
        $original = $this->paquetesRepository->find($id);

        $copy  = $this->paquetesRepository->create([
            'destino_id'=>1,
            'titulo'=>$original->titulo,
            'slug'=>Str::slug($original->titulo),
            'descripcion'=>$original->descripcion,
            'include'=>$original->include,
            'noinclude'=>$original->noinclude,
            'informacion'=>$original->informacion,
            'dias'=>$original->dias,
            'pais'=>$original->pais,
            'imagen'=>$original->imagen,
            'dias'=>$original->dias,
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

        $itinerarios = $this->itinerarioRepository->getItinerario($original->id);

        foreach($itinerarios as $i){
            $this->itinerarioRepository->attach($copy->id, $i->id);
        }



        return redirect(route('admin.paquetes.index'))->with('message', 'Destino clonado satisfactoriamente!');
    }

    public function edit($id)
    {
        $paquete = $this->paquetesRepository->find($id);

        $imagenes = $this->imagenesRepository->searchBy(['type_id'=>$paquete->id, 'type'=>'paquete']);

        $paises=$this->paisesRepository->all();

        $itinerarios=$this->itinerarioRepository->getItinerario($id);

        $destinos = $this->destinosRepository->all();

        $paquetesDestinos = $this->paquetesRepository->getDestinos($id);

        foreach($paquete->destinos() as $d){
            dd($d);
        }

        $paqueteDestinosArray = [];

        foreach($paquetesDestinos  as $pd){
            $paqueteDestinosArray[] = $pd->destino_id;
        }


        return view('admin.paquetes.edit', compact(
            'paquete',
            'imagenes',
            'paises',
            'itinerarios',
            'destinos',
            'paqueteDestinosArray'
        ));
    }

    public function update(Request $request, $id)
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

            $paquete  = $this->paquetesRepository->update($id, [
                'imagen'=>$imagen,
            ]);

        }

        try {

            $paquete  = $this->paquetesRepository->update($id, [
                'titulo'=>$request->titulo,
                'slug'=>Str::slug($request->titulo),
                'descripcion'=>$request->descripcion,
                'include'=>$request->include,
                'noinclude'=>$request->noinclude,
                'informacion'=>$request->informacion,
                'pais'=>$request->pais, 
                'idioma'=>$request->idioma,
            ]);

             $this->paquetesRepository->deleteAllDestinos($id);

              foreach($data as $key => $value) {
                if (str_starts_with($key, 'destino_')) {
                    $destinoId = str_replace('destino_', '', $key);
                    if ($value) {
                        $this->paquetesRepository->addDestinos($paquete->id, [$destinoId]);
                    }
                }
            }


        } catch (\Exception $e) {

            return redirect()->back()->with('error',$e->getMessage() );

        }

        return redirect(route('admin.paquetes.edit', ['id'=>$id]))->with('message', 'Destino actualizado satisfactoriamente!');
    }
    

    public function actualizar(Request $request)
    {

        $data = $request->all();

        try {

            $destino = $this->paquetesRepository->update($data['id'], $data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        return ['respuesta' => 'exito', 'message' => 'Destino actualizado correctamente', 'id' => $destino->id];
    }


    public function detail($id)
    {
        $destino = $this->paquetesRepository->find($id);

        return view('admin.paquetes.detail', compact('destino'));
    }

    public function estatus(Request $request)
    {
        try {
            $this->paquetesRepository->update($request->id, [
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

            $destino  = $this->paquetesRepository->delete($id);


        } catch (\Exception $e) {

            return redirect()->back()->with('error',$e->getMessage() );

        }

        return redirect(route('admin.paquetes.index'))->with('message', 'Destino Eliminado satisfactoriamente!');
    }
    

    public function itinerario(Request $request){

        $imagen='default.png';

        $picture = "";

        if ($request->hasFile('myfile')) {

            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/imagenes/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

        }

        if($request->id_itinerario){

            $itinerario = $this->itinerarioRepository->update($request->id_itinerario, [
                'titulo'=>$request->titulo,
                'descripcion'=>$request->descripcion,
                'imagen'=>$imagen,
            ]);

        }else{

            $itinerario = $this->itinerarioRepository->create([
                'titulo'=>$request->titulo,
                'descripcion'=>$request->descripcion,
                'imagen'=>$imagen,
            ]);

        }
        

        $this->itinerarioRepository->attach($request->id, $itinerario->id);

        $itinerarios=$this->itinerarioRepository->getItinerario($request->id);

        $view= View::make('admin.paquetes.listaItinerarios', compact('itinerarios'));

        $data=$view->render();

        return $data;
    }


    public function deleteitinerario(Request $request){

        $this->itinerarioRepository->deattach($request->id, $request->itinerario);

        $itinerarios=$this->itinerarioRepository->getItinerario($request->id);

        $view= View::make('admin.paquetes.listaItinerarios', compact('itinerarios'));

        return $view->render();
    }
   

    

}
