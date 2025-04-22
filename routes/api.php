<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
