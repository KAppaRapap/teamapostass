# 🖼️ Como Corrigir Imagens que Não Aparecem no Laravel Forge

## 🔍 Problema Identificado

As imagens não aparecem no seu site publicado pelo Laravel Forge devido a:

1. **Link simbólico do storage não criado** no servidor
2. **APP_URL incorreta** no arquivo `.env` do servidor
3. **Permissões incorretas** dos arquivos de upload

## 🚀 Solução Rápida

### Opção 1: Script Automático (Recomendado)

1. **Faça upload do script** `fix-images-forge.sh` para o servidor
2. **Execute no terminal SSH** do seu servidor:

```bash
# Dar permissão de execução
chmod +x fix-images-forge.sh

# Executar o script
./fix-images-forge.sh
```

### Opção 2: Comandos Manuais

Se preferir executar manualmente, siga estes passos:

#### 1. Conectar via SSH ao servidor

```bash
# Navegar para o diretório do projeto
cd /home/forge/seudominio.com
```

#### 2. Criar link simbólico do storage

```bash
# Remover link antigo se existir
rm -f public/storage

# Criar novo link simbólico
php artisan storage:link
```

#### 3. Configurar permissões

```bash
# Permissões para storage
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 storage/app/public/
chmod -R 755 public/storage/
```

#### 4. Limpar e otimizar cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## ⚙️ Configuração no Painel do Laravel Forge

### 1. Configurar Variáveis de Ambiente

No painel do Laravel Forge, vá em **Environment** e configure:

```env
APP_URL=https://seudominio.com
FILESYSTEM_DRIVER=public
```

**⚠️ IMPORTANTE:** Substitua `https://seudominio.com` pela URL real do seu site!

### 2. Script de Deploy

Adicione estes comandos ao seu **Deploy Script** no Forge:

```bash
# Após o git pull e composer install, adicione:
php artisan storage:link
chmod -R 755 storage/
chmod -R 755 public/storage/
php artisan config:cache
```

## 🔧 Verificações e Debug

### Verificar se o link simbólico está funcionando:

```bash
ls -la public/storage
```

Deve mostrar algo como:
```
lrwxrwxrwx 1 forge forge 33 Jan 15 10:30 storage -> /home/forge/seudominio.com/storage/app/public
```

### Verificar permissões:

```bash
ls -la storage/app/public/
```

### Testar uma imagem específica:

```bash
# Verificar se o arquivo existe
ls -la storage/app/public/profile_photos/

# Testar acesso via URL
curl -I https://seudominio.com/storage/profile_photos/nome-do-arquivo.jpg
```

## 🐛 Problemas Comuns e Soluções

### 1. "Link has been connected" mas imagens ainda não aparecem

**Solução:**
```bash
# Recriar o link forçadamente
rm -f public/storage
php artisan storage:link --force
```

### 2. Erro 404 nas imagens

**Causa:** APP_URL incorreta ou link simbólico quebrado

**Solução:**
1. Verificar APP_URL no `.env`
2. Recriar link simbólico
3. Limpar cache de configuração

### 3. Erro de permissões

**Solução:**
```bash
# Dar permissões corretas
sudo chown -R forge:forge storage/
sudo chown -R forge:forge public/storage/
chmod -R 755 storage/
chmod -R 755 public/storage/
```

### 4. Imagens antigas não aparecem, mas novas funcionam

**Causa:** Imagens foram enviadas antes do link simbólico ser criado

**Solução:**
1. Recriar link simbólico
2. Verificar se os arquivos existem em `storage/app/public/`
3. Se necessário, mover arquivos de `public/storage/` para `storage/app/public/`

## ✅ Teste Final

Após aplicar as correções:

1. **Faça upload de uma nova foto de perfil**
2. **Verifique se aparece corretamente**
3. **Teste em diferentes navegadores**
4. **Verifique o console do navegador** para erros 404

## 📞 Suporte

Se o problema persistir, verifique:

1. **Logs do Laravel:** `storage/logs/laravel.log`
2. **Logs do servidor:** No painel do Forge
3. **Console do navegador:** F12 → Network tab

---

**💡 Dica:** Sempre que fizer deploy de uma nova versão, execute `php artisan storage:link` para garantir que o link simbólico esteja correto.
