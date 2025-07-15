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

class RecargaCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recarga-cliente';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rellenar campo cliente en auditoria';

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
       
        $auditoria = Auditoria::get();

        foreach($auditoria as $a){

            if($a->cliente_id==null){

                if($a->type=='abono'){

                    $s = Abono::where('id', $a->type_id)->first();

                    if($s){
                        $a->update(['cliente_id'=>$s->cliente_id]);

                    }

                }


                if($a->type=='saldo'){

                    $s = Saldo::where('id', $a->type_id)->first();

                    if($s){
                        $a->update(['cliente_id'=>$s->cliente_id]);

                    }

                }

                if($a->type=='contrato'){

                    $c = Contrato::where('id', $a->type_id)->first();

                    if($c){
                        $a->update(['cliente_id'=>$c->cliente_id]);

                    }

                }

                if($a->type=='pago'){

                    $c = Pagos::where('id', $a->type_id)->first();

                    if($c){
                        $a->update(['cliente_id'=>$c->cliente_id]);

                    }

                }

                if($a->type=='cuota'){

                    $d = ContratoDetalle::where('id', $a->type_id)->first();

                    if($d){
                        $c = Contrato::where('id', $d->contrato_id)->first();
                        if($c){
                            $a->update(['cliente_id'=>$c->cliente_id]);
    
                        }
                    }

                    

                }

                if($a->type=='cliente'){
                    $a->update(['cliente_id'=>$a->type_id]);
                }


            }

        }
        
    }

    
    
}
