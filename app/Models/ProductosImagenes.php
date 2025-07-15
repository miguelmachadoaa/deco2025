<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProductosImagenes extends Authenticatable
{

    protected $table = 'productos_imagenes';
    
    protected $fillable = [
        'id_producto',
        'imagen',
        'order',
        'title',
        'alt',
        'user_id',
    ];
    
}
