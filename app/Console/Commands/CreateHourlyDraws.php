<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use App\Models\Draw;
use Carbon\Carbon;

class CreateHourlyDraws extends Command
{
    protected $signature = 'draws:create-hourly';
    protected $description = 'Ensure each game has one upcoming draw, creating one for the next hour if needed.';

    public function handle()
    {
        $games = Game::all();
        $now = Carbon::now();

        foreach ($games as $game) {
            // Verifica se já existe um sorteio futuro NÃO COMPLETADO para este jogo
            $upcomingDraw = Draw::where('game_id', $game->id)
                                ->where('is_completed', false)
                                ->where('draw_date', '>', $now)
                                ->orderBy('draw_date', 'asc') // Pega o mais próximo, caso haja algum erro e tenha mais de um
                                ->first();

            if (!$upcomingDraw) {
                // Não há sorteio futuro agendado e não completado, então cria um para a próxima hora cheia
                $nextHourDrawDate = Carbon::now()->addHour()->startOfHour();
                
                // Adicionalmente, certifica-se de que não estamos tentando criar um sorteio para uma hora que já passou (edge case)
                if ($nextHourDrawDate->isPast()) {
                    $nextHourDrawDate = Carbon::now()->addHours(2)->startOfHour(); // Se a próxima hora já passou, pula para a seguinte
                }

                Draw::create([
                    'game_id' => $game->id,
                    'draw_date' => $nextHourDrawDate,
                    'is_completed' => false,
                ]);
                $this->info("Novo sorteio futuro criado para {$game->name} às {$nextHourDrawDate->toDateTimeString()}.");
            } else {
                $this->line("Jogo {$game->name} já possui um sorteio futuro agendado para {$upcomingDraw->draw_date->toDateTimeString()}.");
            }
        }
        return Command::SUCCESS;
    }
} 