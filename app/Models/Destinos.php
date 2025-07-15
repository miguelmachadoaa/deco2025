<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destinos extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'titulo',
        'slug',
        'descripcion',
        'imagen',
        'pais',
        'idioma',
        'views',
        'order',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'destinos';

    public function paquetes()
    {
        return $this->hasMany(DestinosPaquetes::class, 'destino_id', 'id');
    }


}
