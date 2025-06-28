# Sistema de Administração TeamApostas

## 📋 Visão Geral

Implementei um sistema completo de administração para usuários com privilégios de admin, oferecendo controlo total sobre a plataforma TeamApostas com interface moderna e funcionalidades avançadas.

## 🚀 Funcionalidades Implementadas

### ✅ **Dashboard Principal de Admin**
- **Estatísticas em Tempo Real**: Total de usuários, usuários ativos, apostas, receita
- **Métricas Semanais**: Novos usuários, apostas da semana, receita semanal
- **Usuários Mais Ativos**: Top 10 usuários com mais atividades
- **Atividades Recentes**: Log das últimas 20 atividades do sistema
- **Ações Rápidas**: Links diretos para principais funcionalidades

### ✅ **Gerenciamento de Usuários**
- **Lista Completa**: Todos os usuários com avatar, nome, email, saldo
- **Filtros Avançados**: Pesquisa por nome/email, filtro por status (ativo/banido/admin)
- **Ações por Usuário**:
  - ✅ Editar dados do usuário
  - ✅ Banir/Desbanir usuário
  - ✅ Promover/Despromover admin
  - ✅ Ajustar saldo da carteira
- **Interface Moderna**: Cards com avatares, status coloridos, ações rápidas

### ✅ **Relatórios Financeiros**
- **Métricas Principais**: Total apostado, total pago, lucro da casa, usuários ativos
- **Análise de Período**: 7, 30, 90 dias ou 1 ano
- **Gráficos Visuais**: Volume de apostas por dia com barras coloridas
- **Indicadores de Performance**: RTP, margem da casa, receita por usuário
- **Dados Detalhados**: Tabela com apostas diárias e volumes

### ✅ **Sistema de Logs**
- **Monitorização Completa**: Todas as atividades da plataforma
- **Filtros Inteligentes**: Por tipo de atividade e usuário
- **Categorização Visual**: Ícones e cores por tipo de ação
- **Detalhes Expandíveis**: JSON com dados completos de cada ação
- **Paginação**: Navegação eficiente através dos logs

### ✅ **Controlo de Acesso**
- **Middleware de Admin**: Proteção de todas as rotas administrativas
- **Verificação de Privilégios**: Apenas usuários com `is_admin = true`
- **Interface Condicional**: Botões e links só aparecem para admins
- **Logs de Ações**: Todas as ações de admin são registradas

## 🔧 Implementação Técnica

### **Rotas de Administração**
```php
// routes/web.php
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::post('/users/{user}/toggle-ban', [AdminController::class, 'toggleBanUser']);
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdminUser']);
    Route::post('/users/{user}/adjust-balance', [AdminController::class, 'adjustBalance']);
    Route::get('/reports/financial', [AdminController::class, 'financialReports']);
    Route::get('/logs', [AdminController::class, 'systemLogs']);
    // ... mais rotas
});
```

### **Middleware de Proteção**
```php
// app/Http/Middleware/AdminMiddleware.php
public function handle(Request $request, Closure $next)
{
    if (!Auth::check() || !Auth::user()->is_admin) {
        return redirect()->route('dashboard')
            ->with('error', 'Você não tem permissão para acessar esta página.');
    }
    return $next($request);
}
```

### **Controller Principal**
```php
// app/Http/Controllers/AdminController.php
class AdminController extends Controller
{
    public function dashboard() { /* Estatísticas e métricas */ }
    public function users() { /* Gerenciamento de usuários */ }
    public function toggleBanUser() { /* Banir/desbanir */ }
    public function toggleAdminUser() { /* Promover/despromover */ }
    public function adjustBalance() { /* Ajustar saldo */ }
    public function financialReports() { /* Relatórios financeiros */ }
    public function systemLogs() { /* Logs do sistema */ }
}
```

## 📁 Arquivos Criados/Modificados

### **Backend (PHP)**
1. **`app/Http/Controllers/AdminController.php`** - Controller principal com todas as funcionalidades
2. **`routes/web.php`** - Rotas de administração protegidas
3. **`app/Http/Middleware/AdminMiddleware.php`** - Middleware de proteção (já existia)

### **Frontend (Blade)**
1. **`resources/views/admin/dashboard.blade.php`** - Dashboard principal
2. **`resources/views/admin/users/index.blade.php`** - Gerenciamento de usuários
3. **`resources/views/admin/reports/financial.blade.php`** - Relatórios financeiros
4. **`resources/views/admin/logs/index.blade.php`** - Logs do sistema
5. **`resources/views/layouts/app.blade.php`** - Botão de admin no header

## 🎯 Como Acessar

### **Para Usuários Admin:**
1. **Login**: Fazer login com conta que tem `is_admin = true`
2. **Botão Admin**: Aparece no header um botão vermelho "Admin"
3. **Dashboard**: Acesso direto ao painel de administração
4. **URL Direta**: `/admin` redireciona para o dashboard

### **Funcionalidades por Página:**

#### **Dashboard (`/admin`)**
- Visão geral completa da plataforma
- Estatísticas em tempo real
- Ações rápidas para principais funcionalidades

#### **Usuários (`/admin/users`)**
- Lista todos os usuários com filtros
- Ações: editar, banir, promover, ajustar saldo
- Pesquisa por nome ou email

#### **Relatórios (`/admin/reports/financial`)**
- Análise financeira detalhada
- Gráficos de apostas por período
- Métricas de performance

#### **Logs (`/admin/logs`)**
- Histórico completo de atividades
- Filtros por tipo e usuário
- Detalhes expandíveis

## 🔒 Segurança

### **Proteções Implementadas:**
- ✅ Middleware `admin` em todas as rotas
- ✅ Verificação de `is_admin` no banco de dados
- ✅ Logs de todas as ações administrativas
- ✅ Interface condicional (só admins veem botões)
- ✅ Validação de dados em todos os formulários

### **Logs de Auditoria:**
Todas as ações de admin são registradas na tabela `activities`:
- Banir/desbanir usuários
- Promover/despromover admins
- Ajustar saldos
- Deletar grupos
- Atualizar configurações

## 🎨 Design e UX

### **Interface Moderna:**
- ✅ Design consistente com o tema cyberpunk/gaming
- ✅ Cores neon green para elementos importantes
- ✅ Cards responsivos e bem espaçados
- ✅ Ícones FontAwesome para melhor UX
- ✅ Animações suaves e feedback visual

### **Responsividade:**
- ✅ Funciona perfeitamente em desktop
- ✅ Adaptado para tablets e mobile
- ✅ Grids responsivos em todas as páginas
- ✅ Navegação otimizada para touch

## 🚀 Resultado Final

O sistema de administração oferece:
- **Controlo Total** sobre usuários e plataforma
- **Interface Profissional** e moderna
- **Funcionalidades Avançadas** de gerenciamento
- **Segurança Robusta** com logs de auditoria
- **Experiência Intuitiva** para administradores

---

**Status**: ✅ **Implementado e Funcionando**
**Acesso**: Usuários com `is_admin = true`
**URL**: `/admin`
**Versão**: 1.0
**Data**: 2025-06-28
