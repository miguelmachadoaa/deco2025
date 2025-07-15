<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productos extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'products';
    
    protected $fillable = [
        'titulo',
        'slug',
        'precio',
        'descripcion',
        'caracteristicas',
        'condiciones',
        'marca',
        'estatus',
        'disponible',
        'user_id',
    ];

    public function imagenes()
    {
        return $this->hasMany('App\Models\ProductosImagenes', 'id_producto', 'id');
    }

    public function categorias()
    {
        return $this->belongsToMany('App\Models\Categorias', 'categorias_producto', 'categoria_id', 'producto_id');
    }

     public function colores()
    {
        return $this->belongsToMany(Colores::class, 'producto_colores', 'producto_id', 'color_id');
    }

    public function materiales()
    {
        return $this->belongsToMany(Materiales::class, 'producto_materiales', 'producto_id', 'material_id');
    }

    
}
