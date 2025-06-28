<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CrashController;
use App\Http\Controllers\GameTransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rota para verificar novas notificações
Route::middleware('auth:sanctum')->get('/notifications/check-new', function (Request $request) {
    $user = $request->user();
    $unreadCount = $user->userNotifications()->where('is_read', false)->count();
    $latestNotification = $user->userNotifications()->where('is_read', false)->latest()->first();

    return response()->json([
        'count' => $unreadCount,
        'latest' => $latestNotification
    ]);
});

// Rota para buscar saldo atual do usuário
Route::middleware('auth')->get('/user/balance', function (Request $request) {
    $user = $request->user();
    return response()->json([
        'balance' => $user->virtual_balance,
        'formatted' => '€' . number_format($user->virtual_balance, 2)
    ]);
});

Route::post('/atualizar-saldo', function(Request $request) {
    $user = Auth::user();
    $novoSaldo = $request->input('saldo');
    $user->balance = $novoSaldo;
    $user->save();
    return response()->json(['saldo' => $user->balance]);
});

// Rotas para o jogo da roleta clássica
Route::post('/apostar', function(Request $request) {
    $user = Auth::user();
    $valor = $request->input('valor');
    $jogo = $request->input('jogo');
    
    if ($user->balance < $valor) {
        return response()->json([
            'success' => false,
            'message' => 'Saldo insuficiente'
        ], 400);
    }
    
    // Debita o valor da aposta
    $user->balance -= $valor;
    $user->save();
    
    // Registra a aposta no histórico
    \App\Models\BettingSlip::create([
        'user_id' => $user->id,
        'game_type' => $jogo,
        'bet_amount' => $valor,
        'virtual_amount' => $valor,
        'is_claimed' => false,
        'has_won' => false,
        'total_cost' => $valor
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Aposta registrada com sucesso',
        'novo_saldo' => $user->balance,
        'balance' => $user->balance,
        'formatted_balance' => '€' . number_format($user->balance, 2)
    ]);
})->middleware('auth');

Route::post('/ganhar', function(Request $request) {
    $user = Auth::user();
    $valor = $request->input('valor');
    $jogo = $request->input('jogo');
    
    // Credita o valor ganho
    $user->balance += $valor;
    $user->save();
    
    // Registra o ganho no histórico
    \App\Models\BettingSlip::create([
        'user_id' => $user->id,
        'game_type' => $jogo,
        'bet_amount' => 0,
        'virtual_amount' => $valor,
        'is_claimed' => true,
        'has_won' => true,
        'prize_amount' => $valor,
        'winnings' => $valor,
        'total_cost' => 0
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Ganho registrado com sucesso',
        'novo_saldo' => $user->balance,
        'balance' => $user->balance,
        'formatted_balance' => '€' . number_format($user->balance, 2)
    ]);
})->middleware('auth');

// Verificar se o usuário autenticado é membro de um grupo
Route::middleware('auth:sanctum')->get('/groups/{groupId}/is-member', [\App\Http\Controllers\GroupController::class, 'isMember']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/crash/bet', [CrashController::class, 'placeBet']);
    Route::post('/crash/{game}/cashout', [CrashController::class, 'cashout']);
    Route::get('/crash/{game}', [CrashController::class, 'show']); // for simulation
    
    // Rota de teste para verificar autenticação
    Route::get('/test-auth', function () {
        return response()->json(['message' => 'Autenticado com sucesso', 'user' => auth()->user()->id]);
    });

    Route::post('/games/bet', [GameTransactionController::class, 'bet']); // Registrar aposta (desconta saldo)
    Route::post('/games/win', [GameTransactionController::class, 'win']); // Registrar ganho (adiciona saldo e registra atividade)
});
