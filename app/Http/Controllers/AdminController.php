<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create default games in the system.
     *
     * @return \Illuminate\Http\Response
     */
    public function createGames() //sigma
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

        // Limpar jogos existentes para evitar duplicações
        Game::truncate();

        // Criar os jogos
        foreach ($games as $game) {
            Game::create($game);
        }

        return redirect()->route('admin.show-create-games')
            ->with('success', 'Jogos adicionados com sucesso! Agora você pode criar grupos.');
    }

    /**
     * Show the create games page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreateGames()
    {
        return view('admin.create-games');
    }

    /**
     * List all users for admin management.
     */
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to edit user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user attributes.
     */
    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->is_admin = $request->has('is_admin');
        $user->is_banned = $request->has('is_banned');
        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
