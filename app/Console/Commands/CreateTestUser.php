<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user to verify avatar system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testUsers = [
            [
                'name' => 'João Silva',
                'email' => 'joao@test.com',
                'password' => Hash::make('password'),
                'virtual_balance' => 1000.00,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@test.com',
                'password' => Hash::make('password'),
                'virtual_balance' => 1500.00,
            ],
            [
                'name' => 'Pedro Costa',
                'email' => 'pedro@test.com',
                'password' => Hash::make('password'),
                'virtual_balance' => 2000.00,
            ]
        ];

        foreach ($testUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            if ($user->wasRecentlyCreated) {
                $this->info("Utilizador criado: {$user->name} ({$user->email})");
                $this->info("Avatar URL: {$user->profile_photo_url}");
            } else {
                $this->info("Utilizador já existe: {$user->name} ({$user->email})");
                $this->info("Avatar URL: {$user->profile_photo_url}");
            }
        }

        $this->info('Teste concluído!');
    }
}
