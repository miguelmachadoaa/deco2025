<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class ClientesDirecciones extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'clientes_direcciones';

    protected $guarded = ['id'];

    public function getCompletaAttribute()
    {
        return $this->ciudad.' '.$this->urbanizacion.' '.$this->direccion;
    }


}
