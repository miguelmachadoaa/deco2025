<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorias extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'categories';
    
    protected $fillable = [
        'titulo',
        'slug',
        'descripcion',
        'imagen',
        'estatus',
        'categoria_padre_id',
        'user_id'
    ];



    //relacion de muchos a uno con tabla pivot categoria_productos
    public function productos()
    {
        return $this->belongsToMany(Productos::class, 'categorias_producto', 'categoria_id', 'producto_id')
                    ->withPivot('id', 'created_at', 'updated_at')
                    ->withTimestamps(); 

    }


  

    
}
