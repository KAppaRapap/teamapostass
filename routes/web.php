<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DrawController;
use App\Http\Controllers\BettingSlipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $games = App\Models\Game::all();
    return view('welcome', compact('games'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rotas de administração
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/create-games', [App\Http\Controllers\AdminController::class, 'showCreateGames'])->name('show-create-games');
    Route::post('/create-games', [App\Http\Controllers\AdminController::class, 'createGames'])->name('create-games');
    // User management
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
});

Route::middleware(['auth'])->group(function () {
    // Games routes
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
    Route::get('/upcoming-draws', [GameController::class, 'upcomingDraws'])->name('games.upcoming-draws');
    
    // Groups routes
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{group}/ban/{user}', [GroupController::class, 'banUser'])->name('groups.ban-user');
    Route::post('/groups/{group}/unban/{user}', [GroupController::class, 'unbanUser'])->name('groups.unban-user');
    
    // Betting Slips routes
    Route::get('/betting-slips', [BettingSlipController::class, 'index'])->name('betting-slips.index');
    Route::get('/betting-slips/{bettingSlip}', [BettingSlipController::class, 'show'])->name('betting-slips.show');
    
    // Results routes
    Route::get('/results', [DrawController::class, 'results'])->name('results.index');
    Route::get('/results/{draw}', [DrawController::class, 'showResult'])->name('results.show');
    
    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotificationPreferences'])->name('settings.update-notifications');
    
    // Draws routes
    Route::get('/draws', [DrawController::class, 'index'])->name('draws.index');
    Route::get('/draws/create', [DrawController::class, 'create'])->name('draws.create');
    Route::post('/draws', [DrawController::class, 'store'])->name('draws.store');
    Route::get('/draws/{draw}', [DrawController::class, 'show'])->name('draws.show');
    Route::get('/draws/{draw}/edit', [DrawController::class, 'edit'])->name('draws.edit');
    Route::put('/draws/{draw}', [DrawController::class, 'update'])->name('draws.update');
    Route::delete('/draws/{draw}', [DrawController::class, 'destroy'])->name('draws.destroy');
    
    // Betting Slips routes
    Route::get('/groups/{group}/betting-slips/create', [BettingSlipController::class, 'create'])->name('betting-slips.create');
    Route::post('/groups/{group}/betting-slips', [BettingSlipController::class, 'store'])->name('betting-slips.store');
    Route::get('/betting-slips/{bettingSlip}', [BettingSlipController::class, 'show'])->name('betting-slips.show');
    Route::post('/betting-slips/{bettingSlip}/check-results', [BettingSlipController::class, 'checkResults'])->name('betting-slips.check-results');
    Route::post('/betting-slips/generate-system-combinations', [BettingSlipController::class, 'generateSystemCombinations'])->name('betting-slips.generate-system-combinations');
    
    // Claim/Prize for Betting Slip
    Route::post('/betting-slips/{bettingSlip}/claim', [App\Http\Controllers\BettingSlipController::class, 'claim'])->name('betting-slips.claim')->middleware('auth');
    
    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    
    // AJAX para buscar jogos de uma liga para Totobola
    Route::get('/betting-slips/league-matches/{league}', [App\Http\Controllers\BettingSlipController::class, 'getLeagueMatches'])->name('betting-slips.league-matches');
    
    // Carteira Virtual
    Route::middleware(['auth'])->prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/deposit', [WalletController::class, 'showDepositForm'])->name('deposit');
        Route::post('/deposit', [WalletController::class, 'deposit'])->name('deposit.submit');
    });
});

require __DIR__.'/auth.php';
