<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUsersToPortugal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-to-portugal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza todos os utilizadores para configurações de Portugal (pt-PT, EUR, Europe/Lisbon)';

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
    public function handle()
    {
        $this->info('🔄 A atualizar utilizadores para configurações de Portugal...');

        try {
            // Atualizar todos os utilizadores para configurações de Portugal
            $updatedCount = User::query()
                ->update([
                    'language' => 'pt-PT',
                    'timezone' => 'Europe/Lisbon',
                    'currency' => 'EUR',
                ]);

            $this->info("✅ {$updatedCount} utilizadores atualizados com sucesso!");
            $this->info('📋 Configurações aplicadas:');
            $this->info('   • Idioma: pt-PT (Português de Portugal)');
            $this->info('   • Fuso horário: Europe/Lisbon');
            $this->info('   • Moeda: EUR (Euro)');

            // Verificar se há utilizadores com configurações antigas
            $oldConfigUsers = User::where('language', 'pt')
                ->orWhere('timezone', 'America/Sao_Paulo')
                ->orWhere('currency', 'BRL')
                ->count();

            if ($oldConfigUsers > 0) {
                $this->warn("⚠️  Ainda existem {$oldConfigUsers} utilizadores com configurações antigas.");
                $this->warn('   Execute novamente este comando se necessário.');
            } else {
                $this->info('🎉 Todos os utilizadores estão agora configurados para Portugal!');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Erro ao atualizar utilizadores: ' . $e->getMessage());
            return 1;
        }
    }
}
