<?php

namespace App\Repositories;

use App\Models\Destinos;


final class DestinosRepository{

    protected $model;

    public function __construct(Destinos $model)
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
        return $this->model->orderBy('order', 'asc')->get();
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
        }else{
            $query->orderBy('order', 'asc');
        }
        
        return $query->get();
    }


    public function searchOneBy(array $data)
    {
        $query = $this->model->select('*')->with('paquetes');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->first();
    }


    public function getPaquuetes($destinoId){

        $query = $this->model->select('paquete.*')->distinct()
        ->join('destinos_paquetes', 'destinos_paquetes.destino_id', '=', 'destinos.id')
        ->join('paquete', 'destinos_paquetes.paquete_id', '=', 'paquete.id')
        ->where('destinos.id', $destinoId);

        return $query->get();


    }


}