<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Notification;
use App\Models\Game;
use App\Models\Group;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateRandomNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:generate-random';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random notifications for users every 15 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->info('No users found.');
            return 0;
        }
        
        // Get all games
        $games = Game::all();
        
        // Get all groups
        $groups = Group::all();
        
        // Notification types
        $notificationTypes = [
            'new_draw' => [
                'title' => 'Novo sorteio disponível',
                'message' => 'Um novo sorteio de {game} está disponível para apostas.',
                'data' => ['game_id' => null]
            ],
            'draw_result' => [
                'title' => 'Resultado disponível',
                'message' => 'O resultado do sorteio de {game} já está disponível.',
                'data' => ['game_id' => null]
            ],
            'group_join' => [
                'title' => 'Novo membro no grupo',
                'message' => 'Um novo membro entrou no grupo {group}.',
                'data' => ['group_id' => null]
            ],
            'group_activity' => [
                'title' => 'Atividade no grupo',
                'message' => 'Há uma nova aposta no grupo {group}.',
                'data' => ['group_id' => null]
            ],
            'jackpot_increase' => [
                'title' => 'Jackpot aumentou',
                'message' => 'O jackpot do {game} aumentou para €{amount}.',
                'data' => ['game_id' => null, 'amount' => null]
            ],
        ];
        
        // For each user, create a random notification
        foreach ($users as $user) {
            // Select a random notification type
            $notificationType = array_rand($notificationTypes);
            $notification = $notificationTypes[$notificationType];
            
            // Prepare notification data based on type
            switch ($notificationType) {
                case 'new_draw':
                case 'draw_result':
                case 'jackpot_increase':
                    if ($games->isEmpty()) {
                        continue 2; // Skip to next user if no games
                    }
                    
                    $game = $games->random();
                    $notification['message'] = str_replace('{game}', $game->name, $notification['message']);
                    $notification['data']['game_id'] = $game->id;
                    
                    if ($notificationType === 'jackpot_increase') {
                        $amount = rand(1, 100) * 1000000; // Random amount between 1M and 100M
                        $formattedAmount = number_format($amount, 0, ',', '.');
                        $notification['message'] = str_replace('{amount}', $formattedAmount, $notification['message']);
                        $notification['data']['amount'] = $amount;
                    }
                    break;
                    
                case 'group_join':
                case 'group_activity':
                    if ($groups->isEmpty()) {
                        continue 2; // Skip to next user if no groups
                    }
                    
                    $group = $groups->random();
                    $notification['message'] = str_replace('{group}', $group->name, $notification['message']);
                    $notification['data']['group_id'] = $group->id;
                    break;
            }
            
            // Create the notification
            Notification::create([
                'user_id' => $user->id,
                'type' => $notificationType,
                'title' => $notification['title'],
                'message' => $notification['message'],
                'data' => json_encode($notification['data']),
                'is_read' => false,
            ]);
            
            $this->info("Created notification for user {$user->name}: {$notification['title']}");
        }
        
        return 0;
    }
}
