#!/bin/bash

echo "🚀 Iniciando deploy do Team Apostas..."

# Verificar se o Vercel CLI está instalado
if ! command -v vercel &> /dev/null; then
    echo "❌ Vercel CLI não encontrado. Instalando..."
    npm install -g vercel
fi

# Verificar se está logado no Vercel
if ! vercel whoami &> /dev/null; then
    echo "🔐 Fazendo login no Vercel..."
    vercel login
fi

# Gerar APP_KEY se não existir
if [ -z "$APP_KEY" ]; then
    echo "🔑 Gerando APP_KEY..."
    APP_KEY=$(php artisan key:generate --show)
    echo "APP_KEY gerado: $APP_KEY"
    echo "⚠️  Lembre-se de adicionar esta chave no painel do Vercel!"
fi

# Build dos assets
echo "📦 Fazendo build dos assets..."
npm run production

# Deploy
echo "🚀 Fazendo deploy..."
if [ "$1" = "--prod" ]; then
    vercel --prod
else
    vercel
fi

echo "✅ Deploy concluído!"
echo "📝 Próximos passos:"
echo "1. Configure as variáveis de ambiente no painel do Vercel"
echo "2. Execute as migrations: php artisan migrate"
echo "3. Configure seu domínio personalizado" 