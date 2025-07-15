<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\ClientesRepository;
use App\Repositories\ContractoTipoRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ContractoCategoriaRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SaldoRepository;
use App\Repositories\PagosRepository;
use App\Repositories\ContratosGruposRepository;
use Auth;
use App\Models\Clientes;
use App\Models\ClientesReferencias;
use App\Models\ClientesImagenes;
use Illuminate\Support\Str;
use View;

class ClientesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct( 
        private readonly ClientesRepository $clientesRepository,
        private readonly ContractoTipoRepository $contratoTipoRepository,
        private readonly ContractoCategoriaRepository $contratoCategoriasRepository,
        private readonly ContratoRepository $contratoRepository,
        private readonly UserRepository $userRepository,
        private readonly ProductRepository $productRepository,
        private readonly SaldoRepository $saldoRepository,
        private readonly PagosRepository $pagosRepository,
        private readonly ContratosGruposRepository $contratosGruposRepository,
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
        $clientes = $this->clientesRepository->all();

        return view('admin.clientes.home', compact('clientes'));
    }

    public function morosos()
    {
        $clientes = $this->clientesRepository->morosos();

        return view('admin.clientes.home', compact('clientes'));
    }

    public function add()
    {
        return view('admin.clientes.add');
    }

    public function store(Request $request)
    {

        $archivo = null;

            if ($request->hasFile('myfile')) {
    
                $file = $request->file('myfile');
                $extension = $file->extension()?: 'png';
                $picture = Str::random(10) . '.' . $extension;
                $destinationPath = public_path('/uploads/abonos/');
                $file->move($destinationPath, $picture);
                $archivo = $picture;

            }

            $data = $request->all();

            $data['archivo']= $archivo;

            $data['password']=$data['documento'];
            $data['repassword']=$data['documento'];
            $data['rol']='4';
      
        try {

            $user = $this->userRepository->create($data);

            $data['usuario_relacionado']=$user->id;

            $client = $this->clientesRepository->create($data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        return ['respuesta' => 'exito', 'message' => 'Cliente creado correctamente', 'id' => $client->id];
    }

    public function edit($id)
    {
        $cliente = $this->clientesRepository->find($id);

        return view('admin.clientes.edit', compact('cliente'));
    }

    public function detail($id)
    {
        $cliente = $this->clientesRepository->find($id);

        $pagos = $this->pagosRepository->searchBy(['cliente_id'=>$id]);

        $abonos = $cliente->abonos;

        if(!$cliente){
            return redirect('home');
        }

        $imagenes=ClientesImagenes::where('id_cliente', $id)->get();

        return view('admin.clientes.detail', compact('cliente', 'imagenes', 'pagos', 'abonos'));
    }

    public function contract($id)
    {
        $cliente = $this->clientesRepository->find($id);

        $contratoTipos = $this->contratoTipoRepository->all();

        $contratoCategorias = $this->contratoCategoriasRepository->all();

        $asesores = $this->userRepository->getAsesores();

        $productos = $this->productRepository->searchBy(['estatus'=>'1']);

        $contratosGrupos = $this->contratosGruposRepository->all();

        return view('admin.clientes.contract', compact(
            'cliente',
            'contratoTipos',
            'contratoCategorias',
            'productos',
            'contratosGrupos',
            'asesores'
        ));
    }

    public function postcontract($id, Request $request)
    {

        $data = $request->all();

        $cliente = $this->clientesRepository->find($id);

        $costo = intval($data['monto']*0.08);

        $data['cliente_id']= $cliente->id;

        $inscripcion = $this->contratoRepository->searchOneBy([
            'inscripcion'=>$data['inscripcion']
        ]);

        if($inscripcion){
            return redirect('admin/clientes/'.$id.'/contract')
            ->with('message', 'Numero de contrato ya fue usado');
        }

        $contrato = $this->contratoRepository->create($data);

        return redirect('admin/clientes/'.$id.'/detail');

    }

    public function getClienteDetalle($id){
        $cliente = $this->clientesRepository->find($id);
        return view('admin.clientes.detalle-cliente', compact('cliente'));
    }


    public function uploadimg(Request $request, $id){

        $imagen='default.png';

        $picture = "";
        
        if ($request->hasFile('myfile')) {

            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/clientes/'.$id.'/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

        }

        $data = array(
          'id_cliente' => $id,
          'imagen' => $imagen,
          'order' => 0,
          'title' => ' ',
          'alt' => ' ',
          'user_id' => Auth::getUser()->id
        );

        ClientesImagenes::create($data);

        $imagenes=ClientesImagenes::where('id_cliente', $id)->get();

        $view= View::make('admin.clientes.imagenes', compact('imagenes'));

        $data=$view->render();

        return $data;
    }


     public function delimagenes(Request $request){


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('AlpProductosController/delimagenes');

        }else{

          activity()->withProperties($request->all())->log('AlpProductosController/delimagenes');

        }

        $imagen=AlpProductosImagenes::where('id', $request->id)->first();

        $id_producto=$imagen->id_producto;

        $imagen->delete();

        $imagenes=AlpProductosImagenes::where('id_producto', $id_producto)->get();


        $view= View::make('admin.productos.imagenes', compact('imagenes'));

      $data=$view->render();

        return $data;
    }

    public function updatePhoto(Request $request)
    {

        $validatedData = $request->validate([
            'id_cliente' => 'required',
            'myfile' => 'required',
        ]);

        $data = $request->all();


        $cliente = $this->clientesRepository->find($data['id_cliente']);

        try {

            $archivo = null;

            if ($request->hasFile('myfile')) {
    
                $file = $request->file('myfile');
                $extension = $file->extension()?: 'png';
                $picture = Str::random(10) . '.' . $extension;
                $destinationPath = public_path('/uploads/clientes/'.$cliente->id.'/');
                $file->move($destinationPath, $picture);
                $archivo = $picture;

            }

            $data = [];

            $data['archivo']= $archivo;

            $cliente = $this->clientesRepository->update($cliente->id, $data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

            return redirect('admin/clientes/'.$cliente->id.'/detail');
        
    }





}
