#!/bin/bash

# Script para corrigir erro 500 no Laravel
echo "🚨 Diagnosticando e corrigindo erro 500..."

cd $FORGE_SITE_PATH

echo "📍 Diretório atual: $(pwd)"

# 1. Verificar se o arquivo .env existe
echo "🔍 Verificando arquivo .env..."
if [ ! -f .env ]; then
    echo "❌ Arquivo .env não encontrado! Criando..."
    cp .env.example .env
    echo "✅ Arquivo .env criado"
else
    echo "✅ Arquivo .env encontrado"
fi

# 2. Verificar APP_KEY
echo "🔑 Verificando APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env; then
    echo "❌ APP_KEY não configurada! Gerando..."
    php artisan key:generate --force
    echo "✅ APP_KEY gerada"
else
    echo "✅ APP_KEY já configurada"
fi

# 3. Verificar permissões críticas
echo "🔐 Corrigindo permissões..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
chown -R www-data:www-data storage/ bootstrap/cache/ || true
echo "✅ Permissões corrigidas"

# 4. Limpar todos os caches
echo "🧹 Limpando todos os caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
echo "✅ Caches limpos"

# 5. Recriar link simbólico do storage
echo "🔗 Recriando link do storage..."
rm -f public/storage
php artisan storage:link --force
echo "✅ Link do storage criado"

# 6. Verificar se o banco de dados está acessível
echo "🗄️ Testando conexão com banco de dados..."
if php artisan migrate:status > /dev/null 2>&1; then
    echo "✅ Conexão com banco OK"
else
    echo "⚠️ Problema na conexão com banco - verifique configurações .env"
fi

# 7. Recriar caches de produção
echo "🚀 Recriando caches de produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Caches de produção criados"

# 8. Verificar logs de erro
echo "📋 Verificando logs de erro..."
if [ -f storage/logs/laravel.log ]; then
    echo "📄 Últimos erros no log:"
    tail -20 storage/logs/laravel.log
else
    echo "ℹ️ Nenhum log de erro encontrado"
fi

# 9. Testar se a aplicação está funcionando
echo "🧪 Testando aplicação..."
if php artisan route:list > /dev/null 2>&1; then
    echo "✅ Aplicação Laravel funcionando"
else
    echo "❌ Ainda há problemas na aplicação"
fi

echo ""
echo "🎉 Diagnóstico concluído!"
echo ""
echo "📝 Se o erro 500 persistir, verifique:"
echo "1. Configurações do banco de dados no .env"
echo "2. APP_URL no .env deve ser a URL real do site"
echo "3. Logs em storage/logs/laravel.log"
echo "4. Configurações do servidor web (Nginx/Apache)"
echo ""
echo "🔧 Comandos úteis para debug:"
echo "- Ver logs: tail -f storage/logs/laravel.log"
echo "- Testar config: php artisan config:show"
echo "- Verificar rotas: php artisan route:list"
