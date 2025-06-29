<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BettingSlip;
use App\Models\Game;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BettingSlipController extends Controller
{
    public function index()
    {
        $bettingSlips = BettingSlip::where('user_id', Auth::id())
            ->with(['game', 'group'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('betting-slips.index', compact('bettingSlips'));
    }

    public function create()
    {
        $groups = Group::where('user_id', Auth::id())->get();
        $games = Game::all();
        
        return view('betting-slips.create', compact('groups', 'games'));
    }

    public function createForGame(Game $game)
    {
        $groups = Group::where('user_id', Auth::id())->get();
        
        return view('betting-slips.create-for-game', compact('game', 'groups'));
    }

    public function store(Request $request, Group $group)
    {
        $request->validate([
            'bet_amount' => 'required|numeric|min:0.01',
            'numbers' => 'required|array|min:1',
            'numbers.*' => 'integer|min:1|max:90',
        ]);

        $bettingSlip = BettingSlip::create([
            'user_id' => Auth::id(),
            'group_id' => $group->id,
            'bet_amount' => $request->bet_amount,
            'numbers' => $request->numbers,
            'virtual_amount' => $request->bet_amount,
        ]);

        return redirect()->route('betting-slips.show', $bettingSlip)
            ->with('success', 'Aposta criada com sucesso!');
    }

    public function storeForGame(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'bet_amount' => 'required|numeric|min:0.01',
            'numbers' => 'required|array|min:1',
            'numbers.*' => 'integer|min:1|max:90',
        ]);

        $bettingSlip = BettingSlip::create([
            'user_id' => Auth::id(),
            'game_id' => $request->game_id,
            'bet_amount' => $request->bet_amount,
            'numbers' => $request->numbers,
            'virtual_amount' => $request->bet_amount,
        ]);

        return redirect()->route('betting-slips.show', $bettingSlip)
            ->with('success', 'Aposta criada com sucesso!');
    }

    public function show(BettingSlip $bettingSlip)
    {
        $this->authorize('view', $bettingSlip);
        
        return view('betting-slips.show', compact('bettingSlip'));
    }

    public function checkResults(BettingSlip $bettingSlip)
    {
        $this->authorize('view', $bettingSlip);
        
        // Lógica para verificar resultados
        // Implementar conforme necessário
        
        return back()->with('success', 'Resultados verificados!');
    }

    public function generateSystemCombinations(Request $request)
    {
        $request->validate([
            'numbers' => 'required|array|min:6|max:15',
            'numbers.*' => 'integer|min:1|max=90',
        ]);

        $numbers = $request->numbers;
        $combinations = $this->generateCombinations($numbers, 6);
        
        return response()->json(['combinations' => $combinations]);
    }

    public function claim(BettingSlip $bettingSlip)
    {
        $this->authorize('claim', $bettingSlip);
        
        if ($bettingSlip->is_claimed) {
            return back()->with('error', 'Esta aposta já foi reclamada!');
        }
        
        if (!$bettingSlip->has_won) {
            return back()->with('error', 'Esta aposta não foi vencedora!');
        }
        
        DB::transaction(function () use ($bettingSlip) {
            $bettingSlip->update(['is_claimed' => true]);
            
            $user = Auth::user();
            $user->virtual_balance += $bettingSlip->prize_amount;
            $user->save();
        });
        
        return back()->with('success', 'Prémio reclamado com sucesso!');
    }

    public function getLeagueMatches($league)
    {
        // Implementar busca de jogos por liga
        // Retornar dados em formato JSON
        return response()->json(['matches' => []]);
    }

    private function generateCombinations($numbers, $size)
    {
        if ($size == 0) {
            return [[]];
        }
        
        if (empty($numbers)) {
            return [];
        }
        
        $combinations = [];
        $first = array_shift($numbers);
        
        // Combinações que incluem o primeiro número
        $subCombinations = $this->generateCombinations($numbers, $size - 1);
        foreach ($subCombinations as $subCombination) {
            $combinations[] = array_merge([$first], $subCombination);
        }
        
        // Combinações que não incluem o primeiro número
        $combinations = array_merge($combinations, $this->generateCombinations($numbers, $size));
        
        return $combinations;
    }
}
