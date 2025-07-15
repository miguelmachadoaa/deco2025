<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Materiales extends Model
{
    protected $table = 'materiales';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
    ];

    
    
}
