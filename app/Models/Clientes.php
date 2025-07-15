<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\ClientesEmpleo;
use App\Models\ClientesReferencias;
use App\Models\ClientesRedes;
use App\Models\ClientesDirecciones;
use App\Models\ClientesTelefonos;
use App\Models\Contrato;
use App\Models\Abono;
use App\Models\Pagos;
use App\Models\Saldo;


class Clientes extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    protected $table = 'clientes';

    protected $guarded = ['id'];

    public function contratos() {
        return $this->hasMany(Contrato::class, 'cliente_id');
    }

    public function abonos() {
        return $this->hasMany(Abono::class, 'cliente_id');
    }

    public function pagos() {
        return $this->hasMany(Pagos::class, 'cliente_id');
    }

    public function saldo() {
        return $this->hasMany(Saldo::class, 'cliente_id');
    }

    public function direcciones() {
        return $this->hasMany(ClientesDirecciones::class, 'cliente_id');
    }

    public function empleo() {
        return $this->hasMany(ClientesEmpleo::class, 'cliente_id');
    }

    public function referencias() {
        return $this->hasMany(ClientesReferencias::class, 'cliente_id');
    }

    public function redes() {
        return $this->hasMany(ClientesRedes::class, 'cliente_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'usuario_relacionado');
    }

    public function getSaldoClienteAttribute()
    {
        $entradas =  $this->saldo()->where('tipo', '=', '1')->sum('monto');
        $salidas =  $this->saldo()->where('tipo', '=', '2')->sum('monto');
        return $entradas - $salidas;
    }

    public function scopeSearch($query, $searchTerm) {
        return $query->where('nombre', 'like', '%' . $searchTerm . '%')
                        ->orWhere('apellido', 'like', '%' . $searchTerm . '%')
                        ->orWhere('cedula', 'like', '%' . $searchTerm . '%')
                        ->orWhere('telefono', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%');
    }

    public function telefonos() {
        return $this->hasMany(ClientesTelefonos::class, 'cliente_id');
    }




}
