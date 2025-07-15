<?php

namespace App\Repositories;

use App\Models\Pagos;
use App\Exceptions\FormularioException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\NotificacionRepository;
use App\Repositories\SaldoRepository;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\AuditoriaRepository;
use App\Repositories\EmailRepository;
use App\Mappers\PagosMapper;
use DateTime;


final class PagosRepository{

    public function __construct(
        private readonly Pagos $model,
        private readonly NotificacionRepository $notificacionRepository,
        private readonly SaldoRepository $saldoRepository,
        private readonly ContratoDetallesRepository $contratoDetallesRepository,
        private readonly AuditoriaRepository $auditoriaRepository,
        private readonly EmailRepository $emailRepository,
        private readonly PagosMapper $pagosMapper,
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
        $pago = $this->model->with('cliente')->find($id);

        return $this->pagosMapper->map($pago);
    }

    public function create(array $data)
    {

        $this->validar($data);

        $fecha = new DateTime('now');

        $pago = $this->model->create([
            'cliente_id'=>$data['cliente_id'],
            'cuota_id'=>$data['cuota_id'],
            'type_id'=>$data['type_id'],
            'type'=>$data['type'],
            'fecha'=>$data['fecha']??$fecha,
            'monto'=>$data['monto'],
            'observaciones'=>$data['observaciones'],
            'estatus'=>1,
            'creado_por'=>Auth::user()->id

        ]);

        $this->notificacionRepository->create([
            'type_id'=>$pago->id,
            'type'=>'pago',
            'title'=>'Se ha creado un pago',
            'body'=>'Se ha creado un pago'
        ]);

        $this->auditoriaRepository->create([
            'type_id'=>$pago->id,
            'type'=>'pago',
            'accion'=>'Se ha creado un pago', 
            'data'=>json_encode($data, true)
        ]);

        $this->emailRepository->payment($this->find($pago->id));

        return $pago;
        
    }

    public function update($id, array $data)
    {
        $this->validar($data);
        
        $elemento = $this->model->findOrFail($id);
        $elemento->update($data);

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'pago',
            'accion'=>'Se ha actualizado un pago', 
            'data'=>json_encode($data, true)
        ]);


        return $elemento;
    }

    public function delete($id)
    {
        $elemento = $this->model->findOrFail($id);

        $fecha = new DateTime('now');

        $elemento->delete();

        if($elemento->id){

            if($elemento->type=='cuota'){

                $this->saldoRepository->create([
                    'cliente_id'=>$elemento->cliente_id,
                    'fecha'=>$fecha,
                    'monto'=>$elemento->monto,
                    'tipo'=>1,
                    'observaciones'=>'Reposicion de saldo por eliminacion de pago #'.$elemento->id.' ',
                ]);

                $cuota = $this->contratoDetallesRepository->update(
                    $elemento->type_id, 
                    ['estatus'=>0, 'color'=>'ligth']
                );

            }

            $this->auditoriaRepository->create([
                'type_id'=>$elemento->id,
                'type'=>'pago',
                'accion'=>'Se ha eliminado un pago',
                'data'=>json_encode($id, true)
            ]);
        }

    }

    public function all()
    {
        return $this->pagosMapper->multiMap($this->model->with('cliente')->get());
    }

    public function searchBy(array $data)
    {
        $query = $this->model->with('cliente');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $this->pagosMapper->multiMap($query->get());
    }


    public function searchOneBy(array $data)
    {
        $query = $this->model->with('cliente');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $this->pagosMapper->map($query->first());
    }

}