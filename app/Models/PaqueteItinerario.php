<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\FormasPago;
use App\Models\User;

class PaqueteItinerario extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'paquete_id',
        'itinerario_id',
    ];


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'paquetes_itinerario';

}
