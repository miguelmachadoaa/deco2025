<?php

namespace App\Repositories;

use App\Models\FormasPago;
use App\Exceptions\FormularioException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SaldoRepository;
use App\Repositories\NotificacionRepository;
use App\Repositories\AuditoriaRepository;

final class FormasPagoRepository{

    public function __construct(
        private readonly FormasPago $model,
        private readonly NotificacionRepository $notificacionRepository,
        private readonly AuditoriaRepository $auditoriaRepository,
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

        return $this->model->find($id); 
    }

    public function create(array $data)
    {

        $this->validar($data);

        $elemento =  $this->model->create([
            'nombre'=>$data['nombre'],
            'descripcion'=>$data['descripcion'],
            'moneda'=>$data['moneda'],
            'creado_por'=>Auth::user()->id

        ]);

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'formas pago',
            'accion'=>'Se ha creado una forma de pago', 
            'data'=>json_encode($data, true)
        ]);

        return $elemento;


    }

    public function update($id, array $data)
    {
        $this->validar($data);
        
        $elemento = $this->model->findOrFail($id);

        $elemento->update($data);

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'forma pago',
            'accion'=>'Se ha actualizado un abono', 
            'data'=>json_encode($data, true)
        ]);

        return $elemento;
    }

    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        $user->delete();

        $this->auditoriaRepository->create([
            'type_id'=>$id,
            'type'=>'abono',
            'accion'=>'Se ha eliminado un abono', 
            'data'=>json_encode($id, true)
        ]);
    }

    public function all()
    {
        return $this->model->get();
    }

}