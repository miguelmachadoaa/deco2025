<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sliders extends Authenticatable
{

    protected $table = 'sliders';
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'enlace',
        'imagen',
        'idioma',
        'estatus',
        'user_id'
    ];

  

    
}
