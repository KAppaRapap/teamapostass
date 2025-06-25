<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Firestore;

class FirebaseTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:firebase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tests the connection to Firebase';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Firestore $firestore)
    {
        $userRepository = new UserRepository($firestore);

        try {
            $this->info('Starting Firebase Firestore test...');

            // 1. Create a user
            $this->info("\nStep 1: Creating a new user...");
            $userData = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'created_at' => now()->toIso8601String(),
            ];
            $createdUser = $userRepository->create($userData);
            $userId = $createdUser['id'];
            $this->info("User created with ID: {$userId}");
            $this->info(json_encode($createdUser, JSON_PRETTY_PRINT));

            // 2. Find the user
            $this->info("\nStep 2: Finding user with ID: {$userId}...");
            $foundUser = $userRepository->find($userId);
            if ($foundUser) {
                $this->info('User found:');
                $this->info(json_encode($foundUser, JSON_PRETTY_PRINT));
            } else {
                $this->error('User not found.');
                return 1;
            }

            // 3. Update the user
            $this->info("\nStep 3: Updating user's name...");
            $updatedUser = $userRepository->update($userId, ['name' => 'Test User Updated']);
            $this->info('User updated:');
            $this->info(json_encode($updatedUser, JSON_PRETTY_PRINT));

            // 4. Delete the user
            $this->info("\nStep 4: Deleting user with ID: {$userId}...");
            $userRepository->delete($userId);
            $this->info('User deleted.');

            // Verify deletion
            $this->info("\nVerifying deletion...");
            if (!$userRepository->find($userId)) {
                $this->info("Successfully verified that user {$userId} has been deleted.");
            } else {
                $this->error("Verification failed. User {$userId} was not deleted.");
            }

            $this->info("\nFirebase Firestore test completed successfully!");

            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred during the Firebase test.');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
