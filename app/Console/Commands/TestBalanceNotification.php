<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;

class TestBalanceNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:balance-notification {user_id?} {--amount=100} {--type=add} {--reason="Teste de notificação"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o sistema de notificações de ajuste de saldo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id') ?? User::first()->id;
        $amount = $this->option('amount');
        $type = $this->option('type');
        $reason = $this->option('reason');

        $user = User::find($userId);
        
        if (!$user) {
            $this->error("Utilizador com ID {$userId} não encontrado!");
            return 1;
        }

        $this->info("Testando notificação de ajuste de saldo para: {$user->name}");
        $this->info("Tipo: {$type}, Valor: €{$amount}, Motivo: {$reason}");

        $oldBalance = $user->virtual_balance;

        // Simular ajuste de saldo
        switch ($type) {
            case 'add':
                $newBalance = $oldBalance + $amount;
                break;
            case 'subtract':
                $newBalance = max(0, $oldBalance - $amount);
                break;
            case 'set':
                $newBalance = max(0, $amount);
                break;
            default:
                $this->error("Tipo inválido! Use: add, subtract, set");
                return 1;
        }

        // Criar notificação de teste (sem alterar o saldo real)
        $this->createBalanceAdjustmentNotification($user, $oldBalance, $newBalance, $type, $amount, $reason);

        $this->info("✅ Notificação criada com sucesso!");
        $this->info("Saldo anterior: €{$oldBalance}");
        $this->info("Novo saldo (simulado): €{$newBalance}");
        
        // Mostrar a notificação criada
        $notification = $user->userNotifications()->latest()->first();
        if ($notification) {
            $this->info("\n📧 Notificação criada:");
            $this->info("Título: {$notification->title}");
            $this->info("Mensagem: {$notification->message}");
            $this->info("Tipo: {$notification->type}");
            $this->info("Lida: " . ($notification->is_read ? 'Sim' : 'Não'));
        }

        return 0;
    }

    /**
     * Criar notificação de ajuste de saldo
     */
    private function createBalanceAdjustmentNotification($user, $oldBalance, $newBalance, $type, $amount, $reason)
    {
        // Determinar o título e mensagem baseado no tipo de ajuste
        switch ($type) {
            case 'add':
                $title = '💰 Saldo Adicionado';
                $message = "Foram adicionados €{$amount} ao seu saldo. Motivo: {$reason}";
                $icon = 'fas fa-plus-circle';
                $color = 'bg-green-600';
                break;
            case 'subtract':
                $title = '💸 Saldo Deduzido';
                $message = "Foram deduzidos €{$amount} do seu saldo. Motivo: {$reason}";
                $icon = 'fas fa-minus-circle';
                $color = 'bg-red-600';
                break;
            case 'set':
                $title = '⚖️ Saldo Definido';
                $message = "Seu saldo foi definido para €{$newBalance}. Motivo: {$reason}";
                $icon = 'fas fa-balance-scale';
                $color = 'bg-blue-600';
                break;
        }

        // Criar a notificação
        Notification::create([
            'user_id' => $user->id,
            'type' => 'balance_adjustment',
            'title' => $title,
            'message' => $message,
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
                'amount' => $amount,
                'adjustment_type' => $type,
                'reason' => $reason,
                'admin_id' => 1, // ID fictício para teste
                'admin_name' => 'Sistema de Teste',
                'icon' => $icon,
                'color' => $color,
            ]),
            'is_read' => false,
        ]);
    }
}
