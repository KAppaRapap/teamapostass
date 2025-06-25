<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class GameTransactionController extends Controller
{
    // Registrar aposta: desconta saldo do usuário
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
        // Registrar atividade de aposta
        Activity::create([
            'user_id' => $user->id,
            'type' => 'bet_placed',
            'description' => 'Aposta em ' . $request->game,
            'data' => [
                'amount' => $request->amount,
                'game' => $request->game,
            ],
        ]);
        return response()->json(['success' => true, 'balance' => $user->virtual_balance]);
    }

    // Registrar ganho/perda: adiciona saldo e registra atividade
    public function win(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric', // pode ser 0 se perdeu
            'game' => 'required|string',
            'won' => 'required|boolean',
        ]);
        $user = Auth::user();
        if ($request->amount > 0) {
            $user->virtual_balance += $request->amount;
            $user->save();
        }
        // Registrar atividade de resultado
        Activity::create([
            'user_id' => $user->id,
            'type' => $request->won ? 'bet_won' : 'bet_lost',
            'description' => ($request->won ? 'Ganhou em ' : 'Perdeu em ') . $request->game,
            'data' => [
                'amount' => $request->amount,
                'game' => $request->game,
            ],
        ]);
        return response()->json(['success' => true, 'balance' => $user->virtual_balance]);
    }
} 