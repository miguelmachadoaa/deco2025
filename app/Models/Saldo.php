<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\ContratoDetalle;
use App\Models\Clientes;
class Saldo extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'saldo';

    protected $guarded = ['id'];

    public function cliente(){
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }


}
