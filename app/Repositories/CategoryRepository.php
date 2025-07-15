<?php

namespace App\Repositories;

use App\Models\Categorias;


final class CategoryRepository{

    protected $model;

    public function __construct(Categorias $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->get();
    }


    public function findBySlug($id)
    {
        return $this->model->where('slug',$id)->first();
    }

    public function findRandom(int $limit=3)
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
    
       public function searchOneBy(array $data)
    {
        $query = $this->model->select('*')->with('productos');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->first();
    }


}