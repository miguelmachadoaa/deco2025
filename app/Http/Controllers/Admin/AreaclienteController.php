<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\ClientesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\AbonoRepository;
use App\Repositories\PagosRepository;
use App\Repositories\DolarRepository;
use App\Repositories\FormasPagoRepository;
use App\Models\ClientesImagenes;
use Auth;
use Str;

class AreaclienteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly ClientesRepository $clientesRepository,
        private readonly ContratoRepository $contratoRepository,
        private readonly AbonoRepository $abonoRepository,
        private readonly PagosRepository $pagosRepository,
        private readonly DolarRepository $dolarRepository,
        private readonly FormasPagoRepository $formasPagoRepository,
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

        $cliente = $this->clientesRepository->searchOneBy(['usuario_relacionado'=>Auth::user()->id]);

        $contratos = $this->contratoRepository->searchBy(['cliente_id'=>$cliente->id]);

        $dolar = $this->dolarRepository->last();

        $imagenes=ClientesImagenes::where('id_cliente', $cliente->id)->get();

        $abonos = $cliente->abonos;
        $pagos = $cliente->pagos;

        return view('admin.areacliente.home', compact(
            'contratos',
            'cliente',
            'abonos',
            'pagos',
            'dolar',
            'imagenes'
        ));
    }

    public function addabono()
    {
        $cliente = $this->clientesRepository->searchOneBy(['usuario_relacionado'=>Auth::user()->id]);

        $id_cliente = $cliente->id;

        $formaspago = $this->formasPagoRepository->all();

        return view('admin.areacliente.addabono', compact('id_cliente', 'formaspago'));
    }


    public function storeabono(Request $request)
    {

        $validatedData = $request->validate([
            'fecha' => 'required',
            'forma_pago' => 'required',
            'referencia' => 'required|min:4',
            'monto' => 'required',
            'myfile' => 'required',
        ]);

        try {

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

            $abono = $this->abonoRepository->create($data);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        $abonos = $this->abonoRepository->all();

        return redirect('admin/areacliente');
        
    }

   


    
}
