<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return redirect()->route('dashboard')->with('success', 'Depósito realizado com sucesso!');
    }
}
