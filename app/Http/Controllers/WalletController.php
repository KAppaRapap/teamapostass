<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = Activity::where('user_id', $user->id)
            ->whereIn('type', [
                Activity::TYPE_BET_PLACED,
                Activity::TYPE_BET_WON,
                Activity::TYPE_BALANCE_UPDATED
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('wallet.index', compact('user', 'transactions'));
    }

    public function addFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:1000'
        ]);

        $user = Auth::user();
        $user->updateBalance($request->amount, 'credit');

        return redirect()->route('wallet.index')
            ->with('success', 'Saldo adicionado com sucesso!');
    }

    public function withdrawFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:1000'
        ]);

        $user = Auth::user();

        if ($user->virtual_balance < $request->amount) {
            return redirect()->back()
                ->with('error', 'Saldo insuficiente para realizar o saque.');
        }

        $user->updateBalance($request->amount, 'debit');

        return redirect()->route('wallet.index')
            ->with('success', 'Saque realizado com sucesso!');
    }
}
