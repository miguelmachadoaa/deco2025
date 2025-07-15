<?php

namespace App\Repositories;

use App\Models\Dolar;
use App\Exceptions\FormularioException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SaldoRepository;
use App\Repositories\NotificacionRepository;
use App\Repositories\AuditoriaRepository;
use Datetime;


final class DolarRepository{

    public function __construct(
        private readonly Dolar $model, 
        private readonly SaldoRepository $saldoRepository,
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

        $dolar =  $this->model->create([
            'valor'=>$data['valor'],
            'fecha'=>new DateTime('now'),
            'creado_por'=>Auth::user()->id

        ]);

        $this->auditoriaRepository->create([
            'type_id'=>$dolar->id,
            'type'=>'dolar',
            'accion'=>'Se ha creado una nueva cotizacion', 
            'data'=>json_encode($data, true)
        ]);

        return $dolar;


    }

    public function update($id, array $data)
    {
        $this->validar($data);
        
        $dolar = $this->model->findOrFail($id);

        $dolar->update($data);

        $this->auditoriaRepository->create([
            'type_id'=>$dolar->id,
            'type'=>'dolar',
            'accion'=>'Se ha actualizado un dolar', 
            'data'=>json_encode($data, true)
        ]);

        return $dolar;
    }

    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        $user->delete();

        $this->auditoriaRepository->create([
            'type_id'=>$id,
            'type'=>'dolar',
            'accion'=>'Se ha eliminado un dolar', 
            'data'=>json_encode($id, true)
        ]);
    }

    public function all()
    {
        return $this->model->get();
    }

    public function last()
    {
        return $this->model->latest()->first();
    }

}