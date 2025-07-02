#!/bin/bash

# Script de Deployment Laravel Forge
# Resolve problemas de branches divergentes e garante deployment correto

set -e

echo "🚀 Iniciando deployment..."

# Navegar para o diretório do site
cd $FORGE_SITE_PATH

echo "📁 Diretório atual: $(pwd)"

# Configurar Git para lidar com branches divergentes
echo "⚙️ Configurando Git..."
git config pull.rebase false
git config pull.ff false

# Fazer backup do estado atual (opcional)
echo "💾 Fazendo backup do estado atual..."
git stash push -m "Backup antes do deployment $(date)" || true

# Buscar as últimas mudanças do remoto
echo "📥 Buscando mudanças do repositório remoto..."
git fetch origin main --force

# Forçar sincronização com o repositório remoto
echo "🔄 Forçando sincronização com o repositório remoto..."
git reset --hard origin/main

# Limpar arquivos não rastreados
echo "🧹 Limpando arquivos não rastreados..."
git clean -fd

# Instalar/atualizar dependências do Composer
echo "📦 Instalando dependências do Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

# Verificar se o arquivo .env existe
if [ ! -f .env ]; then
    echo "⚠️ Arquivo .env não encontrado! Copiando do .env.example..."
    cp .env.example .env
fi

# Gerar APP_KEY se não existir
echo "🔑 Verificando APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Gerando nova APP_KEY..."
    php artisan key:generate --force
fi

# Criar link simbólico do storage
echo "🔗 Criando link simbólico do storage..."
php artisan storage:link --force

# Limpar todos os caches antes de recriar
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Definir permissões adequadas ANTES de cachear
echo "🔐 Definindo permissões..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public/storage

# Cachear configurações para produção
echo "🚀 Cacheando configurações para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar se tudo está funcionando
echo "✅ Verificando status do Git..."
git status --porcelain

echo "🎉 Deployment concluído com sucesso!"
echo "📊 Commit atual: $(git rev-parse --short HEAD)"
echo "🌿 Branch: $(git branch --show-current)"
