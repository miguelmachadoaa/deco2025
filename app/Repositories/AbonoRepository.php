<?php

namespace App\Repositories;

use App\Models\Abono;
use App\Exceptions\FormularioException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SaldoRepository;
use App\Repositories\NotificacionRepository;
use App\Repositories\AuditoriaRepository;
use App\Repositories\DolarRepository;
use App\Repositories\FormasPagoRepository;
use App\Repositories\EmailRepository;


final class AbonoRepository{

    public function __construct(
        private readonly Abono $model, 
        private readonly SaldoRepository $saldoRepository,
        private readonly NotificacionRepository $notificacionRepository,
        private readonly AuditoriaRepository $auditoriaRepository,
        private readonly DolarRepository $dolarRepository,
        private readonly FormasPagoRepository $formasPagoRepository,
        private readonly EmailRepository $emailRepository,
        )
    {
    }

    public function validar(array $data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                if ($key == 'observaciones') {
                    continue;
                }
                throw new FormularioException($key);
            }
        }
    }

    public function find($id)
    {
        return $this->model->with('cliente', 'actualizado', 'formapago')->find($id);
    }

    public function create(array $data)
    {

        $this->validar($data);

        $dolar = $this->dolarRepository->last();
        $formapago = $this->formasPagoRepository->find($data['forma_pago']);

        $monto_bs = 0;
        $tasa = $dolar->valor;
        $monto = $data['monto'];

        if($formapago->moneda == 'VES'){

            $monto_bs = $data['monto'];
            $monto = $data['monto']/$dolar->valor;
        }

        $abono =  $this->model->create([
            'cliente_id'=>$data['id_cliente'],
            'fecha'=>$data['fecha'],
            'forma_pago'=>$data['forma_pago'],
            'referencia'=>$data['referencia'],
            'monto'=>$monto,
            'monto_bs'=>$monto_bs,
            'tasa'=>$tasa,
            'archivo'=>$data['archivo'],
            'estatus'=>0,
            'creado_por'=>Auth::user()->id

        ]);

        $this->notificacionRepository->create([
            'type_id'=>$abono->id,
            'type'=>'abono',
            'title'=>'Se ha creado un abono',
            'body'=>'Se ha creado un abono'
        ]);

        $this->auditoriaRepository->create([
            'type_id'=>$abono->id,
            'type'=>'abono',
            'accion'=>'Se ha creado un abono',
            'cliente_id'=>$data['id_cliente'],
            'data'=>json_encode($data, true)
        ]);

        $this->emailRepository->abono($this->find($abono->id));

        return $abono;


    }

    public function update($id, array $data)
    {
        $this->validar($data);
        
        $abono = $this->model->findOrFail($id);

        $dolar = $this->dolarRepository->last();

        if(isset($data['forma_pago'])){
            
            $formapago = $this->formasPagoRepository->find($data['forma_pago']);

            $monto_bs = 0;
            $tasa = $dolar->valor;
            $monto = $data['monto'];
    
            if($formapago->moneda == 'VES'){
    
                $data['monto_bs'] = $data['monto'];
                $data['monto'] = $data['monto']/$dolar->valor;
                $data['tasa'] = $dolar->valor;
            }
    
        }
       
        $data['actualizado_por']=Auth::user()->id;

        $abono->update($data);

        if(isset($data['estatus']) && $data['estatus']==1){
            $this->saldoRepository->create([
                'cliente_id'=>$abono->cliente_id,
                'fecha'=>$abono->fecha,
                'monto'=>$abono->monto,
                'tipo'=>1,
                'observaciones'=>"Saldo creado a partir de la aprobacion del abono".$abono->id,
                'estatus'=>1,
                'creado_por'=>Auth::user()->id
            ]);
        }

        $this->auditoriaRepository->create([
            'type_id'=>$abono->id,
            'type'=>'abono',
            'accion'=>'Se ha actualizado un abono',
            'cliente_id'=>$abono->cliente_id,
            'data'=>json_encode($data, true)
        ]);

        $this->emailRepository->abono($this->find($abono->id));


        return $abono;
    }

    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        $cliente_id = $user->cliente_id;
        $user->delete();

        $this->auditoriaRepository->create([
            'type_id'=>$id,
            'type'=>'abono',
            'accion'=>'Se ha eliminado un abono',
            'cliente_id'=>$cliente_id,
            'data'=>json_encode($id, true)
        ]);
    }

    public function all()
    {
        return $this->model->with('cliente', 'actualizado', 'formapago')->get();
    }

    public function searchBy(array $data)
    {
        $query = $this->model->with('cliente', 'actualizado', 'formapago');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->get();
    }


    public function searchOneBy(array $data)
    {
        $query = $this->model->with('cliente', 'actualizado', 'formapago');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->first();
    }

}