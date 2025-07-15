<?php

namespace App\Repositories;

use App\Models\Pais;


final class PaisesRepository{

    protected $model;

    public function __construct(Pais $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
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

}