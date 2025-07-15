<?php

namespace App\Repositories;

use App\Models\Itinerario;
use App\Models\PaqueteItinerario;


final class ItinerarioRepository{

    protected $model;

    public function __construct(Itinerario $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findBySlug($id)
    {
        return $this->model->where('slug',$id)->first();
    }

    public function findRandom(int $limit)
    {
        return $this->model->inRandomOrder()->limit($limit)->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->model->findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        $user->delete();
    }

    public function attach($paqueteId, $itinerarioId)
    {

        $exist = PaqueteItinerario::where('paquete_id', $paqueteId)->where('itinerario_id', $itinerarioId)->first();

        if(!$exist){
            PaqueteItinerario::create([
                'paquete_id'=>$paqueteId,
                'itinerario_id'=>$itinerarioId,
            ]);
        }

        
    }

    public function deattach($paqueteId, $itinerarioId)
    {
        PaqueteItinerario::where('paquete_id', $paqueteId)->where('itinerario_id', $itinerarioId)->delete();
    }

    public function getItinerario($paqueteId)
    {
        return $this->model->select('itinerario.*')
        ->join('paquetes_itinerario', 'paquetes_itinerario.itinerario_id', '=', 'itinerario.id')
        ->where('paquetes_itinerario.paquete_id', '=', $paqueteId)
        ->whereNull('paquetes_itinerario.deleted_at' )
        ->get();
    }

}