<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ClientesRepository;
use App\Repositories\EmailRepository;

class VerificarCuotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-cuotas';

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
        EmailRepository $emailRepository,
        )
    {
        parent::__construct();

        $this->contratoDetallesRepository = $contratoDetallesRepository;
        $this->contratoRepository = $contratoRepository;
        $this->clientesRepository = $clientesRepository;
        $this->emailRepository = $emailRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cuotas = $this->contratoDetallesRepository->CuotasVencidas();

        foreach($cuotas as $cuota ){

         $contrato = $this->contratoRepository->find($cuota->contrato_id);

            echo $cuota->id.' / ';

            try {
                $this->emailRepository->vencida($cuota, $contrato);
            } catch (\Throwable $th) {
                //dd($th);
            }

            $this->contratoDetallesRepository->update($cuota->id, [
                'estatus'=>'2',
                'color'=>'red'
            ]);
            
            $contrato = $this->contratoRepository->find($cuota->contrato_id);

            $this->clientesRepository->update($contrato->cliente->id, ['estado'=>'rojo']);
            
        }
    }
}
