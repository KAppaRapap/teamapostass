<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use App\Models\Draw;
use Carbon\Carbon;

class GenerateHourlyDraws extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'draws:generate-hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new draws for all games every hour.';

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
        $nextHour = Carbon::now()->addHour()->startOfHour();

        $games = Game::all();

        if ($games->isEmpty()) {
            $this->info('No games found. Creating a default "Totoloto" game.');
            $game = Game::create([
                'name' => 'Totoloto',
                'type' => 'Lottery',
                'description' => 'O Totoloto é um jogo de sorteio baseado em números. Escolha 5 números de 1 a 49 e 1 número suplementar.',
                'rules' => 'Para ganhar o primeiro prêmio, você deve acertar os 5 números principais e o número suplementar. Existem vários prêmios menores para acertos parciais.',
                'price_per_bet' => 0.75,
                'image_url' => 'default_game_logo.png',
            ]);
            $games = collect([$game]);
        }

        foreach ($games as $game) {
            $existingDraw = Draw::where('game_id', $game->id)
                                ->where('draw_date', $nextHour)
                                ->first();

            if ($existingDraw) {
                $this->info("Draw for {$game->name} at {$nextHour->format('Y-m-d H:i:s')} already exists. Skipping.");
                continue;
            }

            $draw = Draw::create([
                'game_id' => $game->id,
                'draw_number' => null,
                'draw_date' => $nextHour,
                'jackpot_amount' => 10000.00,
                'winning_numbers' => null,
                'additional_info' => null,
                'is_completed' => false,
            ]);
            $this->info("Generated new draw for {$game->name}: Draw ID {$draw->id} scheduled for {$draw->draw_date->format('Y-m-d H:i:s')}");
        }

        $this->info('Hourly draws generation completed.');

        return 0;
    }
}
