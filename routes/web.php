<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DrawController;
use App\Http\Controllers\BettingSlipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Groups routes
    Route::resource('groups', GroupController::class);
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::get('/groups/{group}/members', [GroupController::class, 'members'])->name('groups.members');

    // Games routes
    Route::resource('games', GameController::class);
    Route::get('/games/{game}/draws', [GameController::class, 'draws'])->name('games.draws');
    Route::get('/games/upcoming-draws', [GameController::class, 'upcomingDraws'])->name('games.upcoming-draws');

    // Betting Slips routes
    Route::get('/betting-slips', [BettingSlipController::class, 'index'])->name('betting-slips.index');
    Route::get('/betting-slips/create/{group}', [BettingSlipController::class, 'create'])->name('betting-slips.create');
    Route::post('/betting-slips/{group}', [BettingSlipController::class, 'store'])->name('betting-slips.store');
    Route::get('/betting-slips/{bettingSlip}', [BettingSlipController::class, 'show'])->name('betting-slips.show');
    Route::post('/betting-slips/{bettingSlip}/check-results', [BettingSlipController::class, 'checkResults'])->name('betting-slips.check-results');
    Route::post('/betting-slips/generate-system-combinations', [BettingSlipController::class, 'generateSystemCombinations'])->name('betting-slips.generate-system-combinations');

    // Claim/Prize for Betting Slip
    Route::post('/betting-slips/{bettingSlip}/claim', [App\Http\Controllers\BettingSlipController::class, 'claim'])->name('betting-slips.claim')->middleware('auth');

    // Results routes
    Route::get('/results', [DrawController::class, 'results'])->name('results.index');
    Route::get('/results/{draw}', [DrawController::class, 'showResult'])->name('results.show');

    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');

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

    // AJAX para buscar jogos de uma liga para Totobola
    Route::get('/betting-slips/league-matches/{league}', [App\Http\Controllers\BettingSlipController::class, 'getLeagueMatches'])->name('betting-slips.league-matches');

    // Carteira Virtual
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::middleware(['auth'])->prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/deposit', [WalletController::class, 'showDepositForm'])->name('deposit');
        Route::post('/deposit', [WalletController::class, 'deposit'])->name('deposit.submit');
        Route::post('/add-funds', [WalletController::class, 'addFunds'])->name('add-funds');
        Route::post('/withdraw-funds', [WalletController::class, 'withdrawFunds'])->name('withdraw-funds');
    });
});

require __DIR__.'/auth.php';
