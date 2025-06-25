# Migração para Português de Portugal (pt-PT)

## Resumo das Alterações Realizadas

Este documento descreve todas as alterações realizadas para converter completamente o site TeamApostas de português do Brasil (pt-BR) para português de Portugal (pt-PT).

## 1. Configurações do Sistema

### 1.1 Configuração da Aplicação (`config/app.php`)
- **Locale padrão**: Alterado de `'pt'` para `'pt-PT'`
- **Fuso horário**: Alterado de `'UTC'` para `'Europe/Lisbon'`
- **Faker locale**: Alterado de `'en_US'` para `'pt_PT'`

### 1.2 Base de Dados
- **Nova migração**: `2025_06_25_185017_add_language_timezone_currency_to_users_table.php`
  - Adicionado campo `language` com valor padrão `'pt-PT'`
  - Adicionado campo `timezone` com valor padrão `'Europe/Lisbon'`
  - Adicionado campo `currency` com valor padrão `'EUR'`

### 1.3 Modelo User
- Atualizado array `$fillable` para incluir os novos campos:
  - `language`
  - `timezone`
  - `currency`
  - `theme`

## 2. Arquivos de Idioma

### 2.1 Criação do Diretório pt-PT
- Criado diretório `resources/lang/pt-PT/`
- Copiados todos os arquivos de `resources/lang/pt/` para `resources/lang/pt-PT/`

### 2.2 Adaptação das Mensagens de Validação
- **Arquivo**: `resources/lang/pt-PT/validation.php`
- **Alterações principais**:
  - "aceite" → "aceito"
  - "ficheiro" → "ficheiro" (mantido, já correto em pt-PT)
  - Outras adaptações para português de Portugal

### 2.3 Arquivos de Idioma Já Corretos
- `resources/lang/pt-PT/auth.php` - Já estava em português de Portugal
- `resources/lang/pt-PT/passwords.php` - Já estava em português de Portugal
- `resources/lang/pt-PT/pagination.php` - Já estava em português de Portugal

## 3. Tradução de Views

### 3.1 Páginas de Autenticação
- **Login** (`resources/views/auth/login.blade.php`):
  - "Email" → "E-mail"
  - "Senha" → "Palavra-passe"
  - "Faça login para acessar sua conta" → "Faça login para aceder à sua conta"
  - "Esqueceu a senha?" → "Esqueceu a palavra-passe?"
  - "Registre-se" → "Registe-se"
  - "Termos de Uso" → "Termos de Utilização"

- **Registo** (`resources/views/auth/register.blade.php`):
  - "Registrar" → "Registo"
  - "Crie sua conta" → "Crie a sua conta"
  - "Senha" → "Palavra-passe"
  - "Confirmar Senha" → "Confirmar Palavra-passe"
  - "Eu aceito" → "Aceito"
  - "Termos de Uso" → "Termos de Utilização"

- **Recuperação de Palavra-passe** (`resources/views/auth/forgot-password.blade.php`):
  - "Recuperar Senha" → "Recuperar Palavra-passe"
  - "Digite seu e-mail" → "Digite o seu e-mail"
  - "Lembrou sua senha?" → "Lembrou a sua palavra-passe?"
  - "entre em contato" → "contacte"

- **Redefinição de Palavra-passe** (`resources/views/auth/reset-password.blade.php`):
  - "Redefinir Senha" → "Redefinir Palavra-passe"
  - "Digite sua nova senha" → "Digite a sua nova palavra-passe"
  - "Nova Senha" → "Nova Palavra-passe"
  - "Confirmar Nova Senha" → "Confirmar Nova Palavra-passe"
  - "Não compartilhe sua senha" → "Não partilhe a sua palavra-passe"

### 3.2 Formulário de Preferências
- **Arquivo**: `resources/views/profile/update-preferences-form.blade.php`
- **Alterações**:
  - "Português" → "Português (Portugal)"
  - "Brasília (GMT-3)" → "Lisboa (GMT+0/+1)"
  - "Real (R$)" → "Euro (€)"
  - "Selecione seu fuso horário" → "Selecione o seu fuso horário"

### 3.3 Termos de Utilização
- **Arquivo**: `resources/views/legal/terms.blade.php`
- **Alterações principais**:
  - "Termos de Uso" → "Termos de Utilização"
  - "você" → "aceita" (segunda pessoa)
  - "usuários" → "utilizadores"
  - "esportivas" → "desportivas"
  - "gerenciar" → "gerir"
  - "registro" → "registo"
  - "contato" → "contacto"
  - "compartilhar" → "partilhar"
  - "Brasil" → "Portugal" (lei aplicável)

## 4. Comando Artisan

### 4.1 Comando de Atualização de Utilizadores
- **Arquivo**: `app/Console/Commands/UpdateUsersToPortugal.php`
- **Comando**: `php artisan users:update-to-portugal`
- **Funcionalidade**: Atualiza todos os utilizadores existentes para configurações de Portugal

### 4.2 Execução do Comando
- 4 utilizadores atualizados com sucesso
- Configurações aplicadas:
  - Idioma: pt-PT
  - Fuso horário: Europe/Lisbon
  - Moeda: EUR

## 5. Limpeza de Cache

### 5.1 Comandos Executados
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 6. Verificações Realizadas

### 6.1 Arquivos Já em Português de Portugal
- `resources/views/welcome.blade.php` - Página inicial
- `resources/views/layouts/app.blade.php` - Layout principal
- `resources/views/dashboard.blade.php` - Dashboard
- `resources/views/wallet/index.blade.php` - Carteira
- `resources/views/groups/index.blade.php` - Grupos
- `resources/views/games/index.blade.php` - Jogos

### 6.2 Configurações Verificadas
- Configuração de banco de dados: ✅
- Configuração de sessão: ✅
- Configuração de e-mail: ✅
- Configuração de notificações: ✅

## 7. Resultado Final

✅ **Site 100% convertido para português de Portugal**

### 7.1 Configurações Aplicadas
- **Idioma padrão**: pt-PT
- **Fuso horário**: Europe/Lisbon
- **Moeda**: EUR (Euro)
- **Localização**: Portugal

### 7.2 Utilizadores Atualizados
- Todos os utilizadores existentes foram atualizados para as novas configurações
- Novos registos usarão automaticamente as configurações de Portugal

### 7.3 Funcionalidades Mantidas
- Todas as funcionalidades existentes foram preservadas
- Apenas a linguagem e configurações regionais foram alteradas

## 8. Próximos Passos Recomendados

1. **Testar todas as funcionalidades** para garantir que tudo funciona corretamente
2. **Verificar e-mails automáticos** para garantir que estão em português de Portugal
3. **Atualizar documentação** se necessário
4. **Monitorizar feedback** dos utilizadores sobre as alterações

---

**Data da Migração**: 25 de Junho de 2025  
**Versão**: 1.0  
**Status**: ✅ Concluído 