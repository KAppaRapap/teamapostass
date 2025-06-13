<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = [
            [
                'name' => 'Euromilhões',
                'type' => 'Euromilhões',
                'description' => 'O Euromilhões é uma loteria europeia onde deve escolher 5 números de 1 a 50 e 2 estrelas de 1 a 12.',
                'rules' => 'Escolha 5 números de 1 a 50 e 2 estrelas de 1 a 12. Os sorteios ocorrem às terças e sextas-feiras.',
                'price_per_bet' => 2.50,
            ],
            [
                'name' => 'Totoloto',
                'type' => 'Totoloto',
                'description' => 'O Totoloto é uma loteria portuguesa onde deve escolher 6 números de 1 a 49.',
                'rules' => 'Escolha 6 números de 1 a 49. Os sorteios ocorrem às quartas e sábados.',
                'price_per_bet' => 1.00,
            ],
            [
                'name' => 'Totobola',
                'type' => 'Totobola',
                'description' => 'O Totobola é um jogo de apostas desportivas baseado em resultados de futebol.',
                'rules' => 'Aposte no resultado de 13 jogos de futebol (1, X ou 2). Os sorteios ocorrem aos domingos.',
                'price_per_bet' => 0.75,
            ],
            [
                'name' => 'Placard',
                'type' => 'Placard',
                'description' => 'O Placard é um jogo de apostas desportivas onde pode apostar em diversos eventos desportivos.',
                'rules' => 'Escolha entre 3 a 10 eventos desportivos e faça a sua aposta. Disponível todos os dias.',
                'price_per_bet' => 1.00,
            ],
        ];

        foreach ($games as $gameData) {
            // Usa 'name' como chave única para verificar se o jogo já existe
            // Se existir, não faz nada. Se não existir, cria com todos os dados de $gameData.
            Game::firstOrCreate(
                ['name' => $gameData['name']], // Atributos para encontrar
                $gameData                      // Atributos para criar/atualizar (se usando updateOrCreate)
            );
        }
    }
}
