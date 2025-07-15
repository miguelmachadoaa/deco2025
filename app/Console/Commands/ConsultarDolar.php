<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ContratoDetallesRepository;
use App\Repositories\ContratoRepository;
use App\Repositories\ClientesRepository;
use DOMDocument;

class ConsultarDolar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consultar-dolar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta de dolar';

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
        $url = 'https://www.bcv.org.ve/tasas-informativas-sistema-bancario';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $html = curl_exec($curl);
        curl_close($curl);
        
        $pos = strpos('div', $html);

        dd($html);
        
    }

    public function get_html($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $html = curl_exec($curl);
        curl_close($curl);
        return $html;
    }

    public function get_classes($html, $element) {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query("//{$element}[@class]");
        $classes = array();
        foreach ($nodes as $node) {
          $classes[] = $node->getAttribute('class');
        }
        return $classes;
      }

    
}
