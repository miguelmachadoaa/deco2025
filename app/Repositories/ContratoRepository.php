<?php

namespace App\Repositories;

use App\Models\Contrato;
use App\Models\ContratoTipo;
use Illuminate\Support\Str;
use App\Exceptions\FormularioException;
use App\Exceptions\EmailDuplicadoException;
use App\Exceptions\NoFoundClienteException;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Repositories\NotificacionRepository;
use App\Repositories\AuditoriaRepository;
use App\Repositories\EmailRepository;


final class ContratoRepository{

    public function __construct(
        private readonly Contrato $model,
        private readonly ContratoTipo $contratoTipo,
        private readonly NotificacionRepository $notificacionRepository,
        private readonly AuditoriaRepository $auditoriaRepository,
        private readonly EmailRepository $emailRepository,
    )
    {
    }

    public function find($id)
    {
        return $this->model->with('cliente', 'detalles', 'producto', 'usuario', 'vendedor')->find($id);
    }

    public function validar(array $data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                if ($key == 'observaciones') {
                    continue;
                }

                if ($key == 'categoria') {
                    if($value == 0){
                        continue;
                    }
                }

                if ($key == 'saldo') {
                        continue;
                }


                throw new FormularioException($key);
            }
        }
    }

    public function create(array $data)
    {
        $this->validar($data);

        $elemento = $this->model::create([
            'cliente_id' => $data['cliente_id'],
            'producto_id' => $data['producto_id'],
            'descripcion' => $data['descripcion'],
            'inscripcion' => $data['inscripcion'],
            'asesor' => $data['asesor'],
            'monto' => $data['monto'],
            'created_at' => $data['fecha_creacion'],
            'creado_por'=> Auth::user()->id
        ]);

        

        $this->notificacionRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contrato',
            'title'=>'Se ha creado un contrato',
            'body'=>'Se ha creado un contrato'
        ]);

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contrato',
            'accion'=>'Se ha creado un contrao', 
            'data'=>json_encode($data, true)
        ]);

        $elemento = $this->model::with('cliente', 'detalles', 'producto', 'usuario', 'vendedor')->find($elemento->id);

        $this->addDetalles($elemento, $data);

        $this->emailRepository->contrato($this->find($elemento));

        return $this->model->find($elemento->id);
    }

    public function addDetalles($contrato, array $data)
    {
        $contrato->detalles()->delete();

        $tipo = $this->contratoTipo::find($data['tipo']);

        $monto_cuota = round(($data['monto']*($tipo->porcentaje/100))*1.05);

        $fecha = new DateTime('now');

        for ($i=0; $i < $tipo->cuotas; $i++) { 

            $contrato->detalles()->create([
                'cuota' => $i+1,
                'monto' => $monto_cuota,
                'fecha' => $contrato->created_at,
            ]);
        }
        
    }

    public function update($id, array $data)
    {
        $elemento = $this->model->findOrFail($id);
        $elemento->update($data);

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contrato',
            'accion'=>'Se ha actualizado un contrato',
            'data'=>json_encode($data, true),
            'cliente_id'=>$elemento->cliente_id
        ]);


        return $elemento;
    }

    public function delete($id)
    {
        $elemento = $this->model->findOrFail($id);
        $elemento->delete();

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contrato',
            'accion'=>'Se ha eliminado un contrao', 
            'data'=>json_encode($id, true)
        ]);


    }

    public function all()
    {
        return $this->model->with('cliente', 'detalles', 'producto', 'usuario', 'vendedor')->get();
    }

    public function searchBy(array $data)
    {
        $query = $this->model->with('cliente', 'detalles', 'producto', 'usuario', 'vendedor');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->get();
    }


    public function searchOneBy(array $data)
    {
        $query = $this->model->with('cliente', 'detalles', 'producto', 'usuario', 'vendedor');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->first();
    }

    

}