<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\PagosRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\SaldoRepository;
use App\Repositories\ClientesRepository;
use App\Repositories\EmailRepository;
use Auth;
use Illuminate\Support\Str;
use View;
use Datetime;
use Carbon\Carbon;
use App\Custom\fpdf\fpdf as fpdf;
use App\Exceptions\MensajeException;


class PagosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly PagosRepository $pagosRepository,
        private readonly ContratoRepository $contratoRepository,
        private readonly ContratoDetallesRepository $contratoDetallesRepository,
        private readonly SaldoRepository $saldoRepository,
        private readonly ClientesRepository $clientesRepository,
        private readonly EmailRepository $emailRepository,
    )
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function cuota(Request $request)
    {
        $last = $request->last;
        $detalle = $this->contratoDetallesRepository->find($request->id);
        $contrato = $this->contratoRepository->find($detalle->contrato_id);

        return view('admin.pagos.cuota', compact('detalle', 'contrato', 'last'));
    }


    public function procesar(Request $request)
    {
        $last = $request->last;

        $fecha_pago = $request->fecha_pago;
        
        $detalle = $this->contratoDetallesRepository->find($request->id);

        $this->procesarCuotas($last, $fecha_pago, $detalle);

        $contrato = $this->contratoRepository->find($detalle->contrato_id);

        $cliente = $this->clientesRepository->find($contrato->cliente_id);

        return view('admin.clientes.contrato', compact('detalle', 'contrato', 'cliente'));
    }

    private function procesarCuotas($last, $fecha_pago, $detalle, $parcial=false){

        $contrato = $this->contratoRepository->find($detalle->contrato_id);

        $cliente = $this->clientesRepository->find($contrato->cliente_id);

        if($cliente->saldo_cliente>=$detalle->saldo){

            $color = 'green';

            if($detalle->estatus == 0){
                
                if($last){

                    $color = 'orange';
                }

            }else{

                $color = 'warning';
            }

            $this->contratoDetallesRepository->update(
                $detalle->id, [
                    'estatus'=>1,
                    'color'=>$color
                ]
            );

            $saldo = $this->saldoRepository->create([
                'cliente_id'=>$contrato->cliente->id,
                'fecha'=>$fecha_pago,
                'monto'=>$detalle->saldo,
                'tipo'=>2,
                'observaciones'=>'Pago de cuota #'.$detalle->cuota,
            ]);

            if($contrato->estatus=='desactivado'){
                $this->contratoRepository->update($contrato->id, [
                    'estatus'=>'activo'
                ]);
            }
            
            $pago = $this->pagosRepository->create([
                'cliente_id'=>$contrato->cliente->id,
                'cuota_id'=>$detalle->id,
                'type_id'=>$detalle->id,
                'type'=>'cuota',
                'fecha'=>$fecha_pago,
                'monto'=>$detalle->saldo,
                'observaciones'=>'Pago de cuota #'.$detalle->cuota
            ]);

            if($detalle->cuota == 1 ){

                $fecha_actual = Carbon::createFromFormat('Y-m-d', $fecha_pago);

                foreach($contrato->detalles  as $detalle){

                    if($detalle->cuota != 1){
                        
                        $this->contratoDetallesRepository->update($detalle->id, [
                            'fecha'=>$fecha_actual->addMonth()->format('Y-m-d')
                        ]);
                        
                    }else{

                        $this->contratoDetallesRepository->update($detalle->id, [
                            'fecha'=>$fecha_actual->format('Y-m-d')
                        ]);
                    }

                }

                $this->emailRepository->welcome($contrato);
                
            }

            $cuotaVencida= $contrato->detalles()->where('estatus', 2)->first();

            if(isset($cuotaVencida->id)){

                //el ultimo parametro se usa para pagos parciales en 1.

                $this->procesarCuotas(1, $fecha_pago, $cuotaVencida, 0);

            }else{

                $ultimaCuotaEnEspera = $contrato->detalles()->where('estatus', 0)->orderBy('id', 'desc')->first();

                if(isset($ultimaCuotaEnEspera->id)){

                    //el ultimo parametro se usa para pagos parciales en 1.

                    $this->procesarCuotas(1, $fecha_pago, $ultimaCuotaEnEspera, 0);

                }

            }

        }else{
            
            if($parcial){

                if($cliente->saldo_cliente > 0){

                    $saldo_disponible = $cliente->saldo_cliente;

                    $saldo = $this->saldoRepository->create([
                        'cliente_id'=>$contrato->cliente->id,
                        'fecha'=>$fecha_pago,
                        'monto'=>$saldo_disponible,
                        'tipo'=>2,
                        'observaciones'=>'Pago parcial de cuota #'.$detalle->cuota,
                    ]);
        
                    
                    $pago = $this->pagosRepository->create([
                        'cliente_id'=>$contrato->cliente->id,
                        'cuota_id'=>$detalle->id,
                        'type_id'=>$detalle->id,
                        'type'=>'cuota',
                        'fecha'=>$fecha_pago,
                        'monto'=>$saldo_disponible,
                        'observaciones'=>'Pago parcial de cuota #'.$detalle->cuota
                    ]);

                }

            }

        }

    }


    public function index()
    {
        $pagos = $this->pagosRepository->all();

        return view('admin.pagos.home', compact('pagos'));
    }

    public function add()
    {
        return view('admin.pagos.add');
    }


    public function store(Request $request)
    {

        try {

            $data = $request->all();

            $fecha = new DateTime('now');

            $cliente  = $this->clientesRepository->find($data['cliente_id']);

            if($cliente->saldo_cliente>=$data['monto']){

                $pago = $this->pagosRepository->create($data);

                $saldo = $this->saldoRepository->create([
                    'cliente_id'=>$data['cliente_id'],
                    'fecha'=>$data['fecha']??$fecha,
                    'monto'=>$data['monto'],
                    'tipo'=>2,
                    'observaciones'=>$data['observaciones'],
                ]);

            }else{

                throw new MensajeException('Saldo insuficiente');
            }

            

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }


       $pagos = $this->pagosRepository->all();

       return view('admin.pagos.home', compact('pagos'));
        
    }

    public function detail($id)
    {
        $abono = $this->pagosRepository->find($id);

        return view('admin.pagos.detail', compact('abono'));
    }


    public function edit($id)
    {
        $contratoTipo = $this->pagosRepository->find($id);

        return view('admin.pagos.edit', compact('contratoTipo'));
    }


    public function update($id, Request $request)
    {

        $contratoCategoria = $this->pagosRepository->update($id, $request->all());

        return redirect('admin/abonos');
        
    }


    public function status(Request $request)
    {

        try {

            $data = $request->all();

        $contratoCategoria = $this->pagosRepository->update($data['id'], [
            'estatus'=>$data['estado_abono'],
            'observaciones'=>$data['observaciones']

        ]);

        } catch (\Exception $e) {

            return ['respuesta' => 'error', 'message' => $e->getMessage()];
        }

        return ['respuesta' => 'exito', 'message' => 'Abono actualizado correctamente.', 'id' => $data['id']];
        
    }

    public function delete($id)
    {



        $this->pagosRepository->delete($id);

        return redirect()->back();
        
    }


    public function archivo($id){

        $pago = $this->pagosRepository->find($id);

        if(!$pago){
            return redirect('home');
        }

        $this->fpdf = new fpdf;

        $this->fpdf->AddPage();

        $this->fpdf->SetFont('Arial', 'B');

        $this->fpdf->SetFontSize(12);

        $this->fpdf->SetFontSize(12);

        $this->fpdf->SetFillColor(255,255,255);

        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);
        $this->fpdf->cell(100,8,utf8_decode('RECIBO '), 0, 0, 'C', true);
        $this->fpdf->cell(30,8,utf8_decode('Fecha:  '), 0, 0, 'L', true);
        $this->fpdf->cell(40,8,utf8_decode(date("d/m/Y", strtotime($pago->fecha))), 0, 1, 'L', true);

        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);
        $this->fpdf->cell(100,8,utf8_decode(' Nro. '.str_pad($pago->id, 10, "0", STR_PAD_LEFT)), 0, 0, 'C', true);
        $this->fpdf->cell(30,8,utf8_decode('INSC:  '), 0, 0, 'L', true);
        $this->fpdf->cell(40,8,utf8_decode(str_pad($pago->cliente->contratos->first()->inscripcion, 10, "0", STR_PAD_LEFT)), 0, 1, 'L', true);


        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);
        $this->fpdf->cell(170,8,utf8_decode('  '), 0, 1, 'L', true);
        $this->fpdf->SetFont('Arial', 'B');
        
        $this->fpdf->cell(100,8,utf8_decode('Hemos recibido de :' ), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(45,8,utf8_decode($pago->cliente->nombre.' '.$pago->cliente->apellido ), 0, 1, 'L', true);
        $this->fpdf->SetFont('Arial', 'B');

        $this->fpdf->cell(100,8,utf8_decode('Cedula de Identidad Nro.:'), 0, 0, 'L', true);
        $this->fpdf->SetFont('Arial');
        $this->fpdf->cell(45,8,utf8_decode($pago->cliente->documento ), 0, 1, 'L', true);
        $this->fpdf->SetFont('Arial', 'B');

        if(isset($pago->detalle)){

            $this->fpdf->cell(100,8,utf8_decode('Segun el organigrama de Pago, la cuota Nro.:' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(125,8,utf8_decode($pago->detalle->cuota.' ' ), 0, 1, 'L', true);
    
            $this->fpdf->SetFont('Arial', 'B');
    
            $this->fpdf->cell(100,8,utf8_decode('Cada Cuota con el Valor de:' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(125,8,utf8_decode(number_format($pago->detalle->monto,0).'$ ' ), 0, 1, 'L', true);
            $this->fpdf->SetFont('Arial', 'B');

            if($pago->detalle->mora>0){
                $this->fpdf->cell(100,8,utf8_decode('Monto de Mora: ' ), 0, 0, 'L', true);
                $this->fpdf->SetFont('Arial');
                $this->fpdf->cell(130,8,utf8_decode(number_format($pago->detalle->mora).' $ ' ), 0, 1, 'L', true);
                $this->fpdf->SetFont('Arial');
            }
    
            $this->fpdf->SetFont('Arial', 'B');
    
            $this->fpdf->cell(100,8,utf8_decode('Monto Pagado: ' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(130,8,utf8_decode(number_format($pago->monto).' $ ' ), 0, 1, 'L', true);
            $this->fpdf->SetFont('Arial');

            $this->fpdf->SetFont('Arial', 'B');
    
            if($pago->detalle->saldo>0){
                $this->fpdf->cell(100,8,utf8_decode('Pendiente por pagar: ' ), 0, 0, 'L', true);
                $this->fpdf->SetFont('Arial');
                $this->fpdf->cell(130,8,utf8_decode(number_format($pago->detalle->saldo).' $ ' ), 0, 1, 'L', true);
                $this->fpdf->SetFont('Arial');
            }

        }


        if(isset($pago->contrato)){

            $this->fpdf->cell(100,8,utf8_decode('Segun contrato Nro.:' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(125,8,utf8_decode($pago->contrato->inscripcion.' ' ), 0, 1, 'L', true);
    
            $this->fpdf->SetFont('Arial', 'B');
    
            $this->fpdf->cell(100,8,utf8_decode('Por un  Valor de:' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(125,8,utf8_decode(number_format($pago->contrato->monto,0).'$ ' ), 0, 1, 'L', true);
    
            $this->fpdf->SetFont('Arial', 'B');
    
            $this->fpdf->cell(100,8,utf8_decode('Total: ' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(130,8,utf8_decode(number_format($pago->monto,2).' $ ' ), 0, 1, 'L', true);
            $this->fpdf->SetFont('Arial');
    
        }

        if(isset($pago->diferencia)){

            $this->fpdf->cell(100,8,utf8_decode('Segun contrato Nro.:' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(125,8,utf8_decode($pago->diferencia->inscripcion.' ' ), 0, 1, 'L', true);
            $this->fpdf->SetFont('Arial', 'B');

            $this->fpdf->cell(100,8,utf8_decode('Abono a diferencia de un monto de: ' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(125,8,utf8_decode($pago->diferencia->diferencia.'$ ' ), 0, 1, 'L', true);
    
            $this->fpdf->SetFont('Arial', 'B');
    
            $this->fpdf->cell(100,8,utf8_decode('Por un  Valor de:' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(125,8,utf8_decode(number_format($pago->monto,0).'$ ' ), 0, 1, 'L', true);
    
            $this->fpdf->SetFont('Arial', 'B');
    
            $this->fpdf->cell(100,8,utf8_decode('Total: ' ), 0, 0, 'L', true);
            $this->fpdf->SetFont('Arial');
            $this->fpdf->cell(130,8,utf8_decode(number_format($pago->monto,2).' $ ' ), 0, 1, 'L', true);
            $this->fpdf->SetFont('Arial');

        }

        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 1, 'L', true);
        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 1, 'L', true);
        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 1, 'L', true);
        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 1, 'L', true);
        $this->fpdf->cell(10,8,utf8_decode( '' ), 0, 0, 'L', true);
        $this->fpdf->cell(75,8,utf8_decode('Entregado por'), 'T', 0, 'C', true);

        $this->fpdf->cell(10,8,utf8_decode(' ' ), 0, 0, 'L', true);
        $this->fpdf->cell(75,8,utf8_decode('Recibido por' ), 'T', 1, 'C', true);


        $this->fpdf->cell(170,8,utf8_decode(' Av. Bolivar Con Calle Sucre - C.C. Gran Bazar - Pb. Local Nro.14 - Maracay estado Aragua '), 0, 1, 'L', true);

        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);
        $this->fpdf->cell(170,8,utf8_decode('  '), 0, 1, 'L', true);

        $this->fpdf->cell(20,8,' ', 0, 0, 'L', true);

        $this->fpdf->Image(url('/assets/images/logo.png') , 10,0,0,40 );


        $this->fpdf->Output('I', 'abono.pdf');

        exit;


    }
    

}
