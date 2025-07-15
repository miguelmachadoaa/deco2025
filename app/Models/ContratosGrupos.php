<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\ContratoDetalle;
use App\Models\Contrato;

class ContratosGrupos extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'contratos_grupos';

    protected $guarded = ['id'];

    public function contratos(){
        return $this->hasMany(Contrato::class, 'grupo_id', 'id');
    }


}
