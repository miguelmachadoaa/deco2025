<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paquete extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'id',
        'destino_id',
        'titulo',
        'slug',
        'descripcion',
        'dias',
        'include',
        'noinclude',
        'informacion',
        'imagen',
        'pais',
        'idioma',
        'views',
        'type',
        'estatus',
    ];



    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'paquete';

   

    public function destinos()
    {
        return $this->hasMany(DestinosPaquetes::class);
    }

    
}
