<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class WalletController extends Controller
{
    public function showDepositForm()
    {
        return view('wallet.deposit');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $user->virtual_balance = ($user->virtual_balance ?? 0) + $request->amount;
        $user->save();

        // Registar atividade de depósito
        Activity::logBalanceUpdated($user, $request->amount, 'credit');

        return redirect()->route('dashboard')->with('success', 'Depósito realizado com sucesso!');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        if ($user->virtual_balance < $request->amount) {
            return redirect()->route('dashboard')->with('error', 'Saldo insuficiente para realizar o saque.');
        }

        $user->virtual_balance -= $request->amount;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Saque realizado com sucesso!');
    }

    public function addFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:1000',
        ]);

        $user = auth()->user();
        $user->virtual_balance += $request->amount;
        $user->save();

        // Registrar atividade de depósito
        Activity::logBalanceUpdated($user, $request->amount, 'credit');

        return redirect()->route('wallet.index')->with('success', 'Depósito realizado com sucesso!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $transactions = $user->activities()
            ->whereIn('type', ['bet_placed', 'bet_won', 'balance_updated'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('wallet.index', compact('user', 'transactions'));
    }

    /**
     * Get wallet statistics via AJAX
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        $user = auth()->user();

        $totalDeposited = $user->activities()
            ->where('type', 'balance_updated')
            ->where('data->type', 'credit')
            ->sum('data->amount');

        $totalBets = $user->activities()
            ->where('type', 'bet_placed')
            ->sum('data->amount');

        $totalWon = $user->activities()
            ->where('type', 'bet_won')
            ->sum('data->prize_amount');

        return response()->json([
            'balance' => $user->virtual_balance,
            'formatted_balance' => '€' . number_format($user->virtual_balance, 2),
            'total_deposited' => $totalDeposited,
            'formatted_total_deposited' => '€' . number_format($totalDeposited, 2),
            'total_bets' => $totalBets,
            'formatted_total_bets' => '€' . number_format($totalBets, 2),
            'total_won' => $totalWon,
            'formatted_total_won' => '€' . number_format($totalWon, 2),
        ]);
    }
}
