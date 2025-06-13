<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Game;
use App\Models\Sorteio;

class SorteioCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sorteio:executar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa o sorteio automático a cada hora';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $horaAtual = date('H:i:s');
        
        $this->info('Iniciando sorteio...');
        Log::info('Sorteio iniciado às ' . $horaAtual);

        $totobolaGame = Game::where('name', 'Totobola')->first();

        if ($totobolaGame) {
            Sorteio::create([
                'game_id' => $totobolaGame->id,
                'date' => now()->toDateString(),
                'jackpot_amount' => 0.00, // Valor inicial do jackpot
                'groups_count' => 0, // Número inicial de grupos
            ]);

            $this->info('Sorteio de Totobola criado com sucesso!');
            Log::info('Sorteio de Totobola criado para a data ' . now()->toDateString());

        } else {
            $this->error('Jogo Totobola não encontrado. Certifique-se de que os jogos padrão foram semeados.');
            Log::error('Erro: Jogo Totobola não encontrado ao tentar criar sorteio.');
            return 1;
        }
        
        $this->info('Sorteio concluído com sucesso!');
        Log::info('Sorteio concluído às ' . $horaAtual);
        
        return 0;
    }
}
