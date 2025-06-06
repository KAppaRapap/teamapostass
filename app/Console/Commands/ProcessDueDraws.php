<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Draw;
use Carbon\Carbon;

class ProcessDueDraws extends Command
{
    protected $signature = 'draws:process-due';
    protected $description = 'Process draws that are past their result time and generate winning numbers.';

    public function handle()
    {
        $this->info("Verificando sorteios para processar...");
        // Sorteios cuja hora de resultado (draw_date + 1h) já passou e não estão completos
        $drawsToProcess = Draw::where('is_completed', false)
                                ->where('draw_date', '<', Carbon::now()->subHour())
                                ->get();

        if ($drawsToProcess->isEmpty()) {
            $this->info("Nenhum sorteio para processar.");
            return Command::SUCCESS;
        }

        foreach ($drawsToProcess as $draw) {
            $this->info("Processando sorteio ID: {$draw->id} para o jogo {$draw->game->name} de {$draw->draw_date->toDateTimeString()}");
            $draw->processIfDue(); // Este método agora gera os números e salva
            $this->info("Sorteio ID: {$draw->id} processado. Resultados: " . json_encode($draw->winning_numbers));
        }

        $this->info("Processamento de sorteios concluído.");
        return Command::SUCCESS;
    }
} 