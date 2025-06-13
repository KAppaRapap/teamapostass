<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Illuminate\Http\Request;
use App\Services\FootballApiService;
use Carbon\Carbon;

class FootballMatchController extends Controller
{
    protected $footballApiService;

    public function __construct(FootballApiService $footballApiService)
    {
        $this->footballApiService = $footballApiService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matches = FootballMatch::orderBy('match_date', 'desc')->paginate(20);
        return view('admin.football-matches.index', compact('matches'));
    }

    /**
     * Import matches from the API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $matches = $this->footballApiService->getUpcomingSundayMatches();

        if (empty($matches)) {
            return redirect()->back()->with('error', 'Não foi possível obter jogos da API de Futebol ou não há jogos agendados para o próximo Domingo.');
        }

        $importedCount = 0;

        foreach ($matches as $matchData) {
            $match = FootballMatch::updateOrCreate(
                [
                    'id' => $matchData['id']
                ],
                [
                    'home_team' => $matchData['homeTeam']['name'],
                    'away_team' => $matchData['awayTeam']['name'],
                    'match_date' => Carbon::parse($matchData['utcDate']),
                    'league' => $matchData['competition']['name'],
                    'result' => null
                ]
            );
            if ($match->wasRecentlyCreated) {
                $importedCount++;
            }
        }

        return redirect()->back()->with('success', "$importedCount novos jogos foram importados com sucesso.");
    }
    
    /**
     * Helper to format the result.
     */
    private function formatResult($homeWinner, $awayWinner)
    {
        if ($homeWinner === true) {
            return '1';
        }
        if ($awayWinner === true) {
            return '2';
        }
        // Se ambos são false ou null (empate ou jogo não terminado)
        if ($homeWinner === false && $awayWinner === false) {
             return 'X';
        }
        return null; // Jogo ainda não terminou
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
