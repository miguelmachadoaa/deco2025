<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriasProductos extends Authenticatable
{
    protected $table = 'categorias_producto';
    
    protected $fillable = [
        'categoria_id',
        'producto_id',
    ];

  

    
}
