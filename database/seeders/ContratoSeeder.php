<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contrato;
use App\Models\ContratoTipo;
use App\Models\ContratoCategoria;
class ContratoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        ContratoTipo::create([
             'nombre' => 'Normal',
             'descripcion' => 'Normal',
             'cuotas'=>12,
             'porcentaje'=>9.7,
             'creado_por'=>1
        ]);

        ContratoTipo::create([
            'nombre' => 'Rappi Plus',
            'descripcion' => 'Rappi Plus',
            'cuotas'=>7,
            'porcentaje'=>17.15,
            'creado_por'=>1
       ]);

       ContratoCategoria::create([
        'nombre' => 'Start',
        'monto' => '350',
        'creado_por'=>1
       ]);

       ContratoCategoria::create([
        'nombre' => 'Avance',
        'monto' => '500',
        'creado_por'=>1
       ]);

       ContratoCategoria::create([
        'nombre' => 'Plus',
        'monto' => '1000',
        'creado_por'=>1
       ]);

       ContratoCategoria::create([
        'nombre' => 'Personalizado',
        'monto' => '0',
        'creado_por'=>1
       ]);
    }
}
