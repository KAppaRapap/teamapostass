<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        // Gerar notificações aleatórias a cada 15 minutos
        $schedule->command('notifications:generate-random')->everyFifteenMinutes();

        // Para criar novos sorteios a cada hora, no início da hora (ex: 10:00, 11:00)
        // $schedule->command('draws:create-hourly')->hourly()->at('00'); 
        // OU, se quiser que rode um pouco depois do início da hora para garantir que a hora já virou completamente:
        // $schedule->command('draws:create-hourly')->hourly()->at('01'); // Ex: 10:01, 11:01

        // Para processar sorteios vencidos (gerar resultados)
        // Pode rodar com mais frequência, por exemplo, a cada minuto ou a cada 5 minutos.
        // $schedule->command('draws:process-due')->everyMinute(); 
        // Ou a cada 5 minutos:
        // $schedule->command('draws:process-due')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
