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
        
        return view('betting-slips.create', compact('group', 'draws'));
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
        
        $validated = $request->validate([
            'draw_id' => 'required|exists:draws,id',
            'numbers' => 'required|array',
            'numbers.*' => 'required|integer|min:1',
            'is_system' => 'boolean',
            'system_details' => 'nullable|array',
            'custom_amount' => 'required|numeric|min:0.01',
        ]);
        
        // Get the draw and game
        $draw = Draw::findOrFail($validated['draw_id']);
        $game = $group->game;
        
        // Calculate total cost
        $totalCost = $this->calculateTotalCost(
            $game, 
            $validated['numbers'], 
            $validated['is_system'] ?? false, 
            $validated['system_details'] ?? null
        );

        // Valor customizado é sempre obrigatório
        $customAmount = $validated['custom_amount'];
        if ($validated['is_system'] ?? false) {
            $minCost = $totalCost;
            if ($customAmount < $minCost) {
                return redirect()->back()->withInput()->with('error', 'O valor a apostar deve ser igual ou maior ao custo estimado do sistema.');
            }
            $totalCost = $customAmount;
        } else {
            // Para apostas simples, aceita qualquer valor >= 0.01
            $totalCost = $customAmount;
        }

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
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'draw_id' => $validated['draw_id'],
            'numbers' => $validated['numbers'],
            'is_system' => $validated['is_system'] ?? false,
            'system_details' => $validated['system_details'] ?? null,
            'total_cost' => $totalCost,
            'is_checked' => false,
        ]);
        
        return redirect()->route('groups.show', $group)
            ->with('success', 'Aposta registrada com sucesso. Valor debitado da carteira virtual.');
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
        $systemType = isset($systemDetails['system_type']) ? $systemDetails['system_type'] : null;
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
    
    /**
     * Generate full system combinations.
     *
     * @param  array  $mainNumbers
     * @param  array  $stars
     * @return array
     */
    private function generateFullSystemCombinations($mainNumbers, $stars)
    {
        // Simplified implementation - in a real system, this would generate all possible combinations
        // For example, for Euromilhões, it would generate all combinations of 5 numbers from mainNumbers
        // and 2 stars from stars
        
        // This is a placeholder for the actual implementation
        return [
            // Example combinations
            ['main' => [1, 2, 3, 4, 5], 'stars' => [1, 2]],
            ['main' => [1, 2, 3, 4, 6], 'stars' => [1, 2]],
            // More combinations would be generated here
        ];
    }
    
    /**
     * Generate partial system combinations.
     *
     * @param  array  $mainNumbers
     * @param  array  $stars
     * @param  string  $systemType
     * @return array
     */
    private function generatePartialSystemCombinations($mainNumbers, $stars, $systemType)
    {
        // Simplified implementation - in a real system, this would generate combinations
        // based on the specific partial system type
        
        // This is a placeholder for the actual implementation
        return [
            // Example combinations
            ['main' => [1, 2, 3, 4, 5], 'stars' => [1, 2]],
            ['main' => [1, 2, 3, 4, 6], 'stars' => [1, 2]],
            // More combinations would be generated here
        ];
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
        // Simplified implementation - in a real system, this would check the betting slip numbers
        // against the winning numbers and calculate the prize based on the game rules
        
        // This is a placeholder for the actual implementation
        $winningNumbers = $draw->winning_numbers;
        $betNumbers = $bettingSlip->numbers;
        
        // Count matching numbers
        $matchingCount = count(array_intersect($winningNumbers, $betNumbers));
        
        // Simple prize calculation (would be more complex in a real system)
        switch ($matchingCount) {
            case 3:
                return 5.00;
            case 4:
                return 50.00;
            case 5:
                return 500.00;
            case 6:
                return 5000.00;
            default:
                return 0.00;
        }
    }
}
