<?php

namespace App\Http\Controllers;

use App\Models\BettingSlip;
use App\Models\Group;
use App\Models\Draw;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Check if user is a member of the group
        if (!$group->members()->where('users.id', Auth::id())->exists()) {
            return redirect()->route('groups.show', $group)
                ->with('error', 'Você não é membro deste grupo.');
        }
        // Get upcoming draws for the group's game
        $draws = Draw::where('game_id', $group->game_id)
            ->where('draw_date', '>', now())
            ->where('is_completed', false)
            ->orderBy('draw_date', 'asc')
            ->get();
        if ($draws->isEmpty()) {
            return redirect()->route('groups.show', $group)
                ->with('error', 'Não há sorteios futuros disponíveis para este jogo.');
        }
        $matches = [];
        $leagues = [];
        $selectedLeague = request()->input('league');
        if ($group->game->name === 'Totobola') {
            $service = app(\App\Services\FootballApiService::class);
            $leagues = $service->getLeagues();
            if ($selectedLeague) {
                $rawMatches = $service->getMatchesForLeague($selectedLeague);
                $matches = [];
                foreach ($rawMatches as $match) {
                    $matches[] = $service->getMatchExtendedInfo($match);
                }
            } else {
                $matches = $service->getUpcomingSundayMatches();
            }
        }
        return view('betting-slips.create', compact('group', 'draws', 'matches', 'leagues', 'selectedLeague'));
    }

    /**
     * Show the form for creating a new betting slip for a game (sem grupo, ex: Totobola).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createForGame(Request $request)
    {
        $gameId = $request->input('game_id');
        $game = Game::findOrFail($gameId);
        $group = null;
        $draws = Draw::where('game_id', $gameId)
            ->where('draw_date', '>', now())
            ->where('is_completed', false)
            ->orderBy('draw_date', 'asc')
            ->get();
        $matches = [];
        $leagues = [];
        $selectedLeague = $request->input('league');
        if ($game->name === 'Totobola') {
            $service = app(\App\Services\FootballApiService::class);
            $leagues = $service->getLeagues();
            if ($selectedLeague) {
                $rawMatches = $service->getMatchesForLeague($selectedLeague);
            } else {
                $rawMatches = $service->getUpcomingSundayMatches();
            }
            $matches = [];
            foreach ($rawMatches as $match) {
                $matches[] = $service->getMatchExtendedInfo($match);
            }
        }
        // Permite criar aposta mesmo sem draws (Totobola)
        return view('betting-slips.create', compact('game', 'group', 'draws', 'matches', 'leagues', 'selectedLeague'));
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
        // Check if user is a member of the group
        if (!$group->members()->where('users.id', Auth::id())->exists()) {
            return redirect()->route('groups.show', $group)
                ->with('error', 'Você não é membro deste grupo.');
        }
        
        // Prepare inputs based on game type
        $game = $group->game;
        if ($game->name === 'Totobola') {
            $v = $request->validate([
                'draw_id' => 'required|exists:draws,id',
                'predictions'   => 'required|array|size:13',
                'predictions.*' => 'required|in:1,X,2',
                'custom_amount' => 'required|numeric|min:0.01',
            ]);
            $draw         = Draw::findOrFail($v['draw_id']);
            $numbers      = $v['predictions'];
            $isSystem     = false;
            $systemDetails= null;
            $totalCost    = $v['custom_amount'];
        } else {
            $v = $request->validate([
                'draw_id'        => 'required|exists:draws,id',
                'numbers'        => 'required|array',
                'numbers.*'      => 'required|integer|min:1',
                'is_system'      => 'boolean',
                'system_details' => 'nullable|array',
                'custom_amount'  => 'required|numeric|min:0.01',
            ]);
            $draw          = Draw::findOrFail($v['draw_id']);
            $numbers       = $v['numbers'];
            $isSystem      = $v['is_system'] ?? false;
            $systemDetails = $v['system_details'] ?? null;
            $totalCost     = $this->calculateTotalCost($game, $numbers, $isSystem, $systemDetails);
            // Override custom amount
            if ($isSystem) {
                if ($v['custom_amount'] < $totalCost) {
                    return redirect()->back()->withInput()->with('error', 'O valor a apostar deve ser igual ou maior ao custo estimado do sistema.');
                }
                $totalCost = $v['custom_amount'];
            } else {
                $totalCost = $v['custom_amount'];
            }
        }

        // Valor customizado é sempre obrigatório
        // Saldo do usuário
        $user = Auth::user();
        $saldo = $user->virtual_balance;
        if ($saldo < $totalCost) {
            return redirect()->route('groups.show', $group)
                ->with('error', 'Saldo insuficiente na carteira virtual para realizar esta aposta.');
        }

        // Desconta o saldo
        $user->virtual_balance -= $totalCost;
        $user->save();
        
        // Create the betting slip
        $bettingSlip = BettingSlip::create([
            'group_id'      => $group->id,
            'user_id'       => Auth::id(),
            'draw_id'       => $draw->id,
            'numbers'       => $numbers,
            'is_system'     => $isSystem,
            'system_details'=> $systemDetails,
            'total_cost'    => $totalCost,
            'is_checked'    => false,
        ]);
        
        return redirect()->route('groups.show', $group)
            ->with('success', 'Aposta registrada com sucesso. Valor debitado da carteira virtual.');
    }

    /**
     * Store a newly created betting slip for a game (sem grupo, ex: Totobola).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeForGame(Request $request)
    {
        $user = Auth::user();
        $gameId = $request->input('game_id');
        $game = Game::findOrFail($gameId);
        // Para Totobola, predictions e amounts vêm do form
        if ($game->name === 'Totobola') {
            $request->validate([
                'predictions'   => 'required|array',
                'predictions.*' => 'required|in:1,X,2',
                'amounts'       => 'required|array',
                'amounts.*'     => 'required|numeric|min:0.01',
            ]);
            $predictions = $request->input('predictions');
            $amounts = $request->input('amounts');
            $totalCost = array_sum($amounts);
        } else {
            $v = $request->validate([
                'draw_id'        => 'required|exists:draws,id',
                'numbers'        => 'required|array',
                'numbers.*'      => 'required|integer|min:1',
                'is_system'      => 'boolean',
                'system_details' => 'nullable|array',
                'custom_amount'  => 'required|numeric|min:0.01',
            ]);
            $draw          = Draw::findOrFail($v['draw_id']);
            $numbers       = $v['numbers'];
            $isSystem      = $v['is_system'] ?? false;
            $systemDetails = $v['system_details'] ?? null;
            $totalCost     = $this->calculateTotalCost($game, $numbers, $isSystem, $systemDetails);
            // Override custom amount
            if ($isSystem) {
                if ($v['custom_amount'] < $totalCost) {
                    return redirect()->back()->withInput()->with('error', 'O valor a apostar deve ser igual ou maior ao custo estimado do sistema.');
                }
                $totalCost = $v['custom_amount'];
            } else {
                $totalCost = $v['custom_amount'];
            }
        }

        // Saldo do usuário
        $saldo = $user->virtual_balance;
        if ($saldo < $totalCost) {
            return back()->withInput()->with('error', 'Saldo insuficiente na carteira virtual para realizar esta aposta.');
        }

        // Desconta o saldo
        $user->virtual_balance -= $totalCost;
        $user->save();
        
        // Create the betting slip
        $bettingSlipData = [
            'user_id'       => $user->id,
            'game_id'       => $game->id,
            'total_cost'    => $totalCost,
            'is_checked'    => false,
        ];

        // Adicionar campos específicos de acordo com o tipo de jogo
        if ($game->name === 'Totobola') {
            $bettingSlipData['numbers'] = json_encode($predictions);
            $bettingSlipData['amounts'] = json_encode($amounts);
        } else {
            $bettingSlipData['draw_id'] = $draw->id;
            $bettingSlipData['numbers'] = $numbers;
            $bettingSlipData['is_system'] = $isSystem;
            $bettingSlipData['system_details'] = $systemDetails;
        }
        
        $bettingSlip = BettingSlip::create($bettingSlipData);
        
        return redirect()->route('betting-slips.show', $bettingSlip)
            ->with('success', 'Aposta registrada com sucesso! Valor debitado da carteira virtual.');
    }

    /**
     * Display the specified betting slip.
     *
     * @param  \App\Models\BettingSlip  $bettingSlip
     * @return \Illuminate\Http\Response
     */
    public function show(BettingSlip $bettingSlip)
    {
        // Processa automaticamente o sorteio associado se já passou
        $bettingSlip->draw->processIfDue();
        // Atualiza atributos do bettingSlip após processamento do sorteio
        $bettingSlip->refresh();
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
    public function checkResults(BettingSlip $bettingSlip)
    {
        $group = $bettingSlip->group;
        
        // Check if user is admin of the group
        if ($group->admin_id !== Auth::id()) {
            return redirect()->route('groups.show', $group)
                ->with('error', 'Apenas o administrador do grupo pode verificar os resultados.');
        }
        
        $draw = $bettingSlip->draw;
        
        // Check if draw is completed
        if (!$draw->is_completed) {
            return redirect()->route('betting-slips.show', $bettingSlip)
                ->with('error', 'O sorteio ainda não foi realizado.');
        }
        
        // Check if already checked
        if ($bettingSlip->is_checked) {
            return redirect()->route('betting-slips.show', $bettingSlip)
                ->with('info', 'Esta aposta já foi verificada.');
        }
        
        // Calculate winnings
        $winnings = $this->calculateWinnings($bettingSlip, $draw);
        
        // Update betting slip
        $bettingSlip->update([
            'winnings' => $winnings,
            'is_checked' => true,
            'has_won' => $winnings > 0,
        ]);
        
        return redirect()->route('betting-slips.show', $bettingSlip)
            ->with('success', 'Resultados verificados com sucesso.');
    }
    
    /**
     * Claim/prize a betting slip (admin only)
     */
    public function claim(Request $request, BettingSlip $bettingSlip)
    {
        $user = Auth::user();
        $group = $bettingSlip->group;
        if (!$group || $group->admin_id !== $user->id) {
            return redirect()->back()->with('error', 'Apenas o admin do grupo pode premiar esta aposta.');
        }
        if (!$bettingSlip->isWinner() || !$bettingSlip->draw->is_completed) {
            return redirect()->back()->with('error', 'Apenas apostas vencedoras e sorteios concluídos podem ser premiados.');
        }
        if ($bettingSlip->is_claimed) {
            return redirect()->back()->with('info', 'Este prêmio já foi creditado.');
        }
        // Creditar prêmio ao usuário da aposta
        $winner = $bettingSlip->user;
        $winner->virtual_balance += $bettingSlip->winnings;
        $winner->save();
        $bettingSlip->is_claimed = true;
        $bettingSlip->save();
        return redirect()->back()->with('success', 'Prêmio creditado ao apostador com sucesso!');
    }
    
    /**
     * Calculate total cost of a bet.
     *
     * @param  \App\Models\Game  $game
     * @param  array  $numbers
     * @param  bool  $isSystem
     * @param  array|null  $systemDetails
     * @return float
     */
    private function calculateTotalCost($game, $numbers, $isSystem, $systemDetails)
    {
        if (!$isSystem) {
            // Regular bet
            return $game->price_per_bet;
        }

        // System bet (desdobramento)
        $systemType = $systemDetails['type'] ?? null;
        if (!$systemType) {
            // Em vez de lançar exceção, retorna 0 (ou pode mostrar mensagem user-friendly)
            return 0;
        }
        $combinations = $this->generateCombinations($game, $numbers, $systemType);
        return $this->calculateSystemCost($game, $combinations);
    }
    
    /**
     * Generate combinations for system bet.
     *
     * @param  \App\Models\Game  $game
     * @param  array  $numbers
     * @param  string  $systemType
     * @return array
     */
    private function generateCombinations($game, $numbers, $systemType)
    {
        $combinations = [];
        
        // Different logic based on game type
        switch ($game->type) {
            case 'Euromilhões':
                // For Euromilhões, we need to handle main numbers and stars
                // This is a simplified example
                $mainNumbers = array_slice($numbers, 0, 5);
                $stars = array_slice($numbers, 5);
                
                // Generate combinations based on system type
                if ($systemType === 'full') {
                    // Full system - all possible combinations
                    $combinations = $this->generateFullSystemCombinations($mainNumbers, $stars);
                } else {
                    // Partial system
                    $combinations = $this->generatePartialSystemCombinations($mainNumbers, $stars, $systemType);
                }
                break;
                
            case 'Totoloto':
            case 'Totobola':
            case 'Placard':
                // Simplified for other games
                if ($systemType === 'full') {
                    $combinations = $this->generateFullSystemCombinations($numbers, []);
                } else {
                    $combinations = $this->generatePartialSystemCombinations($numbers, [], $systemType);
                }
                break;
        }
        
        return $combinations;
    }
    
    private function generateFullSystemCombinations($mainNumbers, $stars)
    {
        $combinations = [];
        if (count($mainNumbers) < 5 || count($stars) < 2) {
            return $combinations;
        }
        $mainCombos = $this->getCombinations($mainNumbers, 5);
        $starCombos = $this->getCombinations($stars, 2);
        foreach ($mainCombos as $m) {
            foreach ($starCombos as $s) {
                $combinations[] = ['main' => $m, 'stars' => $s];
            }
        }
        return $combinations;
    }

    private function generatePartialSystemCombinations($mainNumbers, $stars, $systemType)
    {
        $full = $this->generateFullSystemCombinations($mainNumbers, $stars);
        $count = count($full);
        if ($count === 0) {
            return [];
        }
        $half = (int) ceil($count / 2);
        return array_slice($full, 0, $half);
    }

    private function getCombinations($items, $k)
    {
        $results = [];
        $n = count($items);
        if ($k > $n) {
            return $results;
        }
        $indexes = range(0, $k - 1);
        while (true) {
            $combo = [];
            foreach ($indexes as $i) {
                $combo[] = $items[$i];
            }
            $results[] = $combo;
            for ($i = $k - 1; $i >= 0; $i--) {
                if ($indexes[$i] < $i + $n - $k) {
                    break;
                }
            }
            if ($i < 0) {
                break;
            }
            $indexes[$i]++;
            for ($j = $i + 1; $j < $k; $j++) {
                $indexes[$j] = $indexes[$j - 1] + 1;
            }
        }
        return $results;
    }
    
    /**
     * Calculate system cost.
     *
     * @param  \App\Models\Game  $game
     * @param  array  $combinations
     * @return float
     */
    private function calculateSystemCost($game, $combinations)
    {
        return count($combinations) * $game->price_per_bet;
    }
    
    /**
     * Calculate winnings for a betting slip.
     *
     * @param  \App\Models\BettingSlip  $bettingSlip
     * @param  \App\Models\Draw  $draw
     * @return float
     */
    private function calculateWinnings($bettingSlip, $draw)
    {
        // Count matching numbers
        $matchingCount = count(
            array_intersect(
                (array) $draw->winning_numbers,
                (array) $bettingSlip->numbers
            )
        );
        // Prize percentages per match count
        $percentages = [
            1 => 0.01,
            2 => 0.05,
            3 => 0.10,
            4 => 0.20,
            5 => 0.30,
            6 => 1.00,
        ];
        // Calculate prize as percentage of jackpot
        if (isset($percentages[$matchingCount])) {
            return round($draw->jackpot_amount * $percentages[$matchingCount], 2);
        }
        return 0.00;
    }

    /**
     * AJAX: Get matches for a selected league (Totobola) with extended info
     * @param string $league
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeagueMatches($league)
    {
        $service = app(\App\Services\FootballApiService::class);
        $matches = $service->getMatchesForLeague($league);
        $extended = [];
        foreach (array_slice($matches, 0, 13) as $match) {
            $info = $service->getMatchExtendedInfo($match);
            $info['match_id'] = $match['id'] ?? null;
            $info['homeTeam'] = $match['homeTeam']['name'] ?? '?';
            $info['awayTeam'] = $match['awayTeam']['name'] ?? '?';
            $extended[] = $info;
        }
        return response()->json(['matches' => $extended]);
    }
}
