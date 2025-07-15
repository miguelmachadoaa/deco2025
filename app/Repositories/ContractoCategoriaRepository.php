<?php

namespace App\Repositories;

use App\Models\ContratoCategoria;
use Illuminate\Support\Str;
use App\Exceptions\FormularioException;
use App\Exceptions\EmailDuplicadoException;
use App\Exceptions\NoFoundClienteException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AuditoriaRepository;

final class ContractoCategoriaRepository{


    public function __construct(
        private readonly ContratoCategoria $model,
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
                'monto'=>$data['monto'],
                'estatus'=>1,
                'creado_por'=>Auth::getUser()->id
               ]
        );

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contratoCategoria',
            'accion'=>'Se ha creado una categoria de contrato', 
            'data'=>json_encode($data, true)
        ]);

        return $this->model->find($elemento->id);
    }

    public function update($id, array $data)
    {
        $elemento = $this->model->findOrFail($id);
        $elemento->update([
            'nombre'=>$data['nombre'],
                'monto'=>$data['monto'],
        ]);

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'contratoCategoria',
            'accion'=>'Se ha actualizado una categoria de contrato', 
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
            'type'=>'contratoCategoria',
            'accion'=>'Se ha eliminado una categoria de contrato', 
            'data'=>json_encode($id, true)
        ]);
    }

    public function all()
    {
        return $this->model->all();
    }

}