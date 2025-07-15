<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ClientesRepository;

class VerificarCuotasMoroso extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-cuotas-moroso';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agrega cuota de mora a cuotas con 5 dias de vencidas ';

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
        $cuotas = $this->contratoDetallesRepository->CuotasVencidasMorosos();

        foreach($cuotas as $cuota ){

            $contrato = $this->contratoRepository->find($cuota->contrato_id);

            echo $cuota->id.' / ';

            if($contrato->estatus =='adjudicado'){

                $this->contratoDetallesRepository->update($cuota->id, [
                    'estatus'=>'2',
                    'mora'=>round($cuota->monto*0.15)
                ]);

            }else{

                $this->contratoDetallesRepository->update($cuota->id, [
                    'estatus'=>'2',
                    'mora'=>round($cuota->monto*0.05)
                ]);
            }

            
            $contrato = $this->contratoRepository->find($cuota->contrato_id);

            $this->clientesRepository->update($contrato->cliente->id, ['estado'=>'rojo']);
        }
    }
}
