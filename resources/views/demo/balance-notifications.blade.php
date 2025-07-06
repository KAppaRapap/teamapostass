@extends('layouts.app')

@section('title', 'Demo - Notificações de Ajuste de Saldo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-900 rounded-lg shadow-lg p-6 border border-gray-800 mb-8">
            <h1 class="text-3xl font-bold text-neon-green mb-4">
                <i class="fas fa-bell mr-3"></i>Sistema de Notificações de Ajuste de Saldo
            </h1>
            <p class="text-gray-300 mb-6">
                Este sistema permite que administradores ajustem o saldo dos utilizadores e automaticamente enviem notificações informando sobre as alterações.
            </p>
        </div>

        <!-- Demonstração dos Tipos de Notificação -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <!-- Adição de Saldo -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-600 text-white text-xl mr-4">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">💰 Saldo Adicionado</h3>
                        <p class="text-sm text-gray-400">Tipo: add</p>
                    </div>
                </div>
                <p class="text-gray-300 text-sm mb-3">
                    "Foram adicionados €50 ao seu saldo. Motivo: Bônus de boas-vindas"
                </p>
                <div class="text-xs text-gray-500">
                    <strong>Uso:</strong> Bônus, promoções, correções positivas
                </div>
            </div>

            <!-- Dedução de Saldo -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-600 text-white text-xl mr-4">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">💸 Saldo Deduzido</h3>
                        <p class="text-sm text-gray-400">Tipo: subtract</p>
                    </div>
                </div>
                <p class="text-gray-300 text-sm mb-3">
                    "Foram deduzidos €25 do seu saldo. Motivo: Taxa de manutenção"
                </p>
                <div class="text-xs text-gray-500">
                    <strong>Uso:</strong> Taxas, penalidades, correções negativas
                </div>
            </div>

            <!-- Definição de Saldo -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white text-xl mr-4">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">⚖️ Saldo Definido</h3>
                        <p class="text-sm text-gray-400">Tipo: set</p>
                    </div>
                </div>
                <p class="text-gray-300 text-sm mb-3">
                    "Seu saldo foi definido para €1000. Motivo: Ajuste administrativo"
                </p>
                <div class="text-xs text-gray-500">
                    <strong>Uso:</strong> Redefinições, ajustes administrativos
                </div>
            </div>
        </div>

        <!-- Como Funciona -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mb-8">
            <h2 class="text-2xl font-bold text-neon-green mb-4">
                <i class="fas fa-cogs mr-2"></i>Como Funciona
            </h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-3">1. Admin Ajusta Saldo</h3>
                    <p class="text-gray-300 mb-4">
                        O administrador acessa a página de edição do utilizador e utiliza o formulário "Ajustar Saldo" para:
                    </p>
                    <ul class="text-gray-300 text-sm space-y-1">
                        <li>• Escolher o tipo de ajuste (Adicionar, Subtrair, Definir)</li>
                        <li>• Inserir o valor</li>
                        <li>• Especificar o motivo</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-3">2. Sistema Envia Notificação</h3>
                    <p class="text-gray-300 mb-4">
                        Automaticamente, o sistema:
                    </p>
                    <ul class="text-gray-300 text-sm space-y-1">
                        <li>• Atualiza o saldo do utilizador</li>
                        <li>• Cria uma notificação personalizada</li>
                        <li>• Registra a ação no log de atividades</li>
                        <li>• Utilizador recebe notificação em tempo real</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Informações Técnicas -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <h2 class="text-2xl font-bold text-neon-green mb-4">
                <i class="fas fa-code mr-2"></i>Informações Técnicas
            </h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-3">Implementação</h3>
                    <ul class="text-gray-300 text-sm space-y-2">
                        <li><strong>Controller:</strong> AdminController::adjustBalance()</li>
                        <li><strong>Modelo:</strong> App\Models\Notification</li>
                        <li><strong>Tabela:</strong> notifications (com UUID)</li>
                        <li><strong>Tipo:</strong> balance_adjustment</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-3">Dados Armazenados</h3>
                    <ul class="text-gray-300 text-sm space-y-2">
                        <li><strong>Saldo anterior:</strong> old_balance</li>
                        <li><strong>Novo saldo:</strong> new_balance</li>
                        <li><strong>Valor ajustado:</strong> amount</li>
                        <li><strong>Tipo de ajuste:</strong> adjustment_type</li>
                        <li><strong>Motivo:</strong> reason</li>
                        <li><strong>Admin responsável:</strong> admin_id, admin_name</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Botões de Teste -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mt-8">
            <h2 class="text-2xl font-bold text-neon-green mb-4">
                <i class="fas fa-flask mr-2"></i>Comandos de Teste
            </h2>
            <p class="text-gray-300 mb-4">
                Use estes comandos no terminal para testar o sistema:
            </p>
            <div class="bg-gray-800 rounded p-4 font-mono text-sm">
                <div class="text-green-400 mb-2"># Adicionar saldo</div>
                <div class="text-white mb-4">php artisan test:balance-notification --amount=50 --type=add --reason="Bônus de teste"</div>
                
                <div class="text-green-400 mb-2"># Deduzir saldo</div>
                <div class="text-white mb-4">php artisan test:balance-notification --amount=25 --type=subtract --reason="Taxa de teste"</div>
                
                <div class="text-green-400 mb-2"># Definir saldo</div>
                <div class="text-white">php artisan test:balance-notification --amount=1000 --type=set --reason="Ajuste de teste"</div>
            </div>
        </div>
    </div>
</div>
@endsection
