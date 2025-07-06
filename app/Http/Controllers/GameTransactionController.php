<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\BettingSlip;

class GameTransactionController extends Controller
{
    // Registrar aposta: desconta saldo do utilizador
    public function bet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'game' => 'required|string',
        ]);

        $user = Auth::user();
        if ($user->virtual_balance < $request->amount) {
            return response()->json(['error' => 'Saldo insuficiente'], 400);
        }

        $user->virtual_balance -= $request->amount;
        $user->save();

        // Criar registro na tabela betting_slips
        $bettingSlip = BettingSlip::create([
            'user_id' => $user->id,
            'game_type' => $request->game,
            'bet_amount' => $request->amount,
            'total_cost' => $request->amount,
            'status' => 'pending',
            'has_won' => false,
            'is_claimed' => false,
        ]);

        // Registrar atividade de aposta
        Activity::create([
            'user_id' => $user->id,
            'type' => 'bet_placed',
            'description' => 'Aposta em ' . $request->game,
            'data' => [
                'amount' => $request->amount,
                'game' => $request->game,
                'betting_slip_id' => $bettingSlip->id,
            ],
        ]);

        return response()->json([
            'success' => true,
            'balance' => $user->virtual_balance,
            'formatted_balance' => '€' . number_format($user->virtual_balance, 2),
            'message' => 'Aposta registrada com sucesso',
            'betting_slip_id' => $bettingSlip->id
        ]);
    }

    // Registrar ganho/perda: adiciona saldo e registra atividade
    public function win(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric', // pode ser 0 se perdeu
            'game' => 'required|string',
            'won' => 'required|boolean',
            'betting_slip_id' => 'nullable|exists:betting_slips,id',
        ]);

        $user = Auth::user();

        // Adicionar saldo se ganhou
        if ($request->amount > 0) {
            $user->virtual_balance += $request->amount;
            $user->save();
        }

        // Atualizar o registro na betting_slips se fornecido
        if ($request->betting_slip_id) {
            $bettingSlip = BettingSlip::where('id', $request->betting_slip_id)
                                    ->where('user_id', $user->id)
                                    ->first();

            if ($bettingSlip) {
                $bettingSlip->update([
                    'status' => $request->won ? 'won' : 'lost',
                    'has_won' => $request->won,
                    'prize_amount' => $request->amount,
                    'is_claimed' => $request->won,
                ]);
            }
        } else {
            // Se não temos betting_slip_id, procurar a aposta mais recente pendente
            $bettingSlip = BettingSlip::where('user_id', $user->id)
                                    ->where('game_type', $request->game)
                                    ->where('status', 'pending')
                                    ->orderBy('created_at', 'desc')
                                    ->first();

            if ($bettingSlip) {
                $bettingSlip->update([
                    'status' => $request->won ? 'won' : 'lost',
                    'has_won' => $request->won,
                    'prize_amount' => $request->amount,
                    'is_claimed' => $request->won,
                ]);
            }
        }

        // Registrar atividade de resultado
        Activity::create([
            'user_id' => $user->id,
            'type' => $request->won ? 'bet_won' : 'bet_lost',
            'description' => ($request->won ? 'Ganhou em ' : 'Perdeu em ') . $request->game,
            'data' => [
                'amount' => $request->amount,
                'prize_amount' => $request->amount, // Para compatibilidade com as estatísticas
                'game' => $request->game,
                'betting_slip_id' => $bettingSlip->id ?? null,
            ],
        ]);

        return response()->json([
            'success' => true,
            'balance' => $user->virtual_balance,
            'formatted_balance' => '€' . number_format($user->virtual_balance, 2),
            'message' => $request->won ? 'Parabéns! Ganhaste!' : 'Mais sorte na próxima!'
        ]);
    }
} 