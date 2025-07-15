<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blogs extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'blogs';
    
    protected $fillable = [
        'titulo',
        'contenido',
        'slug',
        'imagen',
        'idioma',
        'views',
        'estatus',
        'blog_category_id',
        'user_id',
    ];

  

    
}
