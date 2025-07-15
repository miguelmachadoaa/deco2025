<?php

namespace App\Repositories;

use App\Models\Clientes;
use App\Models\ClientesDirecciones;
use App\Models\ClientesEmpleo;
use App\Models\ClientesRedes;
use App\Models\ClientesReferencias;
use App\Models\ClientesTelefonos;
use App\Models\ClientesEmails;
use Illuminate\Support\Str;
use App\Exceptions\FormularioException;
use App\Exceptions\EmailDuplicadoException;
use App\Exceptions\DocumentoDuplicadoException;
use App\Exceptions\NoFoundClienteException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\NotificacionRepository;
use App\Repositories\AuditoriaRepository;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ProductRepository;
final class ClientesRepository{

    public function __construct(
        private readonly Clientes $model,
        private readonly NotificacionRepository $notificacionRepository,
        private readonly AuditoriaRepository $auditoriaRepository,
        private readonly ContratoDetallesRepository $contratoDetallesRepository,
        private readonly ContratoRepository $contratoRepository,
        private readonly ProductRepository $productRepository,
    )
    {
    }

    public function find($id)
    {
        return $this->model->with(
            'direcciones',
            'telefonos',
            'redes',
            'empleo',
            'referencias',
            'contratos',
            'abonos',
            'pagos',
            'user',
        )->find($id);
    }

