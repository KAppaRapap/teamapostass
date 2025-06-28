<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\BettingSlipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Helpers\AvatarHelper;

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

// Rotas de demonstração
Route::get('/demo/balance-notifications', function () {
    return view('demo.balance-notifications');
})->name('demo.balance-notifications');

Route::get('/demo/pagination-showcase', function () {
    return view('demo.pagination-showcase');
})->name('demo.pagination-showcase');

// Rotas de administração
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gerenciamento de usuários
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/toggle-ban', [AdminController::class, 'toggleBanUser'])->name('users.toggle-ban');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdminUser'])->name('users.toggle-admin');
    Route::post('/users/{user}/adjust-balance', [AdminController::class, 'adjustBalance'])->name('users.adjust-balance');

    // Gerenciamento de grupos
    Route::get('/groups', [AdminController::class, 'groups'])->name('groups.index');
    Route::get('/groups/{group}', [AdminController::class, 'showGroup'])->name('groups.show');
    Route::delete('/groups/{group}', [AdminController::class, 'deleteGroup'])->name('groups.delete');

    // Relatórios financeiros
    Route::get('/reports/financial', [AdminController::class, 'financialReports'])->name('reports.financial');

    // Logs do sistema
    Route::get('/logs', [AdminController::class, 'systemLogs'])->name('logs.index');

    // Configurações
    Route::get('/config', [AdminController::class, 'showConfig'])->name('config');
    Route::post('/config', [AdminController::class, 'updateConfig'])->name('config.update');

    // Funcionalidades antigas (manter compatibilidade)
    Route::get('/create-games', [AdminController::class, 'showCreateGames'])->name('show-create-games');
    Route::post('/create-games', [AdminController::class, 'createGames'])->name('create-games');
});

// Rotas públicas dos jogos
Route::get('/games/roleta', [App\Http\Controllers\GameController::class, 'roletaClassica'])->name('games.roleta');
Route::get('/games/dice', function() {
    return view('games.dice');
})->name('games.dice');
Route::get('/games/bombmine', function () {
    return view('games.bombmine');
})->middleware(['auth', 'verified'])->name('games.bombmine');
Route::get('/games/roleta-classica', [App\Http\Controllers\GameController::class, 'roletaClassica'])->name('games.roleta-classica');
Route::get('/games/crash', function () {
    return view('games.crash');
})->middleware(['auth', 'verified'])->name('games.crash');

Route::middleware(['auth'])->group(function () {
    // Games routes
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
    
    // Results route
    Route::get('/results', function() {
        return view('results.index');
    })->name('results.index');
    
    // Groups routes
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{group}/ban/{user}', [GroupController::class, 'banUser'])->name('groups.ban-user');
    Route::post('/groups/{group}/unban/{user}', [GroupController::class, 'unbanUser'])->name('groups.unban-user');
    
    // Group Chat route
    Route::get('/groups/{group}/chat', function($group) {
        return view('groups.chat', compact('group'));
    })->name('groups.chat');
    
    // Betting Slips routes
    Route::get('/betting-slips', [BettingSlipController::class, 'index'])->name('betting-slips.index');
    Route::get('/betting-slips/create', [BettingSlipController::class, 'createForGame'])->name('betting-slips.create-for-game');
    Route::post('/betting-slips/store-for-game', [BettingSlipController::class, 'storeForGame'])->name('betting-slips.store-for-game');
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
        Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
        Route::get('/index', [WalletController::class, 'index'])->name('index');
        Route::post('/add-funds', [WalletController::class, 'addFunds'])->name('add-funds');
    });

    // Chat routes
    Route::post('/chat/upload', [App\Http\Controllers\NotificationController::class, 'uploadChatFile'])->name('chat.upload')->middleware('auth');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::put('/profile/notifications', [App\Http\Controllers\ProfileController::class, 'updateNotifications'])->name('profile.update-notifications');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotificationPreferences'])->name('settings.update-notifications');
});

Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');

// Rotas para páginas legais
Route::get('/termos-de-uso', function () {
    return view('legal.terms');
})->name('legal.terms');

Route::get('/politica-de-privacidade', function () {
    return view('legal.privacy');
})->name('legal.privacy');

// Rota para gerar avatares SVG
Route::get('/avatar/{userId}/{name}', function ($userId, $name) {
    $svg = AvatarHelper::generateSvgAvatar($name, $userId, 200);

    return response($svg)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'public, max-age=31536000'); // Cache por 1 ano
})->name('avatar.generate');



require __DIR__.'/auth.php';
