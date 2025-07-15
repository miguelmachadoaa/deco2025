<?php

namespace App\Console\Commands;

use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ClientesRepository;



use Illuminate\Console\Command;

class VerificarCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-cliente';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'verifica las clientes para cambiarlos de color ';

    public function __construct(
        ContratoDetallesRepository $contratoDetallesRepository,
        ContratoRepository $contratoRepository,
        ClientesRepository $clientesRepository,
        )
    {
        parent::__construct();

        $this->contratoDetallesRepository = $contratoDetallesRepository;
        $this->contratoRepository = $contratoRepository;
        $this->clientesRepository = $clientesRepository;
    }



    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contratos = $this->contratoRepository->all();
        
        foreach($contratos as $contrato){

            $red = $contrato->detalles()->where('color', 'red')->count();

            if($red >= 2){
                $this->clientesRepository->update($contrato->cliente_id, ['estado'=>'red']);
                echo $contrato->cliente_id.' / ';
            }
        }
    }
}
