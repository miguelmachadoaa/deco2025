<?php

namespace App\Repositories;

use App\Models\Contrato;
use App\Models\ContratoTipo;
use App\Models\ContratoDetalle;
use Illuminate\Support\Str;
use App\Exceptions\FormularioException;
use App\Exceptions\EmailDuplicadoException;
use App\Exceptions\NoFoundClienteException;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AuditoriaRepository;
use App\Mappers\CuotasMapper;

final class ContratoDetallesRepository{

    public function __construct(
        private readonly ContratoDetalle $model,
        private readonly ContratoTipo $contratoTipo,
        private readonly Contrato $contrato,
        private readonly AuditoriaRepository $auditoriaRepository,
        private readonly CuotasMapper $mapper,
    )
    {
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function validar(array $data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                if ($key == 'observaciones') {
                    continue;
                }
                throw new FormularioException($key);
            }
        }
    }

    public function create(array $data)
    {
        $this->validar($data);

        $elemento = $this->model::create([
            'cliente_id' => $data['cliente_id'],
            'descripcion' => $data['descripcion'],
            'monto' => $data['categoria'],
            'creado_por'=> Auth::user()->id
        ]);

        $elemento = $this->model::with('detalles')->find($elemento->id);

        $this->addDetalles($elemento, $data);

        return $this->find($elemento->id);
    }

    public function addDetalles($contrato, array $data)
    {
        $contrato->detalles()->delete();

        $tipo = $this->contratoTipo::find($data['tipo']);

        $monto_cuota = ($data['categoria']*($tipo->porcentaje/100))*1.05;

        $fecha = new DateTime('now');

        for ($i=0; $i < $tipo->cuotas; $i++) {

            $contrato->detalles()->create([
                'cuota' => $i+1,
                'monto' => $monto_cuota,
                'fecha' => $fecha,
            ]);
        }
        
    }

    public function update($id, array $data)
    {
        $elemento = $this->find($id);


        if(isset($elemento->id)){

            $elemento->update($data);

            $this->auditoriaRepository->create([
                'type_id'=>$elemento->id,
                'type'=>'cuota',
                'accion'=>'Se ha actualizado una cuota', 
                'data'=>json_encode($data, true)
            ]);

        }

        return $elemento;
    }

    public function delete($id)
    {
        $elemento = $this->find($id);
        $elemento->delete();

        $this->auditoriaRepository->create([
            'type_id'=>$elemento->id,
            'type'=>'cuota',
            'accion'=>'Se ha eliminado una cuota', 
            'data'=>json_encode($id, true)
        ]);


    }

    public function all()
    {
        return $this->model->get();
    }
    
    public function CuotasVencidas(){

        return $this->model
        ->select('contratos_detalle.*')
        ->join('contratos', 'contratos_detalle.contrato_id','=','contratos.id')
        ->where('contratos_detalle.fecha', '<=', new DateTime('now'))
        ->where('contratos_detalle.estatus', '=', 0)
        ->where('contratos.estatus', '<>', 'desactivado')
        ->get();
    }


    public function CuotasVencidasMorosos(){

        $date = new DateTime();
        $date->modify("-5 days");

        return $this->model
        ->where('fecha', '<=', $date->format("Y-m-d"))
        ->where('estatus', '=', 2)
        ->get();
    }

    public function searchBy(array $data)
    {
        $query = $this->model->select();

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $this->mapper->multiMap($query->get());
    }
}