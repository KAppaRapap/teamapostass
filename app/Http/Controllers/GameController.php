<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the games.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::whereIn('name', ['Dice', 'Bomb Mine', 'Crash', 'Roleta'])->get();
        return view('games.index', compact('games'));
    }

    /**
     * Show the form for creating a new game.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('games.create');
    }

    /**
     * Store a newly created game in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rules' => 'nullable|string',
            'price_per_bet' => 'required|numeric|min:0',
        ]);

        Game::create($validated);

        return redirect()->route('games.index')
            ->with('success', 'Jogo criado com sucesso.');
    }

    /**
     * Display the specified game.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        $groups = $game->groups()->paginate(10);

        return view('games.show', compact('game', 'groups'));
    }

    /**
     * Show the form for editing the specified game.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    /**
     * Update the specified game in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rules' => 'nullable|string',
            'price_per_bet' => 'required|numeric|min:0',
        ]);

        $game->update($validated);

        return redirect()->route('games.index')
            ->with('success', 'Jogo atualizado com sucesso.');
    }

    /**
     * Remove the specified game from storage.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        $game->delete();

        return redirect()->route('games.index')
            ->with('success', 'Jogo excluído com sucesso.');
    }



    public function roletaClassica()
    {
        return view('games.roleta');
    }
}
