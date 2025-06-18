<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Draw;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Adicionado para debugging

class DrawSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = Game::all();
        $now = Carbon::now();

        foreach ($games as $game) {
            Log::info("Seeder - Processando jogo para sorteio inicial: {$game->name}");

            // Verifica se já existe um sorteio futuro NÃO COMPLETADO para este jogo
            $upcomingDraw = Draw::where('game_id', $game->id)
                                ->where('is_completed', false)
                                ->where('draw_date', '>', $now)
                                ->first();

            if (!$upcomingDraw) {
                // Cria UM sorteio para a próxima hora cheia
                $nextHourDrawDate = Carbon::now()->addHour()->startOfHour();
                
                // Adicionalmente, certifica-se de que não estamos tentando criar um sorteio para uma hora que já passou
                if ($nextHourDrawDate->isPast()) {
                    $nextHourDrawDate = Carbon::now()->addHours(2)->startOfHour(); // Pula para a seguinte
                }

                Draw::create([
                    'game_id' => $game->id,
                    'draw_date' => $nextHourDrawDate,
                    'is_completed' => false,
                ]);
                Log::info("Seeder - Sorteio futuro inicial criado para {$game->name} às {$nextHourDrawDate->toDateTimeString()}.");
            } else {
                Log::info("Seeder - Jogo {$game->name} já possui sorteio futuro: {$upcomingDraw->draw_date->toDateTimeString()}.");
            }

            // OPCIONAL: Criar alguns sorteios PASSADOS com resultados para histórico/teste
            // Vamos criar 2 sorteios passados para cada jogo, se não existirem muitos sorteios passados.
            $pastDrawsCount = Draw::where('game_id', $game->id)->where('is_completed', true)->count();
            
            if ($pastDrawsCount < 2) { // Só cria se tiver menos de 2 sorteios passados
                for ($i = 1; $i <= 2; $i++) {
                    $pastDrawDate = Carbon::now()->subHours($i * 3); // Ex: 3h atrás, 6h atrás
                    $existingPastDraw = Draw::where('game_id', $game->id)
                                        ->where('draw_date', $pastDrawDate)
                                        ->first();
                    if (!$existingPastDraw) {
                        $winningNumbers = $this->generateWinningNumbers($game);
                        Draw::create([
                            'game_id' => $game->id,
                            'draw_date' => $pastDrawDate,
                            'winning_numbers' => $winningNumbers,
                            'is_completed' => true,
                        ]);
                        Log::info("Seeder - Sorteio PASSADO com resultado criado para {$game->name} às {$pastDrawDate->toDateTimeString()}.");
                    }
                }
            }
        }
    }

    /**
     * Gera números vencedores com base no tipo de jogo.
     *
     * @param Game $game
     * @return array|null
     */
    private function generateWinningNumbers(Game $game)
    {
        switch ($game->type) { // Usando o campo 'type' como sugerido na descrição do jogo
            case 'Euromilhões':
                $numbers = collect(range(1, 50))->shuffle()->take(5)->sort()->values()->all();
                $stars = collect(range(1, 12))->shuffle()->take(2)->sort()->values()->all();
                return ['numbers' => $numbers, 'stars' => $stars];
            case 'Totoloto':
                return collect(range(1, 49))->shuffle()->take(6)->sort()->values()->all();
            case 'Totobola':
                $results = [];
                for ($j = 0; $j < 13; $j++) {
                    $results[] = collect(['1', 'X', '2'])->random();
                }
                return $results;
            case 'Placard':
                // Para o Placard, podemos retornar um resultado genérico ou uma estrutura vazia
                // já que a lógica de resultados é mais complexa e baseada em eventos reais.
                return ['message' => 'Resultados do Placard são baseados em eventos reais.'];
            default:
                Log::warning("Tipo de jogo desconhecido para geração de resultados: {$game->type}");
                return null;
        }
    }
} 