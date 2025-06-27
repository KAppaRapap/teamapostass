# 🖼️ SOLUÇÃO: Imagens não aparecem no Laravel Forge

## 🎯 Solução Rápida (3 opções)

### Opção 1: Comando Artisan (Mais Fácil)
```bash
php artisan fix:images
```

### Opção 2: Script Bash
```bash
chmod +x fix-images-forge.sh
./fix-images-forge.sh
```

### Opção 3: Comandos Manuais
```bash
php artisan storage:link
chmod -R 755 storage/
chmod -R 755 public/storage/
php artisan config:clear
php artisan cache:clear
```

## ⚙️ Configuração no Laravel Forge

### 1. Variáveis de Ambiente
No painel do Forge, configure:
```env
APP_URL=https://seudominio.com
FILESYSTEM_DRIVER=public
```

### 2. Deploy Script
Adicione ao Deploy Script do Forge:
```bash
php artisan storage:link
chmod -R 755 storage/
php artisan config:cache
```

## 🔍 Verificação
Teste se funcionou:
```bash
ls -la public/storage  # Deve mostrar link simbólico
curl -I https://seudominio.com/storage/profile_photos/alguma-imagem.jpg
```

## 📞 Se ainda não funcionar
1. Verifique se APP_URL está correta
2. Execute: `php artisan fix:images --force`
3. Verifique logs: `storage/logs/laravel.log`

---
**✅ Após seguir estes passos, suas imagens devem aparecer normalmente!**
