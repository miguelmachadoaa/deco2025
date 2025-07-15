<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientesImagenes extends Authenticatable
{

    protected $table = 'cliente_imagenes';
    
    protected $fillable = [
        'id_cliente',
        'imagen',
        'order',
        'title',
        'alt',
        'user_id',
    ];
    
}
