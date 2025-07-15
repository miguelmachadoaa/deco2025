<?php

namespace App\Console\Commands;


use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ClientesRepository;


use Illuminate\Console\Command;

class NotificarCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notificar-cliente';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $cuotasVencidas = $this->contratoDetallesRepository->CuotasVencidas();

        $contratos= [];

        foreach($cuotasVencidas as $cv){

            $contratos[$cv->contrato_id]=$cv->contrato_id;

        }

        if(count($contratos)){
            foreach($contratos as $key => $value){

                $contrato = $this->contratoRepository->find($key);

                dd($key);


            }
        }
    }
}
