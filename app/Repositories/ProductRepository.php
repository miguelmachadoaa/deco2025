<?php

namespace App\Repositories;

use App\Models\Productos;


final class ProductRepository{

    protected $model;

    public function __construct(Productos $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->with('imagenes', 'categorias')->find($id);
    }

    public function findArray($id)
    {
        return $this->model->with('imagenes', 'categorias')->find($id)->toArray();
    }


    public function findBySlug($id)
    {
        return $this->model->with('imagenes', 'categorias')->where('slug',$id)->first();
    }

    public function findRandom(int $limit=3)
    {
        return $this->model->with('imagenes', 'categorias')->inRandomOrder()->limit($limit)->get();
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

    public function all()
    {
        return $this->model->with('imagenes', 'categorias')->get();
    }


    public function searchBy(array $data)
    {
        $query = $this->model->with('imagenes', 'categorias');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->get();
    }


    public function searchOneBy(array $data)
    {
        $query = $this->model->with('imagenes', 'categorias');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->first();
    }



}