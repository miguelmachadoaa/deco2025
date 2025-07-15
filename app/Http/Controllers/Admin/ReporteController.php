<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Repositories\DolarRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\UserRepository;
use App\Repositories\PagosRepository;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\AbonoRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientesRepository;
use App\Repositories\ContratosGruposRepository;



use App\Repositories\ExportCsvRepository;
use App\Repositories\ExportPdfRepository;

use App\Mappers\ContratosMapper;



use Auth;
use Illuminate\Support\Str;
use View;
use Datetime;
use App\Custom\fpdf\fpdf as fpdf;


class ReporteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly DolarRepository $dolarRepository,
        private readonly ContratoRepository $contratoRepository,
        private readonly ContratosGruposRepository $contratosGruposRepository,
        private readonly UserRepository $userRepository,
        private readonly PagosRepository $pagosRepository,
        private readonly ContratoDetallesRepository $contratoDetallesRepository,
        private readonly AbonoRepository $abonoRepository,
        private readonly ProductRepository $productRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly ClientesRepository $clientesRepository,
        private readonly ExportCsvRepository $exportCsvRepository,
        private readonly ExportPdfRepository $exportPdfRepository,
        private readonly ContratosMapper $contratosMapper,
    )
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contratos()
    {
        $dolars = $this->dolarRepository->all();

        $asesores = $this->userRepository->getAsesores();

        $productos = $this->productRepository->all();

        $categorias = $this->categoryRepository->all();

        $grupos = $this->contratosGruposRepository->all();

        return view('admin.reporte.home', compact('asesores', 'productos', 'categorias', 'grupos'));
    }

    public function exportContratos(Request $request)
    {

        $input = $request->all();

        $contratos = $this->contratoRepository->all();

        if(null!==$input['desde']){
            $contratos = $contratos->where('created_at', '>=', $input['desde'].' 00:00:00' );
        }

        if(null!==$input['hasta']){
            $contratos = $contratos->where('created_at', '<=', $input['hasta'].' 23:59:59' );
        }
 
        if(null!==$input['estatus']){
            $contratos = $contratos->where('estatus', $input['estatus'] );
        }

        if(null!==$input['producto']){
            $contratos = $contratos->where('producto_id', $input['producto'] );
        }

        if(null!==$input['grupo']){
            $contratos = $contratos->where('grupo_id', $input['grupo'] );
        }

        if(null!==$input['categoria']){
            $contratos = $contratos->map(function ($contrato) use ($input){

                $categoria = $contrato->producto->categorias->where('id', $input['categoria']);

                if(count($categoria)){
                    return $contrato;
                }

            });
        }

        $header = [
            'Nombre Cliente',
            'Apellido Cliente',
            'Email Cliente',
            'Cedula Cliente',
            'Inscripcion Contrato',
            'Rango',
            'estado de Contrato',
            'Producto',
            'Marca',
            'Observaciones',
            'Direccion',
            'Telefono',
            'Nombre Asesor',
            'Vencidas',
        ];

        $data = $this->contratosMapper->getData($contratos);

        if($input['tipo']=='csv'){

            $this->exportCsvRepository->export($header, $data);

        }else{

            $this->exportPdfRepository->contratos($header, $data);
        }

        
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function pagos()
    {
        $dolars = $this->dolarRepository->all();

        $clientes = $this->clientesRepository->all();

        return view('admin.reporte.pagos', compact('clientes'));
    }

    public function exportPagos(Request $request)
    {

        $input = $request->all();

        $pagos = $this->pagosRepository->all();

        if(null!==$input['desde']){
            $pagos = $pagos->where('created_at', '>=', $input['desde'].' 00:00:00' );
        }

        if(null!==$input['hasta']){
            $pagos = $pagos->where('created_at', '<=', $input['hasta'].' 23:59:59' );
        }
 
        if(null!==$input['cliente']){
            $pagos = $pagos->where('cliente_id', $input['cliente'] );
        }

        $header = [
            'Nro de pago',
            'Monto de Pago',
            'Tipo de Pago',
            'Id de tipo de pago',
            'Nombre Cliente',
            'Apellido Cliente',
            'Email Cliente',
        ];

        $data = [];

        foreach($pagos as $pago){

            $cliente = $this->clientesRepository->find($pago->cliente->id);

            if($pago->type=='cuota'){
                $elemento = $this->contratoDetallesRepository->find($pago->type_id)->cuota;
            }else{
                $elemento = $pago->type_id;
            }

            $data[]=[
                'id'=>$pago->id,
                'monto'=>$pago->monto,
                'tipo'=>$pago->type,
                'tipo_id'=>$elemento,
                'nombre'=>$pago->cliente->nombre,
                'apellido'=>$pago->cliente->apellido,
                'email'=>$pago->cliente->email,
                'telefono'=>$pago->cliente->telefono,
                'inscripcion'=>$cliente->contratos->first()->inscripcion,
                'rango'=>$cliente->contratos->first()->monto,
                'cuota'=>$cliente->contratos->first()->detalles->first()->monto,

            ];
           
        }


        if($input['tipo']=='csv'){

            $this->exportCsvRepository->export($header, $data);

        }else{

            $this->exportPdfRepository->pagos($header, $data);
        }

        
    }


     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function abonos()
    {

        $clientes = $this->clientesRepository->all();

        return view('admin.reporte.abonos', compact('clientes'));
    }

    public function exportAbonos(Request $request)
    {

        $input = $request->all();

        $abonos = $this->abonoRepository->all();

        if(null!==$input['desde']){
            $abonos = $abonos->where('created_at', '>=', $input['desde'].' 00:00:00' );
        }

        if(null!==$input['hasta']){
            $abonos = $abonos->where('created_at', '<=', $input['hasta'].' 23:59:59' );
        }
 
        if(null!==$input['cliente']){
            $abonos = $abonos->where('cliente_id', $input['cliente'] );
        }

        

        $header = [
            'Nro de Abono',
            'Monto de Abono',
            'Monto de Abono Bs',
            'Tasa',
            'Fecha de Abono',
            'Forma de Pago',
            'Referencia',
            'Estado del Pago',
            'Nombre Cliente',
            'Apellido Cliente',
            'Email Cliente',
        ];

        $data = [];

        foreach($abonos as $abono){

            $cliente = $this->clientesRepository->find($abono->cliente->id);

            $estado = null;

            if($abono->estatus==0){$estado = 'Espera de Revision';}
            if($abono->estatus==1){$estado = 'Aprobado';}
            if($abono->estatus==2){$estado = 'Rechazado';}

            $data[]=[
                'id'=>$abono->id,
                'monto'=>$abono->monto,
                'monto_bs'=>$abono->monto_bs,
                'tasa'=>$abono->tasa,
                'fecha'=>$abono->fecha,
                'forma_pago'=>$abono->formapago->nombre,
                'referencia'=>$abono->referencia,
                'estado'=>$estado,
                'nombre'=>$abono->cliente->nombre,
                'apellido'=>$abono->cliente->apellido,
                'email'=>$abono->cliente->email,
                'inscripcion'=>$cliente->contratos->first()->inscripcion,
                'rango'=>$cliente->contratos->first()->monto,
                'cuota'=>$cliente->contratos->first()->detalles->first()->monto,
            ];
           
        }

        if($input['tipo']=='csv'){

            $this->exportCsvRepository->export($header, $data);

        }else{

            $this->exportPdfRepository->abonos($header, $data);
        }
        
    }


    public function clientes()
    {

        $clientes = $this->userRepository->searchBy(['rol'=>4   ]);

        return view('admin.reporte.clientes', compact('clientes'));
    }

    public function exportClientes(Request $request)
    {

        $input = $request->all();

        $titulo = 'Reporte de Clientes';

       # dd($input);

        if(null!==$input['tipo_cliente']){
            if($input['tipo_cliente']=='morosos'){
                $clientes = $this->clientesRepository->morosos();
                $titulo = 'Reporte de Clientes Morosos';
           }else{
                 $clientes = $this->clientesRepository->all();
                 $titulo = 'Reporte de  Clientes';
           }
        }

        if(null!==$input['desde']){
            $clientes = $clientes->where('created_at', '>=', $input['desde'].' 00:00:00' );

            $titulo = $titulo.' Desde: '.$input['desde'];
        }

        if(null!==$input['hasta']){
            $clientes = $clientes->where('created_at', '<=', $input['hasta'].' 23:59:59' );
            $titulo = $titulo.' Hasta: '.$input['hasta'];
        }
 
        $header = [
            'Nombre Cliente',
            'Apellido Cliente',
            'Email Cliente',
            'Cedula Cliente',
            'Inscripcion Contrato',
            'Rango',
            'estado de Contrato',
            'Producto',
            'Marca',
            'Observaciones',
            'Direccion',
            'Telefono',
            'Nombre Asesor',
            'Vencidas',
            'Estado Cliente',
        ];
        $data = [];

        foreach($clientes as $cliente){

            $estado = null;

            $contrato = $this->contratoRepository->searchOneBy(['cliente_id'=>$cliente->id]);
            $pago = $this->pagosRepository->searchBy(['cliente_id'=>$cliente->id]);

            $ultimo_pago = $pago->sortByDesc('fecha')->first()?->fecha;

            if($contrato === null){
                continue;
            }


            $pagadas = $contrato->detalles->where('estatus', '1')->where('color', 'orange')->count();

            $data[]=[
                'nombre'=>$contrato->cliente->nombre,
                'apellido'=>$contrato->cliente->apellido,
                'email'=>$contrato->cliente->email,
                'cedula'=>$contrato->cliente->tipo_documento.'-'.$contrato->cliente->documento,
                'inscripcion'=>$contrato->inscripcion,
                'monto'=>$contrato->monto,
                'estatus'=>$contrato->estatus,
                'titulo'=>$contrato->producto->titulo,
                'marca'=>$contrato->producto->marca,
                'descripcion'=>$contrato->descripcion,
                'direccion'=>$contrato->cliente->direcciones->first()?->Completa,
                'telefono'=>$contrato->cliente->direcciones->first()?->telefono,
                'name'=>$contrato->vendedor->name,
                'vencidas'=>$contrato->detalles->where('estatus', '2')->count(),
                'progresivas'=>$contrato->detalles->where('estatus', '1')->where('color', '<>', 'orange')->count(),
                'regresivas'=>$contrato->detalles->where('estatus', '1')->where('color', 'orange')->count(),
                'activacion'=>$contrato->detalles->where('cuota', '1')->first()->fecha,
                'ultimo'=>$ultimo_pago,
                'estado'=>($contrato->detalles->where('estatus', '2')->count())?'Moroso':'Normal',
            ];
           
        }

        if($input['tipo']=='csv'){

            $this->exportCsvRepository->export($header, $data);

        }else{

            $this->exportPdfRepository->contratos($header, $data, $titulo);
        }
        
    }


    public function diario()
    {

        $clientes = $this->userRepository->searchBy(['rol'=>4   ]);

        return view('admin.reporte.diario', compact('clientes'));
    }

    public function exportDiario(Request $request)
    {

        $input = $request->all();

        $abonos = $this->abonoRepository->all();
        $pagos = $this->pagosRepository->all();
        $contratos = $this->contratoRepository->all();


        if(null!==$input['desde']){
            $abonos = $abonos->where('created_at', '>=', $input['desde'].' 00:00:00' );
            $pagos = $pagos->where('created_at', '>=', $input['desde'].' 00:00:00' );
            $contratos = $contratos->where('created_at', '>=', $input['desde'].' 00:00:00' );
        }

        if(null!==$input['hasta']){
            $abonos = $abonos->where('created_at', '<=', $input['hasta'].' 23:59:59' );
            $pagos = $pagos->where('created_at', '<=', $input['hasta'].' 23:59:59' );
            $contratos = $contratos->where('created_at', '<=', $input['hasta'].' 23:59:59' );
        }
 
        $header = [
            'Nro Recibo',
            'Nombre y apellido',
            'Email Cliente',
            'Cedula Cliente',
            'Direccion',
            'Telefono',
            'Inscripcion Contrato',
            'Rango',
            'cuota',
            'Producto',
            'Marca',
            'Detalle',
            'Monto',
            'Forma de pago',
            'Tasa del Dia',
            'Referencia',
            'Fecha',
        ];

        $data = [];
        $sumaAbonos = 0;

        foreach($abonos as $abono){

            $estado = null;

            $cliente = $this->clientesRepository->searchOneBy(['id'=>$abono->cliente_id]);
            $contrato = $this->contratoRepository->searchOneBy(['cliente_id'=>$cliente->id]);

            if($contrato === null){
                continue;
            }

            $data[]=[
                'recibo'=>$abono->id,
                'nombre'=>$contrato->cliente->nombre. ' '.$contrato->cliente->apellido,
                'email'=>$contrato->cliente->email,
                'cedula'=>$contrato->cliente->tipo_documento.'-'.$contrato->cliente->documento,
                'direccion'=>$contrato->cliente->direcciones->first()->Completa,
                'telefono'=>$contrato->cliente->direcciones->first()->telefono,
                'inscripcion'=>$contrato->inscripcion,
                'contrato'=>$contrato->monto,
                'cuota'=>$contrato->detalles->first()->monto,
                'titulo'=>$contrato->producto->titulo,
                'marca'=>$contrato->producto->marca,
                'descripcion'=>$contrato->descripcion,
                'monto'=>$abono->monto,
                'forma'=>$abono->formapago->nombre,
                'tasa'=>$abono->tasa,
                'referencia'=>$abono->referencia,
                'fecha'=>$abono->created_at->format('d/m/Y'),
            ];

            $sumaAbonos=$sumaAbonos+$abono->monto;
           
        }


        $data[]=[
            'recibo'=>'',
            'nombre'=>'',
            'email'=>'',
            'cedula'=>'',
            'direccion'=>'',
            'telefono'=>'',
            'inscripcion'=>'',
            'contrato'=>'',
            'cuota'=>'',
            'titulo'=>'',
            'marca'=>'',
            'descripcion'=>'Total Abonos',
            'monto'=>$sumaAbonos,
            'forma'=>'',
            'tasa'=>'',
            'referencia'=>'',
            'fecha'=>'',
        ];



        $dataPagos = [];

        $sumaPagos = 0;
        
        foreach($pagos as $pago){

            $estado = null;

            $cliente = $this->clientesRepository->searchOneBy(['id'=>$pago->cliente_id]);
            $contrato = $this->contratoRepository->searchOneBy(['cliente_id'=>$cliente->id]);

            if($contrato === null){
                continue;
            }

            $dataPagos[]=[
                'recibo'=>$pago->id,
                'nombre'=>$contrato->cliente->nombre. ' '.$contrato->cliente->apellido,
                'email'=>$contrato->cliente->email,
                'cedula'=>$contrato->cliente->tipo_documento.'-'.$contrato->cliente->documento,
                'direccion'=>$contrato->cliente->direcciones->first()->Completa,
                'telefono'=>$contrato->cliente->direcciones->first()->telefono,
                'inscripcion'=>$contrato->inscripcion,
                'contrato'=>$contrato->monto,
                'cuota'=>$contrato->detalles->first()->monto,
                'titulo'=>$contrato->producto->titulo,
                'marca'=>$contrato->producto->marca,
                'descripcion'=>$contrato->descripcion,
                'monto'=>$pago->monto,
                'forma'=>'N/A',
                'tasa'=>'N/A',
                'referencia'=>$pago->observaciones,
                'fecha'=>$pago->created_at->format('d/m/Y'),
            ];

            $sumaPagos=$sumaPagos+$pago->monto;
           
        }

        $dataPagos[]=[
            'recibo'=>'',
            'nombre'=>'',
            'email'=>'',
            'cedula'=>'',
            'direccion'=>'',
            'telefono'=>'',
            'inscripcion'=>'',
            'contrato'=>'',
            'cuota'=>'',
            'titulo'=>'',
            'marca'=>'',
            'descripcion'=>'Total Pagos',
            'monto'=>$sumaPagos,
            'forma'=>'',
            'tasa'=>'',
            'referencia'=>'',
            'fecha'=>'',
        ];

        $dataContratos=[];

        foreach($contratos as $contrato){

            $estado = null;

            $cliente = $this->clientesRepository->searchOneBy(['id'=>$contrato->cliente_id]);

            if($contrato === null){
                continue;
            }

            $dataContratos[]=[
                'recibo'=>$contrato->id,
                'nombre'=>$contrato->cliente->nombre. ' '.$contrato->cliente->apellido,
                'email'=>$contrato->cliente->email,
                'cedula'=>$contrato->cliente->tipo_documento.'-'.$contrato->cliente->documento,
                'direccion'=>$contrato->cliente->direcciones->first()->Completa,
                'telefono'=>$contrato->cliente->direcciones->first()->telefono,
                'inscripcion'=>$contrato->inscripcion,
                'contrato'=>$contrato->monto,
                'cuota'=>$contrato->detalles->first()->monto,
                'titulo'=>$contrato->producto->titulo,
                'marca'=>$contrato->producto->marca,
                'descripcion'=>$contrato->descripcion,
                'monto'=>$contrato->monto,
                'forma'=>'N/A',
                'tasa'=>'N/A',
                'referencia'=>'N/A',
                'fecha'=>$contrato->created_at->format('d/m/Y'),
            ];
           
        }


        if($input['tipo']=='csv'){

            header("Content-Type: text/csv; charset=utf-8");
            header("Content-Disposition: attachment; filename=reporte_diario_".date('d_m_Y').".csv");
            $output = fopen('php://output', 'w');
    
            fputcsv($output, ['Abonos'], ';');
            fputcsv($output, [' '], ';');
    
    
            fputcsv($output, $header, ';');
            
            foreach ($data as $fields) {
                if(is_array($fields)){
                    fputcsv($output, $fields, ';');
                }
            }
            fputcsv($output, [' '], ';');
    
            fputcsv($output, ['Pagos'], ';');
            fputcsv($output, [' '], ';');
            fputcsv($output, $header, ';');
    
            foreach ($dataPagos as $fields) {
                if(is_array($fields)){
                    fputcsv($output, $fields, ';');
                }
            }
    
            fputcsv($output, [' '], ';');
    
            fputcsv($output, ['Contratos'], ';');
            fputcsv($output, [' '], ';');
            fputcsv($output, $header, ';');
    
            foreach ($dataContratos as $fields) {
                if(is_array($fields)){
                    fputcsv($output, $fields, ';');
                }
            }
    
      
            die();
    
            return 'true';

        }else{

            $this->exportPdfRepository->diario($header, $data, $dataPagos, $dataContratos, 'Reporte diario');
        }

       
        
    }




    

}
