<?php

namespace App\Repositories;

use App\Models\Saldo;
use App\Exceptions\FormularioException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AuditoriaRepository;


final class SaldoRepository{

    public function __construct(
        private readonly Saldo $model,
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
        return $this->model->with('cliente')->find($id);
    }

    public function create(array $data)
    {

        $this->validar($data);

        $saldo =  $this->model->create([
            'cliente_id'=>$data['cliente_id'],
            'fecha'=>$data['fecha'],
            'monto'=>$data['monto'],
            'tipo'=>$data['tipo'],
            'observaciones'=>$data['observaciones'],
            'estatus'=>1,
            'creado_por'=>Auth::user()->id

        ]);

        $this->auditoriaRepository->create([
            'type_id'=>$saldo->id,
            'type'=>'saldo',
            'accion'=>'Se ha creado un saldo', 
            'data'=>json_encode($data, true)
        ]);

        return $saldo;

    }

    public function update($id, array $data)
    {
        $this->validar($data);
        
        $saldo = $this->model->findOrFail($id);
        $saldo->update($data);

        $this->auditoriaRepository->create([
            'type_id'=>$saldo->id,
            'type'=>'saldo',
            'accion'=>'Se ha actualizado un saldo', 
            'data'=>json_encode($data, true)
        ]);


        return $saldo;
    }

    public function delete($id)
    {
        $saldo = $this->model->findOrFail($id);
        $saldo->delete();

        $this->auditoriaRepository->create([
            'type_id'=>$saldo->id,
            'type'=>'saldo',
            'accion'=>'Se ha eliminado un saldo',
            'data'=>json_encode($id, true)
        ]);
    }

    public function all()
    {
        return $this->model->with('cliente')->get();
    }

}