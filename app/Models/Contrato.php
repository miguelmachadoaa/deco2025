<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\ContratoDetalle;
use App\Models\Clientes;
use App\Models\Productos;
use App\Models\User;
class Contrato extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'contratos';

    protected $guarded = ['id'];

    public function detalles() {
        return $this->hasMany(ContratoDetalle::class, 'contrato_id');
    }

    public function cliente(){
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }

    public function producto(){
        return $this->hasOne(Productos::class, 'id', 'producto_id');
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id', 'creado_por');
    }

    public function vendedor(){
        return $this->hasOne(User::class, 'id', 'asesor');
    }


}