    public function validar(array $data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                if ($key == 'observaciones' 
                    || $key == 'facebook' 
                    || $key == 'twitter' 
                    || $key == 'instagram'
                    || $key == 'tiktok'
                    || $key == 'archivo'
                ) {
                    continue;
                }
                throw new FormularioException($key);
            }
        }
    }

    public function create(array $data)
    {
        $this->validar($data);

        if(isset($data['id'])){

            $client = $this->model::where('id', $data['id'])->first();

            if(!$client){
                throw new FormularioException($data['id']);
            }

            $clientmail = $this->model::where('email', $data['email'])
           // ->Where('id', '<>', $data['id'])
            ->first();

            if($clientmail){
                throw new EmailDuplicadoException($data['email']);
            }

            $client->update([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'documento' => $data['documento'],
                'tipo_documento' => $data['tipo_documento']??'V',
                'profesion' => $data['profesion'],
                'fecha_nacimiento' => $data['fechaNacimiento'],
                'sexo' => $data['genero'],
                'estado_civil' => $data['estadoCivil'],
                'email' => $data['email'],
                'telefono' => $data['telefono_0'],
                'celular' => $data['telefono_1'],
                'direccion' => $data['direccion'],
                'facebook' => $data['facebook'],
                'instagram' => $data['instagram'],
                'tiktok' => $data['tiktok'],
                'twitter' => $data['twitter'],
                'archivo' => $data['archivo'],
            ]);

            $this->auditoriaRepository->create([
                'type_id'=>$client->id,
                'type'=>'abono',
                'accion'=>'Se ha actualizado un cliente', 
                'data'=>json_encode($data, true)
            ]);
   

        }else{

            $clientmail = $this->model::where('email', $data['email'])
            ->first();

            $clientDocuemto = $this->model::where('documento', $data['documento'])
            ->first();

            if($clientmail){
                throw new EmailDuplicadoException($data['email']);
            }

            if($clientDocuemto){
                throw new DocumentoDuplicadoException($data['documento']);
            }

            $client = $this->model->create([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'documento' => $data['documento'],
                'tipo_documento' => $data['tipo_documento']??'V',
                'profesion' => $data['profesion'],
                'fecha_nacimiento' => $data['fechaNacimiento'],
                'sexo' => $data['genero'],
                'estado_civil' => $data['estadoCivil'],
                'email' => $data['email'],
                'telefono' => $data['telefono_0'],
                'celular' => $data['telefono_1'],
                'direccion' => $data['direccion'],
                'facebook' => $data['facebook'],
                'instagram' => $data['instagram'],
                'tiktok' => $data['tiktok'],
                'twitter' => $data['twitter'],
                'archivo' => $data['archivo'],
                'usuario_relacionado' => $data['usuario_relacionado'],
                'creado_por'=> Auth::user()->id
            ]);

           /* $this->notificacionRepository->create([
                'type_id'=>$client->id,
                'type'=>'cliente',
                'title'=>'Se ha creado un cliente',
                'body'=>'Se ha creado un cliente'
            ]);*/

            $this->auditoriaRepository->create([
                'type_id'=>$client->id,
                'type'=>'cliente',
                'accion'=>'Se ha creado un cliente', 
                'data'=>json_encode($data, true)
            ]);

        }

        $this->addAddress($client->id, $data);

        $this->addPhone($client->id, $data);

        $this->addJob($client->id, $data);

        $this->addRefer($client->id, $data);

        return $this->model->find($client->id);
    }

    public function addRefer($id, array $data)
    {
        $client = $this->model->find($id);

        $client->referencias()->delete();

        $client->referencias()->create([
            'nombre' => $data['nombreReferencia0'],
            'apellido' => $data['apellidoReferencia0'],
            'telefono' => $data['telefonoReferencia0'],
            'parentesco' => $data['parentescoReferencia0'],
        ]);

        $client->referencias()->create([
            'nombre' => $data['nombreReferencia1'],
            'apellido' => $data['apellidoReferencia1'],
            'telefono' => $data['telefonoReferencia1'],
            'parentesco' => $data['parentescoReferencia1'],
        ]);
        
    }

    public function update($id, array $data)
    {
        $cliente = $this->model->findOrFail($id);
        $cliente->update($data);
        return $cliente;
    }

    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        $user->delete();
    }

    public function all()
    {
        return $this->model->with(
            'direcciones',
            'telefonos',
            'redes',
            'empleo',
            'referencias',
            'contratos',
            'abonos',
            'pagos',
        )->get();
    }

    public function morosos()
    {
        $detalles = $this->contratoDetallesRepository->searchBy(['estatus'=>'2']);

        $cliente = [];

        foreach($detalles as $d){

            $contrato = $this->contratoRepository->find($d->contrato_id);

            $cliente[$contrato->cliente->id]=$contrato->cliente->id;
            
        }

        return $this->model->whereIn('id',$cliente)->get();
    }

    public function addAddress($id, array $data)
    {
        $client = $this->model->find($id);

        $client->direcciones()->delete();

        $client->direcciones()->create([
            'direccion' => $data['direccion'],
            'ciudad' => $data['ciudad'],
            'urbanizacion' => $data['urbanizacion']??'',
            'telefono' => $data['telefono_0']??'',
            'propiedad' => $data['propiedad']??'',
            'estado' => $data['estado']??'  ',
        ]);
    }

    public function addPhone($id, array $data)
    {
        $client = $this->model->find($id);

        $client->telefonos()->delete();

        $client->telefonos()->create([
            'telefono' => $data['telefono_0'],
            'codigo' => $data['codigo']??'',
            'tipo' => $data['operadora']??'telefono',
        ]);

        $client->telefonos()->create([
            'telefono' => $data['telefono_1'],
            'codigo' => $data['codigo']??'',
            'tipo' => $data['operadora']??'celular',
        ]);
    }

    public function addEmail($id, array $data)
    {
        $client = $this->model->find($id);
        $client->emails()->delete();
        $client->emails()->create($data);
    }

    public function addRedes($id, array $data)
    {
        $client = $this->model->find($id);
        $client->redes()->create($data);
    }

    public function addJob($id, array $data)
    {
        $client = $this->model->find($id);

        $client->empleo()->delete();

        $client->empleo()->create([
            'nombre_empresa' => $data['nombre_empresa'],
            'cargo' => $data['cargo'],
            'direccion_empresa' => $data['direccionEmpresa'],
            'ciudad_empresa' => 1,
            'estado_empresa' => 1,
            'telefono_empresa' => $data['telefonoEmpresa'],
            'tiempo' => $data['tiempo'],
            'salario' => $data['salario'],
            'tipo' => 1,
        ]);
    }

    public function searchBy(array $data)
    {
        $query = $this->model->with(
            'direcciones',
            'telefonos',
            'redes',
            'empleo',
            'referencias',
            'contratos',
            'abonos',
            'pagos',
        );

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->get();
    }

    public function searchOneBy(array $data)
    {
        $query = $this->model->with(
        'direcciones',
        'telefonos',
        'redes',
        'empleo',
        'referencias',
        'contratos',
        'abonos',
        'pagos');

        foreach($data as $key=>$value){
            $query->where($key, $value);
        }
        
        return $query->first();
    }

}