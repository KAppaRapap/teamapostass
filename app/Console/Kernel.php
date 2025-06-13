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

        // Gerar novos sorteios de hora em hora para todos os jogos
        $schedule->command('draws:generate-hourly')->hourly();

        // Sorteios automáticos de 4 jogos de hora em hora (removido para evitar conflito com o novo comando)
        // $schedule->command('draws:autocycle')->hourly();
        // $schedule->command('draws:autocycle')->everyMinute();
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
