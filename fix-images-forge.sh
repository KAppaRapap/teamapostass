#!/bin/bash

echo "🖼️  Corrigindo problema das imagens no Laravel Forge..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para imprimir mensagens coloridas
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    print_error "Este script deve ser executado na raiz do projeto Laravel!"
    exit 1
fi

print_status "Verificando estrutura do projeto..."

# 2. Criar diretórios necessários se não existirem
print_status "Criando diretórios necessários..."

if [ ! -d "storage/app/public" ]; then
    mkdir -p storage/app/public
    print_success "Diretório storage/app/public criado"
fi

if [ ! -d "storage/app/public/profile_photos" ]; then
    mkdir -p storage/app/public/profile_photos
    print_success "Diretório storage/app/public/profile_photos criado"
fi

if [ ! -d "storage/app/public/chat_uploads" ]; then
    mkdir -p storage/app/public/chat_uploads
    print_success "Diretório storage/app/public/chat_uploads criado"
fi

# 3. Criar link simbólico do storage
print_status "Criando link simbólico do storage..."

# Remover link existente se houver
if [ -L "public/storage" ]; then
    rm public/storage
    print_warning "Link simbólico antigo removido"
fi

# Criar novo link simbólico
php artisan storage:link

if [ $? -eq 0 ]; then
    print_success "Link simbólico criado com sucesso!"
else
    print_error "Erro ao criar link simbólico"
    exit 1
fi

# 4. Configurar permissões corretas
print_status "Configurando permissões..."

# Permissões para storage
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Permissões específicas para uploads
chmod -R 755 storage/app/public/
chmod -R 755 public/storage/

print_success "Permissões configuradas!"

# 5. Verificar se o link simbólico está funcionando
print_status "Verificando link simbólico..."

if [ -L "public/storage" ] && [ -d "public/storage" ]; then
    print_success "Link simbólico está funcionando corretamente!"
else
    print_error "Problema com o link simbólico!"
    exit 1
fi

# 6. Limpar cache
print_status "Limpando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

print_success "Cache limpo!"

# 7. Otimizar aplicação
print_status "Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_success "Aplicação otimizada!"

echo ""
echo "✅ Correção das imagens concluída!"
echo ""
echo "📝 Próximos passos no Laravel Forge:"
echo "1. Certifique-se de que a variável APP_URL está configurada corretamente"
echo "2. Exemplo: APP_URL=https://seudominio.com"
echo "3. Execute este script no servidor via SSH ou no painel do Forge"
echo "4. Teste o upload de uma nova imagem"
echo ""
echo "🔧 Comandos úteis para debug:"
echo "- Verificar link: ls -la public/storage"
echo "- Verificar permissões: ls -la storage/app/public/"
echo "- Recriar link: php artisan storage:link"
echo ""
