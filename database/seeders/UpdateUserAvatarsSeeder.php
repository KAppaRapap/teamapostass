<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateUserAvatarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Este seeder não precisa fazer nada, pois os avatares são gerados automaticamente
        // quando o utilizador não tem profile_photo definido
        
        $usersWithoutPhoto = User::whereNull('profile_photo')->count();
        
        $this->command->info("Encontrados {$usersWithoutPhoto} usuários sem foto de perfil.");
        $this->command->info("Os avatares automáticos serão gerados dinamicamente quando necessário.");
    }
}
