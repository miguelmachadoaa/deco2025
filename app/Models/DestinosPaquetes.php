<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinosPaquetes extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'destinos_paquetes';

    protected $fillable = [
        'id',
        'paquete_id',
        'destino_id',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */


}
