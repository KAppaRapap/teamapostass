<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use App\Models\Draw;
use App\Models\Notification;
use App\Models\Group;
use App\Models\BettingSlip;
use Carbon\Carbon;

class AutoCycleDraws extends Command
{
    protected $signature = 'draws:autocycle';
    protected $description = 'Gera e encerra automaticamente sorteios de 4 jogos automáticos de hora a hora';

    // IDs dos 4 jogos automáticos (ajuste conforme necessário)
    protected $autoGameIds = [1, 2, 3, 4];

    public function handle()
    {
        foreach ($this->autoGameIds as $gameId) {
            $game = Game::find($gameId);
            if (!$game) continue;

            // Definição dos jackpots iniciais (ajuste conforme necessário)
            $jackpotIniciais = [
                1 => 500000, // Euromilhões
                2 => 100000, // Totoloto
                3 => 50000,  // Totobola
                4 => 10000,  // Placard
            ];

            $lastDraw = $game->draws()->orderByDesc('draw_date')->first();
            $now = Carbon::now();

            if (!$lastDraw || ($lastDraw->is_completed && $lastDraw->draw_date <= $now)) {
                // Jackpot aleatório: 20% chance de zero, senão 50%-200% do valor inicial
                $initial = $jackpotIniciais[$game->id] ?? 10000;
                if (rand(1, 100) <= 20) {
                    $jackpot = 0;
                } else {
                    $min = intval($initial * 0.5);
                    $max = intval($initial * 2);
                    $jackpot = rand($min, $max);
                }
                // Cria novo sorteio
                $newDraw = new Draw();
                $newDraw->game_id = $game->id;
                $newDraw->draw_number = ($lastDraw ? ($lastDraw->draw_number + 1) : 1);
                $newDraw->draw_date = $now->copy()->addHour();
                $newDraw->jackpot_amount = $jackpot;
                $newDraw->is_completed = false;
                $newDraw->save();
                $this->info("Novo sorteio criado para o jogo {$game->name} (ID: {$game->id})");
            } elseif (!$lastDraw->is_completed && $lastDraw->draw_date <= $now) {
                // Termina o sorteio atual
                $lastDraw->is_completed = true;
                // Gerar números sorteados automaticamente (exemplo: 6 de 1 a 49)
                $winningNumbers = collect(range(1, 49))->shuffle()->take(6)->sort()->values()->all();
                $lastDraw->winning_numbers = $winningNumbers;
                $lastDraw->save();
                $this->info("Sorteio encerrado para o jogo {$game->name} (ID: {$game->id}) com resultado: " . implode(', ', $winningNumbers));

                // Atualiza cada aposta com resultados e marca como verificado
                $allSlips = BettingSlip::where('draw_id', $lastDraw->id)->get();
                foreach ($allSlips as $slip) {
                    $matchingCount = count(array_intersect($winningNumbers, $slip->numbers));
                    switch ($matchingCount) {
                        case 3: $prize = 5.00; break;
                        case 4: $prize = 50.00; break;
                        case 5: $prize = 500.00; break;
                        case 6: $prize = 5000.00; break;
                        default: $prize = 0.00; break;
                    }
                    $slip->update([
                        'winnings' => $prize,
                        'has_won' => $prize > 0,
                        'is_checked' => true,
                    ]);
                }

                // Verifica apostas vencedoras e envia notificações para os grupos
                $groups = Group::where('game_id', $game->id)->get();
                foreach ($groups as $group) {
                    $bettingSlips = BettingSlip::where('group_id', $group->id)
                        ->where('draw_id', $lastDraw->id)
                        ->get();
                    $winners = [];
                    foreach ($bettingSlips as $bettingSlip) {
                        if (is_array($bettingSlip->numbers) && is_array($winningNumbers)
                            && count($bettingSlip->numbers) === count($winningNumbers)
                            && empty(array_diff($bettingSlip->numbers, $winningNumbers))) {
                            $winners[] = $bettingSlip->user;
                        }
                    }
                    // Notifica o grupo sobre o resultado
                    $winnerNames = count($winners) ? implode(', ', array_map(fn($u) => $u->name, $winners)) : 'Nenhum vencedor';
                    foreach ($group->activeMembers as $member) {
                        Notification::create([
                            'user_id' => $member->id,
                            'type' => 'draw_result',
                            'title' => 'Resultado do Sorteio',
                            'message' => "O sorteio do grupo '{$group->name}' foi realizado. Números: " . implode(', ', $winningNumbers) . ". Vencedores: $winnerNames.",
                            'data' => json_encode([
                                'group_id' => $group->id,
                                'draw_id' => $lastDraw->id,
                                'winning_numbers' => $winningNumbers,
                                'winners' => array_map(fn($u) => $u->id, $winners),
                            ]),
                            'is_read' => false,
                        ]);
                    }
                }
            }
        }
    }
}
