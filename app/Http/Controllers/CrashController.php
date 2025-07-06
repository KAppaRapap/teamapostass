<?php

namespace App\Http\Controllers;

use App\Models\CrashGame;
use App\Models\BettingSlip;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CrashController extends Controller
{
    private function generateCrashPoint($seed) {
        $hash = hash('sha256', $seed);
        // Using 8 chars for hexdec to avoid hitting float limits on 32-bit systems
        $int = hexdec(substr($hash, 0, 8));
        $max = pow(2, 32); // Corresponding to 8 hex characters
        // Formula adjusted for the new integer size
        $result = floor((100 * $max) / ($max - $int)) / 100;

        return max(1.00, round($result, 2));
    }

    public function placeBet(Request $request) {
        $request->validate([
            'bet_amount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $betAmount = $request->input('bet_amount');

        \Log::info('Place bet request', [
            'user_id' => $user->id,
            'bet_amount' => $betAmount,
            'current_balance' => $user->virtual_balance
        ]);

        if ($user->virtual_balance < $betAmount) {
            return response()->json(['error' => 'Saldo insuficiente'], 400);
        }

        $user->decrement('virtual_balance', $betAmount);

        $seed = Str::random(64);
        $crashPoint = $this->generateCrashPoint($seed);

        $game = CrashGame::create([
            'crash_point' => $crashPoint,
            'server_seed' => $seed,
            'hash' => hash('sha256', $seed),
        ]);

        $bet = BettingSlip::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'bet_amount' => $betAmount,
            'total_cost' => $betAmount,
            'game_type' => 'Crash',
            'status' => 'pending',
        ]);

        // Registar atividade de aposta
        Activity::create([
            'user_id' => $user->id,
            'type' => 'bet_placed',
            'description' => 'Aposta em Crash',
            'data' => [
                'amount' => $betAmount,
                'game' => 'Crash',
                'betting_slip_id' => $bet->id,
            ],
        ]);

        \Log::info('Bet created', [
            'bet_id' => $bet->id,
            'game_id' => $game->id,
            'bet_amount' => $bet->bet_amount,
            'status' => $bet->status
        ]);

        return response()->json([
            'message' => 'Aposta realizada com sucesso!',
            'game_id' => $game->id,
            'bet_id' => $bet->id,
            'hash' => $game->hash,
            'balance' => $user->virtual_balance,
            'formatted_balance' => '€' . number_format($user->virtual_balance, 2)
        ]);
    }

    public function cashout(Request $request, CrashGame $game) {
        \Log::info('Cashout request received', [
            'game_id' => $game->id,
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'multiplier' => 'required|numeric|min:1',
            'bet_id' => 'required|exists:betting_slips,id',
        ]);

        $user = Auth::user();
        $bet = BettingSlip::where('id', $request->input('bet_id'))
                        ->where('user_id', $user->id)
                        ->where('game_id', $game->id)
                        ->where('game_type', 'Crash')
                        ->firstOrFail();

        \Log::info('Bet found', [
            'bet_id' => $bet->id,
            'bet_status' => $bet->status,
            'game_id' => $bet->game_id,
            'user_id' => $bet->user_id,
            'bet_amount' => $bet->bet_amount
        ]);

        if ($bet->status !== 'pending') {
            \Log::warning('Bet already finalized', ['bet_status' => $bet->status]);
            return response()->json(['error' => 'Aposta já finalizada'], 400);
        }

        // Verificar se bet_amount é válido
        if (!$bet->bet_amount || $bet->bet_amount <= 0) {
            \Log::error('Invalid bet_amount', ['bet_amount' => $bet->bet_amount]);
            return response()->json(['error' => 'Valor da aposta inválido'], 400);
        }

        $multiplier = $request->input('multiplier');

        \Log::info('Checking multiplier', [
            'current_multiplier' => $multiplier,
            'crash_point' => $game->crash_point
        ]);

        if ($multiplier > $game->crash_point) {
            \Log::warning('Cashout failed - game crashed', [
                'multiplier' => $multiplier,
                'crash_point' => $game->crash_point
            ]);
            $bet->update(['status' => 'lost', 'has_won' => false]);

            // Registar atividade de perda
            Activity::create([
                'user_id' => $user->id,
                'type' => 'bet_lost',
                'description' => 'Perdeu em Crash',
                'data' => [
                    'amount' => 0,
                    'prize_amount' => 0,
                    'game' => 'Crash',
                    'betting_slip_id' => $bet->id,
                    'crash_point' => $game->crash_point,
                    'attempted_multiplier' => $multiplier,
                ],
            ]);

            return response()->json(['error' => 'Cashout falhou. O jogo crashou.'], 400);
        }

        $winnings = $bet->bet_amount * $multiplier;
        $user->increment('virtual_balance', $winnings);

        $bet->update([
            'status' => 'won',
            'has_won' => true,
            'prize_amount' => $winnings,
            'is_claimed' => true,
        ]);

        // Registar atividade de ganho
        Activity::create([
            'user_id' => $user->id,
            'type' => 'bet_won',
            'description' => 'Ganhou em Crash',
            'data' => [
                'amount' => $winnings,
                'prize_amount' => $winnings,
                'game' => 'Crash',
                'betting_slip_id' => $bet->id,
                'multiplier' => $multiplier,
            ],
        ]);

        \Log::info('Cashout successful', [
            'winnings' => $winnings,
            'multiplier' => $multiplier
        ]);

        return response()->json([
            'message' => 'Cashout realizado com sucesso!',
            'winnings' => $winnings,
            'balance' => $user->virtual_balance,
            'formatted_balance' => '€' . number_format($user->virtual_balance, 2)
        ]);
    }

    public function show(CrashGame $game) {
        return response()->json($game);
    }
}
