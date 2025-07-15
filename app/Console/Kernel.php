<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\NotificarCliente::class,
        Commands\VerificarCliente::class,
        Commands\VerificarContratos::class,
        Commands\VerificarCuotas::class,
        Commands\VerificarCuotasMoroso::class,
    ];


    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:notificar-cliente')->dailyAt('09:00'); //envia correo por cuotas vencidas
        $schedule->command('app:verificar-cliente')->dailyAt('21:00'); //cambia estado de clientes 
        $schedule->command('app:verificar-contratos')->dailyAt('20:00'); //cambia contratos a listado de compras
        $schedule->command('app:verificar-cuotas')->dailyAt('05:00'); //cambia cuaotas a vencidas 
        $schedule->command('app:verificar-cuotas-moroso')->dailyAt('06:00'); //agrega multa a cuotas vencidas 
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
