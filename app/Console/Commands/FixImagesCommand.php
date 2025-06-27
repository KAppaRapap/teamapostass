<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class FixImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:images {--force : Force recreation of storage link}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix image display issues by recreating storage link and setting permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🖼️  Iniciando correção das imagens...');

        // 1. Verificar estrutura de diretórios
        $this->checkDirectories();

        // 2. Recriar link simbólico
        $this->recreateStorageLink();

        // 3. Configurar permissões
        $this->setPermissions();

        // 4. Limpar cache
        $this->clearCache();

        // 5. Verificar configuração
        $this->checkConfiguration();

        $this->info('✅ Correção das imagens concluída!');
        
        return Command::SUCCESS;
    }

    /**
     * Check and create necessary directories
     */
    private function checkDirectories()
    {
        $this->info('📁 Verificando diretórios...');

        $directories = [
            'storage/app/public',
            'storage/app/public/profile_photos',
            'storage/app/public/chat_uploads',
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
                $this->line("   ✓ Criado: {$dir}");
            } else {
                $this->line("   ✓ Existe: {$dir}");
            }
        }
    }

    /**
     * Recreate storage symbolic link
     */
    private function recreateStorageLink()
    {
        $this->info('🔗 Recriando link simbólico...');

        $publicStoragePath = public_path('storage');

        // Remove existing link if it exists
        if (File::exists($publicStoragePath)) {
            if (is_link($publicStoragePath)) {
                unlink($publicStoragePath);
                $this->line('   ✓ Link antigo removido');
            } elseif (is_dir($publicStoragePath)) {
                $this->warn('   ⚠️  Diretório storage/ encontrado em public/. Considere removê-lo manualmente.');
            }
        }

        // Create new symbolic link
        try {
            Artisan::call('storage:link', $this->option('force') ? ['--force' => true] : []);
            $this->line('   ✓ Novo link simbólico criado');
        } catch (\Exception $e) {
            $this->error('   ❌ Erro ao criar link simbólico: ' . $e->getMessage());
            return;
        }

        // Verify link
        if (is_link($publicStoragePath) && is_dir($publicStoragePath)) {
            $this->line('   ✓ Link simbólico verificado e funcionando');
        } else {
            $this->error('   ❌ Link simbólico não está funcionando corretamente');
        }
    }

    /**
     * Set correct permissions
     */
    private function setPermissions()
    {
        $this->info('🔐 Configurando permissões...');

        $paths = [
            'storage/',
            'bootstrap/cache/',
            'storage/app/public/',
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                try {
                    chmod($path, 0755);
                    $this->line("   ✓ Permissões configuradas: {$path}");
                } catch (\Exception $e) {
                    $this->warn("   ⚠️  Não foi possível alterar permissões de {$path}: " . $e->getMessage());
                }
            }
        }

        // Set permissions for public/storage if it exists
        $publicStorage = public_path('storage');
        if (File::exists($publicStorage)) {
            try {
                chmod($publicStorage, 0755);
                $this->line('   ✓ Permissões configuradas: public/storage');
            } catch (\Exception $e) {
                $this->warn('   ⚠️  Não foi possível alterar permissões de public/storage: ' . $e->getMessage());
            }
        }
    }

    /**
     * Clear application cache
     */
    private function clearCache()
    {
        $this->info('🧹 Limpando cache...');

        $commands = [
            'config:clear' => 'Cache de configuração',
            'cache:clear' => 'Cache da aplicação',
            'view:clear' => 'Cache de views',
            'route:clear' => 'Cache de rotas',
        ];

        foreach ($commands as $command => $description) {
            try {
                Artisan::call($command);
                $this->line("   ✓ {$description} limpo");
            } catch (\Exception $e) {
                $this->warn("   ⚠️  Erro ao limpar {$description}: " . $e->getMessage());
            }
        }

        // Optimize application
        $this->info('⚡ Otimizando aplicação...');
        
        $optimizeCommands = [
            'config:cache' => 'Cache de configuração',
            'route:cache' => 'Cache de rotas',
            'view:cache' => 'Cache de views',
        ];

        foreach ($optimizeCommands as $command => $description) {
            try {
                Artisan::call($command);
                $this->line("   ✓ {$description} criado");
            } catch (\Exception $e) {
                $this->warn("   ⚠️  Erro ao criar {$description}: " . $e->getMessage());
            }
        }
    }

    /**
     * Check application configuration
     */
    private function checkConfiguration()
    {
        $this->info('⚙️  Verificando configuração...');

        // Check APP_URL
        $appUrl = config('app.url');
        if ($appUrl === 'http://localhost') {
            $this->warn('   ⚠️  APP_URL está configurada como localhost. Configure para a URL real do seu site.');
        } else {
            $this->line("   ✓ APP_URL: {$appUrl}");
        }

        // Check filesystem driver
        $filesystemDriver = config('filesystems.default');
        $this->line("   ✓ Filesystem driver: {$filesystemDriver}");

        // Check storage disk configuration
        $publicDisk = config('filesystems.disks.public');
        if ($publicDisk) {
            $this->line("   ✓ Disco público configurado");
            $this->line("   ✓ URL do storage: " . $publicDisk['url']);
        }

        // List some uploaded files for verification
        $profilePhotosPath = storage_path('app/public/profile_photos');
        if (File::exists($profilePhotosPath)) {
            $files = File::files($profilePhotosPath);
            $count = count($files);
            $this->line("   ✓ Fotos de perfil encontradas: {$count}");
            
            if ($count > 0) {
                $this->line("   ✓ Exemplo: " . basename($files[0]));
            }
        }
    }
}
