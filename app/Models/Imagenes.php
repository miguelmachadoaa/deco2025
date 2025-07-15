<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\FormasPago;
use App\Models\User;

class Imagenes extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'url',
        'titulo',
        'type_id',
        'type',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'imagenes';


}
