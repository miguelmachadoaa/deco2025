<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ClientesRepository;

class VerificarContratos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-contratos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica estado de contratos para moverlos a listado de compras';

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
        $contratos = $this->contratoRepository->searchBy(['estatus'=>'activo']);

        foreach($contratos as $contrato){

            $orange = $contrato->detalles()->where('color', 'orange')->count();
            $green = $contrato->detalles()->where('color', 'green')->count();

            if($orange >= 5 && $green >=2){
                $this->contratoRepository->update($contrato->id, ['estatus'=>'lista_compra']);
                echo $contrato->id.' / ';
            }
        }

    }
}
