<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\ClientesRepository;
use App\Repositories\ContractoTipoRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ContractoCategoriaRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratosGruposRepository;

use Auth;
use App\Models\Clientes;
use App\Models\ClientesReferencias;
use Illuminate\Support\Str;
use View;
use App\Custom\fpdf\fpdf as fpdf;

class ContratosController extends Controller
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
        private readonly ContratoDetallesRepository $contratoDetallesRepository,
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
        $contratos = $this->contratoRepository->all()->toArray();

        return view('admin.contratos.home', compact('contratos'));
    }

    public function inactivos()
    {
        $contratos = $this->contratoRepository->searchBy(['estatus'=>'desactivado'])->toArray();

        return view('admin.contratos.home', compact('contratos'));
    }

    public function activos()
    {
        $contratos = $this->contratoRepository->searchBy(['estatus'=>'activo'])->toArray();

        return view('admin.contratos.home', compact('contratos'));
    }

    public function compras()
    {
        $contratos = $this->contratoRepository->searchBy(['estatus'=>'lista_compra'])->toArray();

        return view('admin.contratos.home', compact('contratos'));
    }

    public function adjudicados()
    {
        $contratos = $this->contratoRepository->searchBy(['estatus'=>'adjudicado'])->toArray();

        return view('admin.contratos.home', compact('contratos'));
    }

    public function retirados()
    {
        $contratos = $this->contratoRepository->searchBy(['estatus'=>'retirado'])->toArray();

        return view('admin.contratos.home', compact('contratos'));
    }

    public function add()
    {
        return view('admin.contratos.add');
    }

    public function store(Request $request)
    {

        $data = $request->all();

        try {

            $client = $this->contratoRepository->create($data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        return ['respuesta' => 'exito', 'message' => 'Contrato creado correctamente', 'id' => $client->id];
    }

    public function edit($id)
    {
        $contrato = $this->contratoRepository->find($id);

        $cliente = $this->clientesRepository->find($contrato->cliente_id);

        $contratoTipos= $this->contratoTipoRepository->all();

        $contratoCategorias = $this->contratoCategoriasRepository->all();

        $asesores = $this->userRepository->getAsesores();

        $productos = $this->productRepository->all();

        $contratosGrupos = $this->contratosGruposRepository->all();

        return view('admin.contratos.edit', compact(
            'cliente',
            'contratoTipos',
            'contratoCategorias',
            'productos',
            'asesores',
            'contratosGrupos',
            'contrato'
        ));
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();

        if(isset($data['_token'])){ unset($data['_token']);} 
        if(isset($data['fecha_creacion'])){ unset($data['fecha_creacion']);} 

        $data['id']=$id;


        



        try {

            $client = $this->contratoRepository->update($data['id'], $data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        foreach($data as $key => $value){

            if(substr($key,0,5)=='cuota'){

                $partes = explode('_', $key);

                $this->contratoDetallesRepository->update($partes[1],
                [
                    'monto'=>$data['monto_'.$partes[1]],
                    'mora'=>$data['mora_'.$partes[1]]
                ]
                );

            }

        }

        return redirect(route('admin.contratos.index'));
    }
    

    public function actualizar(Request $request)
    {

        $data = $request->all();

        try {

            $client = $this->contratoRepository->update($data['id'], $data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        return ['respuesta' => 'exito', 'message' => 'Contrato actualizado correctamente', 'id' => $client->id];
    }

    public function detail($id)
    {
        $cliente = $this->contratoRepository->find($id);

        return view('admin.contratos.detail', compact('cliente'));
    }

    public function estatus(Request $request)
    {
        try {
            $this->contratoRepository->update($request->id, [
                'estatus'=>$request->estatus
            ]);

        } catch (\Throwable $th) {

           return false;

        }
       
       return true;
    }

    public function archivo($id){

        $contrato = $this->contratoRepository->find($id);

        $this->fpdf = new fpdf;

        $this->fpdf->AddPage();

        $this->fpdf->SetFont('Arial', 'B');

        $this->fpdf->SetFontSize(12);

        $this->fpdf->SetFontSize(12);

        $this->fpdf->SetFillColor(255,255,255);

        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);
        $this->fpdf->cell(100,8,utf8_decode(' Solicitud de Inscripcion'), 0, 0, 'C', true);
        $this->fpdf->cell(30,8,utf8_decode(' Fecha:  '), 0, 0, 'L', true);
        $this->fpdf->cell(40,8,utf8_decode(date('d/m/Y')), 0, 1, 'L', true);

        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);
        $this->fpdf->cell(100,8,utf8_decode(' '), 0, 0, 'C', true);
        $this->fpdf->cell(30,8,utf8_decode(' INSC:  '), 0, 0, 'L', true);
        $this->fpdf->cell(40,8,utf8_decode(str_pad($contrato->id, 10, "0", STR_PAD_LEFT)), 0, 1, 'L', true);



        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);
        $this->fpdf->cell(170,8,utf8_decode('  '), 0, 1, 'L', true);
        $this->fpdf->SetFont('Arial', 'B');

        $this->fpdf->cell(50,8,utf8_decode('Sres. Migtours VENEZUELA' ), 0, 1, 'L', true);
        $this->fpdf->cell(170,8,utf8_decode('  '), 0, 1, 'L', true);
        $this->fpdf->cell(170,8,utf8_decode('  '), 0, 1, 'L', true);

        
        $this->fpdf->cell(90 ,8,utf8_decode('Hemos registrado un contrato a nombre de:' ), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(45,8,utf8_decode($contrato->cliente->nombre.' '.$contrato->cliente->apellido ), 0, 1, 'L', true);
        $this->fpdf->SetFont('Arial', 'B');

        $this->fpdf->cell(60,8,utf8_decode('Cedula de Identidad Nro.: '), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(45,8,utf8_decode($contrato->cliente->documento ), 0, 1, 'L', true);
        $this->fpdf->SetFont('Arial', 'B');

        $this->fpdf->cell(50,8,utf8_decode('Detalles del Contrato' ), 0, 1, 'L', true);

        $this->fpdf->cell(60,8,utf8_decode('Monto del Contrato:' ), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(125,8,utf8_decode($contrato->monto.' $ ' ), 0, 1, 'L', true);
        $this->fpdf->SetFont('Arial', 'B');
        $this->fpdf->cell(60,8,utf8_decode('Producto:' ), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(125,8,utf8_decode($contrato->producto->titulo .'  ' ), 0, 1, 'L', true);

     
        $this->fpdf->SetFont('Arial', 'B');
        $this->fpdf->cell(60,8,utf8_decode('Marca:' ), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(125,8,utf8_decode($contrato->producto->marca .'  ' ), 0, 1, 'L', true);

        $this->fpdf->SetFont('Arial', 'B');
        $this->fpdf->cell(60,8,utf8_decode('Cantidad de Cuotas:' ), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(125,8,utf8_decode(count($contrato->detalles) .'  ' ), 0, 1, 'L', true);

        $this->fpdf->SetFont('Arial', 'B');
        $this->fpdf->cell(60,8,utf8_decode('Monto de Cada Cuota:' ), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(125,8,utf8_decode($contrato->detalles()->first()->monto .'$' ), 0, 1, 'L', true);

        $this->fpdf->SetFont('Arial', 'B');
        $this->fpdf->cell(60,8,utf8_decode('Estado del Contrato:' ), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(125,8,utf8_decode($contrato->estatus .'  ' ), 0, 1, 'L', true);

        $this->fpdf->SetFont('Arial', 'B');

        if($contrato->observaciones){
            $this->fpdf->cell(40,8,utf8_decode('Observaciones: ' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
    
            $this->fpdf->cell(130,8,utf8_decode($contrato->observaciones ), 0, 1, 'L', true);
        }
        
        $this->fpdf->SetFont('Arial');

        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 1, 'L', true);
        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 1, 'L', true);
        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 0, 'L', true);
        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 1, 'L', true);

        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 0, 'C', true);
        $this->fpdf->cell(75,8,utf8_decode($contrato->usuario->name), 0, 0, 'C', true);

        $this->fpdf->cell(10,8,utf8_decode(' ' ), 0, 0, 'L', true);
        $this->fpdf->cell(75,8,utf8_decode($contrato->cliente->nombre.' '.$contrato->cliente->apellido ), 0, 1, 'C', true);


        $this->fpdf->SetFont('Arial', 'B');

        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 0, 'L', true);
        $this->fpdf->cell(75,8,utf8_decode('Registrado por:'), 0, 0, 'C', true);

        $this->fpdf->cell(10,8,utf8_decode(' ' ), 0, 0, 'L', true);
        $this->fpdf->cell(75,8,utf8_decode('Recibido por:' ), 0, 1, 'C', true);

        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 1, 'L', true);


        $this->fpdf->SetFontSize(10);
        $this->fpdf->cell(170,8,utf8_decode('Av. Bolivar Con Calle Sucre - C.C. Gran Bazar - Pb. Local Nro.14 - Maracay estado Aragua '), 0, 1, 'C', true);

        $this->fpdf->SetFont('Arial');

        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);
        $this->fpdf->cell(170,8,utf8_decode('  '), 0, 1, 'L', true);

        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);

        $this->fpdf->Image(url('/assets/images/logo.png') , 10,0,0,40 );


        $this->fpdf->Output('I', 'abono.pdf');

        exit;


    }

    

}
