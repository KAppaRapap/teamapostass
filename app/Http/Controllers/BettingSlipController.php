<?php

namespace App\Http\Controllers;

use App\Models\BettingSlip;
use App\Models\Group;
use App\Models\Draw;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BettingSlipController extends Controller
{
    /**
     * Display a listing of the user's betting slips.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all groups the user is a member of
        $groups = $user->groups()->with(['bettingSlips' => function($query) {
            $query->with('draw')->orderBy('created_at', 'desc');
        }])->get();
        
        // Flatten the betting slips from all groups
        $bettingSlips = $groups->pluck('bettingSlips')->flatten();
        
        // Split into active and past betting slips
        $activeBettingSlips = $bettingSlips->filter(function($slip) {
            return !$slip->draw->is_completed;
        });
        
        $pastBettingSlips = $bettingSlips->filter(function($slip) {
            return $slip->draw->is_completed;
        });
        
        return view('betting-slips.index', compact('activeBettingSlips', 'pastBettingSlips'));
    }
    /**
     * Show the form for creating a new betting slip.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function create(Group $group)
    {
        if (!$group->members()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('groups.index')
                ->with('error', 'Você não é membro deste grupo.');
        }

        return view('betting-slips.create', compact('group'));
    }

    /**
     * Store a newly created betting slip in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        $user = auth()->user();

        try {
            $draw = Draw::findOrFail($request->draw_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            dd("Erro: Sorteio não encontrado para o ID: " . $request->draw_id);
        }

        // Verificar se o usuário é membro do grupo
        if (!$group->isMember($user)) {
            return redirect()->back()->with('error', 'Você não é membro deste grupo.');
        }

        // Validar a aposta
        $validator = $this->validateBet($request, $draw->game);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Calcular o custo total
        $totalCost = $this->calculateTotalCost($request, $draw->game);

        // Verificar saldo
        if ($user->virtual_balance < $totalCost) {
            return redirect()->back()->with('error', 'Saldo insuficiente para realizar a aposta.');
        }

        // Criar a aposta
        $bettingSlip = new BettingSlip([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'draw_id' => $draw->id,
            'numbers' => $request->numbers ?? [],
            'stars' => $request->stars ?? [],
            'predictions' => $request->predictions ?? [],
            'bet_type' => $request->bet_type ?? 'single',
            'system_details' => $request->system_details ?? null,
            'total_cost' => $totalCost,
            'status' => 'pending'
        ]);

        // Validar a aposta antes de salvar
        if (!$bettingSlip->validateBet()) {
            return redirect()->back()->with('error', $bettingSlip->validation_errors)->withInput();
        }

        // Atualizar o saldo do usuário
        $user->updateBalance($totalCost, 'debit');

        // Salvar a aposta
        $bettingSlip->save();

        return redirect()->route('betting-slips.show', $bettingSlip)
            ->with('success', 'Aposta realizada com sucesso!');
    }

    /**
     * Display the specified betting slip.
     *
     * @param  \App\Models\BettingSlip  $bettingSlip
     * @return \Illuminate\Http\Response
     */
    public function show(BettingSlip $bettingSlip)
    {
        if ($bettingSlip->user_id !== Auth::id() && 
            (!$bettingSlip->group || $bettingSlip->group->admin_id !== Auth::id())) {
            return redirect()->route('groups.index')
                ->with('error', 'Você não tem permissão para ver esta aposta.');
        }

        return view('betting-slips.show', compact('bettingSlip'));
    }

    /**
     * Generate system bet combinations (desdobramentos).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateSystemCombinations(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'numbers' => 'required|array',
            'numbers.*' => 'required|integer|min:1',
            'system_type' => 'required|string',
        ]);
        
        $game = Game::findOrFail($validated['game_id']);
        $numbers = $validated['numbers'];
        $systemType = $validated['system_type'];
        
        // Generate combinations based on game type and system type
        $combinations = $this->generateCombinations($game, $numbers, $systemType);
        $totalCost = $this->calculateSystemCost($game, $combinations);
        
        return response()->json([
            'combinations' => $combinations,
            'total_cost' => $totalCost,
            'combinations_count' => count($combinations),
        ]);
    }
    
    /**
     * Check betting slip results.
     *
     * @param  \App\Models\BettingSlip  $bettingSlip
     * @return \Illuminate\Http\Response
     */
    public function checkResults(Request $request, BettingSlip $bettingSlip)
    {
        // Verificar se o usuário é o administrador do grupo
        if (!$bettingSlip->group->isAdmin(auth()->user())) {
            return redirect()->back()->with('error', 'Apenas o administrador do grupo pode verificar os resultados.');
        }

        // Verificar se o sorteio foi concluído
        if (!$bettingSlip->draw->is_completed) {
            return redirect()->back()->with('error', 'O sorteio ainda não foi concluído.');
        }

        // Verificar se os resultados já foram verificados
        if ($bettingSlip->status !== 'pending') {
            return redirect()->back()->with('error', 'Os resultados já foram verificados.');
        }

        // Calcular o prêmio
        $prize = $bettingSlip->calculatePrize();

        // Atualizar a aposta
        $bettingSlip->update([
            'status' => $prize > 0 ? 'won' : 'lost',
            'prize_amount' => $prize
        ]);

        // Se ganhou, atualizar o saldo do usuário
        if ($prize > 0) {
            $bettingSlip->user->updateBalance($prize, 'credit');
        }

        return redirect()->back()->with('success', 'Resultados verificados com sucesso!');
    }

    /**
     * Handle the claim of a betting slip prize.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BettingSlip  $bettingSlip
     * @return \Illuminate\Http\Response
     */
    public function claim(Request $request, BettingSlip $bettingSlip)
    {
        if ($bettingSlip->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para reivindicar este prêmio.');
        }

        if ($bettingSlip->status !== 'won') {
            return redirect()->back()->with('error', 'Esta aposta não foi vencedora ou o prêmio já foi reivindicado.');
        }

        if ($bettingSlip->prize_amount <= 0) {
            return redirect()->back()->with('error', 'Não há prêmio para reivindicar para esta aposta.');
        }

        // Atualizar o saldo do usuário
        $bettingSlip->user->updateBalance($bettingSlip->prize_amount, 'credit');

        // Marcar a aposta como reivindicada (assumindo que você adiciona uma coluna is_claimed na tabela betting_slips)
        $bettingSlip->is_claimed = true;
        $bettingSlip->save();

        return redirect()->back()->with('success', 'Prêmio reivindicado com sucesso!');
    }

    /**
     * Validate betting slip data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateBet(Request $request, Game $game)
    {
        $rules = [
            'draw_id' => 'required|exists:draws,id',
            'group_id' => 'required|exists:groups,id',
            'bet_type' => 'required|in:single,system',
            'custom_amount' => 'required|numeric|min:0.01',
        ];

        if ($game->name === 'Totobola') {
            $rules['predictions'] = 'required|array|size:13';
            $rules['predictions.*'] = 'required|in:1,X,2';
        } else {
            // Para Totoloto/Euromilhões
            $rules['numbers'] = ['required', 'array'];
            $rules['numbers.*'] = 'required|integer|min:1';

            if ($game->name === 'Euromilhões') {
                $rules['numbers'][] = 'size:5'; // 5 números
                $rules['stars'] = ['required', 'array', 'size:2']; // 2 estrelas
                $rules['stars.*'] = 'required|integer|min:1';
            } else { // Totoloto
                $rules['numbers'][] = 'size:5'; // 5 números
            }
        }

        if ($request->input('bet_type') === 'system') {
            $rules['system_details.type'] = 'required|in:full,partial';
            // Adicione mais regras de validação para system_details se necessário
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Calculate the total cost of the bet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return float
     */
    private function calculateTotalCost(Request $request, Game $game)
    {
        $totalCost = 0;
        $basePrice = $game->price_per_bet;

        if ($request->input('bet_type') === 'system') {
            $numbers = $request->input('numbers', []);
            $stars = $request->input('stars', []);
            $systemType = $request->input('system_details.type');

            $combinations = $this->generateCombinations($game, $numbers, $systemType);
            $totalCost = count($combinations) * $basePrice;
        } else {
            $totalCost = $basePrice;
        }

        // Se houver um valor customizado, usá-lo, desde que seja maior que 0 e atenda ao mínimo
        $customAmount = (float) $request->input('custom_amount', 0);
        if ($customAmount > 0 && $customAmount >= $totalCost) {
            $totalCost = $customAmount;
        }
        
        return $totalCost;
    }

    /**
     * Generate system bet combinations (desdobramentos).
     *
     * @param  \App\Models\Game  $game
     * @param  array  $numbers
     * @param  string  $systemType
     * @return array
     */
    private function generateCombinations(Game $game, array $numbers, string $systemType)
    {
        $combinations = [];
        if ($game->name === 'Euromilhões') {
            $mainNumbers = array_slice($numbers, 0, 5);
            $stars = array_slice($numbers, 5); // Assumindo que as estrelas vêm depois dos números principais

            if ($systemType === 'full') {
                $combinations = $this->generateFullSystemCombinations($mainNumbers, $stars);
            } else if ($systemType === 'partial') {
                $combinations = $this->generatePartialSystemCombinations($mainNumbers, $stars, $systemType);
            }
        } else { // Totoloto
            if ($systemType === 'full') {
                $combinations = $this->getCombinations($numbers, 5); // Assumindo 5 números para Totoloto
            }
        }
        return $combinations;
    }

    /**
     * Generate full system combinations for Euromilhões (5 main numbers + stars).
     *
     * @param array $mainNumbers
     * @param array $stars
     * @return array
     */
    private function generateFullSystemCombinations(array $mainNumbers, array $stars)
    {
        $combinations = [];
        $mainCombinations = $this->getCombinations($mainNumbers, 5);

        foreach ($mainCombinations as $mCombo) {
            foreach ($this->getCombinations($stars, 2) as $sCombo) {
                $combinations[] = [
                    'numbers' => $mCombo,
                    'stars' => $sCombo
                ];
            }
        }
        return $combinations;
    }

    /**
     * Generate partial system combinations (implement based on specific rules).
     *
     * @param array $mainNumbers
     * @param array $stars
     * @param string $systemType
     * @return array
     */
    private function generatePartialSystemCombinations(array $mainNumbers, array $stars, string $systemType)
    {
        // Implemente a lógica para sistemas parciais com base nas suas regras específicas
        // Por enquanto, retorna um array vazio ou uma única combinação padrão
        return [];
    }

    /**
     * Get all combinations of k elements from a set.
     *
     * @param array $items
     * @param int $k
     * @return array
     */
    private function getCombinations(array $items, int $k)
    {
        if ($k == 0) {
            return [[]];
        }

        if (empty($items)) {
            return [];
        }

        $head = array_shift($items);
        $combinations = [];

        // Combinations including the head
        foreach ($this->getCombinations($items, $k - 1) as $combo) {
            array_unshift($combo, $head);
            $combinations[] = $combo;
        }

        // Combinations not including the head
        foreach ($this->getCombinations($items, $k) as $combo) {
            $combinations[] = $combo;
        }

        return $combinations;
    }

    /**
     * Calculate the cost for system bets.
     *
     * @param  \App\Models\Game  $game
     * @param  array  $combinations
     * @return float
     */
    private function calculateSystemCost(Game $game, array $combinations)
    {
        return count($combinations) * $game->price_per_bet;
    }

    /**
     * Get league matches for Totobola.
     *
     * @param  string  $league
     * @return \Illuminate\Http\Response
     */
    public function getLeagueMatches($league)
    {
        // Esta função precisaria de uma lógica real para buscar jogos de uma liga
        // Por enquanto, retorna um mock de dados
        return response()->json([
            ['id' => 1, 'home_team' => 'Benfica', 'away_team' => 'Porto', 'date' => '2024-06-20', 'time' => '20:00'],
            ['id' => 2, 'home_team' => 'Sporting', 'away_team' => 'Braga', 'date' => '2024-06-20', 'time' => '20:30'],
        ]);
    }
}
