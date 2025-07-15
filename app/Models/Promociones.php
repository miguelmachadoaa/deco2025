<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Promociones extends Model 
{

    protected $table = 'promociones';
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'idioma',
        'estatus',
        'user_id'
    ];

  

    
}
