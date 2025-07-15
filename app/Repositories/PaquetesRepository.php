<?php

namespace App\Repositories;

use App\Models\Paquete;
use App\Models\DestinosPaquetes;


final class PaquetesRepository{

    protected $model;
    protected $destinosPaquetes;

    public function __construct(Paquete $model, DestinosPaquetes $destinosPaquetes)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findRandom(int $limit)
    {
        return $this->model->inRandomOrder()->limit($limit)->get();
    }


    public function all()
    {
        return $this->model->all();
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

    public function deleteAllDestinos($id){

        $paquete = $this->model->findOrFail($id);

        $paquete->destinos()->delete();

        return $paquete;

    }

    public function addDestinos($id, array $data)
    {
        $paquete = $this->model->findOrFail($id);

        foreach ($data as $destinoId) {
            $paquete->destinos()->create([
                'paquete_id' => $paquete->id,
                'destino_id' => $destinoId
            ]);
        }

        return $paquete;
    }

    public function getDestinos($id){

        return DestinosPaquetes::where('paquete_id', $id)->get();
    }

    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        $user->delete();
    }


    public function searchBy(array $data, $limit= null, $random=null)
    {
        $query = $this->model->select('*');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }

        if($limit){
            $query->limit($limit);
        }

        if($random){
            $query->inRandomOrder();
        }
        
        return $query->get();
    }


    public function searchOneBy(array $data)
    {
        $query = $this->model->select('*');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->first();
    }

}