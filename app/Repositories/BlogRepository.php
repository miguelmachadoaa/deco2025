<?php

namespace App\Repositories;

use App\Models\Blog;


final class BlogRepository{

    protected $model;

    public function __construct(Blog $model)
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


    public function searchBy(array $data, $limit= null, $random=null)
    {
        $query = $this->model->select('blogs.*');

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
        $query = $this->model->select('blogs.*');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->first();
    }

    public function all()
    {
        return $this->model->get();
    }

}