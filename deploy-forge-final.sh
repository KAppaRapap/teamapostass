#!/bin/bash

# Script de Deployment Final para Laravel Forge
# Resolve definitivamente o problema de branches divergentes

echo "🚀 Iniciando deployment - Resolvendo branches divergentes..."

cd $FORGE_SITE_PATH

# Parar qualquer processo Git em andamento
echo "🛑 Parando processos Git..."
rm -f .git/index.lock || true
rm -f .git/HEAD.lock || true

# Configurar Git de forma mais agressiva
echo "⚙️ Configurando Git..."
git config --local pull.rebase false
git config --local pull.ff false
git config --local merge.ours.driver true

# Fazer stash de qualquer mudança local
echo "💾 Salvando mudanças locais..."
git add . || true
git stash || true

# Buscar e resetar de forma forçada
echo "🔄 Sincronizando com repositório remoto..."
git fetch origin
git fetch origin main
git checkout main || git checkout -b main origin/main
git reset --hard origin/main
git clean -fdx

# Verificar se está sincronizado
echo "✅ Verificando sincronização..."
git status

# Instalar dependências
echo "📦 Instalando dependências..."
composer install --no-dev --optimize-autoloader --no-interaction

# Otimizar Laravel
echo "⚡ Otimizando aplicação..."
php artisan optimize:clear
php artisan optimize

# Definir permissões
echo "🔐 Ajustando permissões..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

echo "🎉 Deployment concluído!"
echo "📊 Commit: $(git log -1 --pretty=format:'%h - %s (%an)')"
