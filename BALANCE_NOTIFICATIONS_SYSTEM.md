# Sistema de Notificações de Ajuste de Saldo

## 📋 Resumo

Foi implementado um sistema completo de notificações que informa automaticamente os usuários quando seus saldos são ajustados por administradores.

## ✅ Funcionalidades Implementadas

### 1. **Notificações Automáticas**
- ✅ Notificação automática após ajuste de saldo
- ✅ Diferentes tipos de notificação baseados no tipo de ajuste
- ✅ Mensagens personalizadas com ícones e cores específicas
- ✅ Armazenamento de dados detalhados sobre o ajuste

### 2. **Tipos de Ajuste Suportados**

#### 💰 **Adição de Saldo** (`add`)
- **Ícone:** `fas fa-plus-circle`
- **Cor:** Verde (`bg-green-600`)
- **Mensagem:** "Foram adicionados €{valor} ao seu saldo. Motivo: {motivo}"
- **Uso:** Bônus, promoções, correções positivas

#### 💸 **Dedução de Saldo** (`subtract`)
- **Ícone:** `fas fa-minus-circle`
- **Cor:** Vermelho (`bg-red-600`)
- **Mensagem:** "Foram deduzidos €{valor} do seu saldo. Motivo: {motivo}"
- **Uso:** Taxas, penalidades, correções negativas

#### ⚖️ **Definição de Saldo** (`set`)
- **Ícone:** `fas fa-balance-scale`
- **Cor:** Azul (`bg-blue-600`)
- **Mensagem:** "Seu saldo foi definido para €{novo_saldo}. Motivo: {motivo}"
- **Uso:** Redefinições, ajustes administrativos

### 3. **Dados Armazenados na Notificação**
```json
{
    "old_balance": "1000.00",
    "new_balance": "1050.00",
    "amount": "50.00",
    "adjustment_type": "add",
    "reason": "Bônus de boas-vindas",
    "admin_id": 1,
    "admin_name": "Admin Name",
    "icon": "fas fa-plus-circle",
    "color": "bg-green-600"
}
```

## 🔧 Arquivos Modificados/Criados

### **Backend (PHP)**

1. **`app/Http/Controllers/AdminController.php`**
   - ✅ Método `adjustBalance()` atualizado
   - ✅ Novo método `createBalanceAdjustmentNotification()`
   - ✅ Integração automática com sistema de notificações

2. **`app/Models/Notification.php`**
   - ✅ Configuração para UUID como chave primária
   - ✅ Suporte aos campos `notifiable_type` e `notifiable_id`
   - ✅ Campo `is_read` adicionado

3. **`database/migrations/`**
   - ✅ `2025_06_28_000001_add_is_read_to_custom_notifications_table.php`
   - ✅ `2025_06_28_000002_add_is_read_to_notifications_table.php`

### **Frontend (Blade)**

4. **`resources/views/notifications/index.blade.php`**
   - ✅ Suporte visual para notificações de ajuste de saldo
   - ✅ Ícones e cores dinâmicas baseadas no tipo de ajuste

5. **`resources/views/demo/balance-notifications.blade.php`**
   - ✅ Página de demonstração completa
   - ✅ Documentação visual do sistema

### **Comandos de Teste**

6. **`app/Console/Commands/TestBalanceNotification.php`**
   - ✅ Comando para testar notificações
   - ✅ Suporte a todos os tipos de ajuste
   - ✅ Parâmetros configuráveis

### **Rotas**

7. **`routes/web.php`**
   - ✅ Rota para página de demonstração

## 🚀 Como Usar

### **1. Ajuste via Interface Admin**
1. Acesse `/admin/users/{id}/edit`
2. Use o formulário "Ajustar Saldo"
3. Escolha o tipo, valor e motivo
4. O usuário receberá a notificação automaticamente

### **2. Teste via Comando**
```bash
# Adicionar saldo
php artisan test:balance-notification --amount=50 --type=add --reason="Bônus de teste"

# Deduzir saldo
php artisan test:balance-notification --amount=25 --type=subtract --reason="Taxa de teste"

# Definir saldo
php artisan test:balance-notification --amount=1000 --type=set --reason="Ajuste de teste"
```

### **3. Visualizar Demonstração**
- Acesse `/demo/balance-notifications` para ver a documentação visual

## 📊 Estatísticas do Sistema

- **Notificações criadas:** Verificar com `php artisan tinker --execute="echo App\Models\Notification::count();"`
- **Notificações não lidas:** Verificar com `php artisan tinker --execute="echo App\Models\Notification::where('is_read', false)->count();"`

## 🔄 Fluxo Completo

1. **Admin ajusta saldo** → `AdminController::adjustBalance()`
2. **Sistema atualiza saldo** → Banco de dados
3. **Sistema cria log** → Tabela `activities`
4. **Sistema cria notificação** → Tabela `notifications`
5. **Usuário recebe notificação** → Interface web
6. **Usuário visualiza detalhes** → Página de notificações

## 🎯 Benefícios

- ✅ **Transparência:** Usuários são informados sobre todas as alterações
- ✅ **Rastreabilidade:** Histórico completo de ajustes
- ✅ **Automação:** Processo totalmente automatizado
- ✅ **Personalização:** Mensagens específicas por tipo de ajuste
- ✅ **Dados Detalhados:** Informações completas sobre cada ajuste

## 🔮 Próximos Passos Sugeridos

1. **Notificações em Tempo Real:** Implementar WebSockets/Pusher
2. **Notificações por Email:** Enviar emails para ajustes importantes
3. **Configurações de Usuário:** Permitir usuários escolherem tipos de notificação
4. **Dashboard de Notificações:** Painel admin para gerenciar notificações
5. **Notificações Push:** Implementar notificações push no navegador
