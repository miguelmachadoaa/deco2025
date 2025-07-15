<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\Contrato;
use App\Models\Pagos;



class ContratoDetalle extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'contratos_detalle';

    protected $guarded = ['id'];

    public function pagos() {
        return $this->hasMany(Pagos::class, 'cuota_id')->where('type', '=', 'cuota');
    }

    public function getSaldoAttribute()
    {
        $total_pagos =  $this->pagos()->sum('monto');

        return $this->monto+$this->mora-$total_pagos;
    }


}
