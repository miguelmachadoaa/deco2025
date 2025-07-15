<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ClientesRepository;
use App\Repositories\AuditoriaRepository;
use App\Models\Auditoria;
use App\Models\Saldo;
use App\Models\Contrato;
use App\Models\Pagos;
use App\Models\ContratoDetalle;
use App\Models\Abono;
use DOMDocument;

class RecargaCuota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recarga-cuotas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'verificar cuotas vencidas por el sistema que no aplican ';

    public function __construct(
        ContratoDetallesRepository $contratoDetallesRepository,
        ContratoRepository $contratoRepository,
        ClientesRepository $clientesRepository,
        AuditoriaRepository $auditoriaRepository,
        )
    {
        parent::__construct();

        $this->contratoDetallesRepository = $contratoDetallesRepository;
        $this->contratoRepository = $contratoRepository;
        $this->clientesRepository = $clientesRepository;
        $this->auditoriaRepository = $auditoriaRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $fecha_actual = date("Y-m-d");
        $fecha_pasada = strtotime('-5 day', strtotime($fecha_actual));
        $fecha_pasada = date('Y-m-d', $fecha_pasada);

        $cuotas = ContratoDetalle::where('fecha', '>=', $fecha_pasada)->where('estatus', '=', '2')->get();

       //dd(count($cuotas));


        foreach($cuotas as $c){

            echo $c->id.' / ';

            $c->update([
                'estatus'=>0,
                'color'=>'ligth'
            ]);

        }

        $cuotas = ContratoDetalle::select('contratos_detalle.*')->join('contratos', 'contratos.id', '=', 'contratos_detalle.contrato_id')
        ->where('contratos.estatus', '=', 'desactivado')
        ->where('contratos_detalle.estatus', '=', '2')
        ->get();

        foreach($cuotas as $c){

            echo $c->id.' / ';

            $c->update([
                'estatus'=>0,
                'color'=>'ligth'
            ]);

        }


    }
        
}

    
    
