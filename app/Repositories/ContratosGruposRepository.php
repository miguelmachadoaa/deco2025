<?php

namespace App\Repositories;

use App\Models\ContratosGrupos;
use Illuminate\Support\Str;
use App\Exceptions\FormularioException;
use App\Exceptions\EmailDuplicadoException;
use App\Exceptions\NoFoundClienteException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AuditoriaRepository;

final class ContratosGruposRepository{


    public function __construct(
        private readonly ContratosGrupos $model,
        private readonly AuditoriaRepository $auditoriaRepository,
        )
    {
    }

    public function find($id)
    {
        return $this->model->find($id);
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

    public function create(array $data)
    {
        $this->validar($data);

        $elemento = $this->model::create(
            [
                'nombre'=>$data['nombre'],
                'estatus'=>1,
                'user_id'=>Auth::getUser()->id
               ]
        );

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contratosGrupos',
            'accion'=>'Se ha creado un grupo de contrato', 
            'data'=>json_encode($data, true)
        ]);

        return $this->model->find($elemento->id);
    }

    public function update($id, array $data)
    {
        $elemento = $this->model->findOrFail($id);

        $elemento->update([
            'nombre'=>$data['nombre'],
        ]);

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contratosGrupos',
            'accion'=>'Se ha actualizado un grupo  de contrato', 
            'data'=>json_encode($data, true)
        ]);


        return $elemento;
    }


    

    public function delete($id)
    {
        $elemento = $this->model->findOrFail($id);
        $elemento->delete();

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contratosGrupos',
            'accion'=>'Se ha eliminado un grupo de contrato', 
            'data'=>json_encode($id, true)
        ]);
    }

    public function all()
    {
        return $this->model->all();
    }

}