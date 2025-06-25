# 🚀 Guia de Deploy - Team Apostas

## Pré-requisitos

1. **Conta no Vercel** - [vercel.com](https://vercel.com)
2. **Database** - PlanetScale, Railway ou Supabase
3. **Vercel CLI** instalado

## Passo a Passo

### 1. Configurar Database

#### PlanetScale (Recomendado)
1. Acesse [planetscale.com](https://planetscale.com)
2. Crie uma conta gratuita
3. Crie um novo projeto
4. Crie um branch de produção
5. Copie a connection string

#### Railway
1. Acesse [railway.app](https://railway.app)
2. Conecte com GitHub
3. Crie um novo projeto
4. Adicione MySQL
5. Copie as credenciais

### 2. Configurar Vercel

#### Variáveis de Ambiente Necessárias:

```bash
APP_NAME="Team Apostas"
APP_ENV=production
APP_KEY=base64:sua_chave_aqui
APP_DEBUG=false
APP_URL=https://seu-app.vercel.app

# Database
DB_CONNECTION=mysql
DB_HOST=seu-host
DB_PORT=3306
DB_DATABASE=teamapostas
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Firebase (se usar)
FIREBASE_CREDENTIALS={"type":"service_account"}
FIREBASE_DATABASE_URL=https://seu-projeto.firebaseio.com
FIREBASE_PROJECT_ID=seu-projeto-id

# API Keys
FOOTBALL_API_KEY=sua_chave_api
```

### 3. Deploy

```bash
# Login no Vercel
vercel login

# Deploy inicial
vercel

# Deploy para produção
vercel --prod
```

### 4. Executar Migrations

Após o deploy, execute as migrations:

```bash
# Via Vercel CLI
vercel env pull .env.production
php artisan migrate --env=production

# Ou via Vercel Functions
# Crie uma função temporária para executar migrations
```

### 5. Configurar Domínio

1. No painel do Vercel, vá em "Settings" > "Domains"
2. Adicione seu domínio
3. Configure os DNS conforme instruído

## Troubleshooting

### Erro de Database
- Verifique se as credenciais estão corretas
- Confirme se o host permite conexões externas
- Teste a conexão localmente

### Erro de APP_KEY
- Gere uma nova chave: `php artisan key:generate --show`
- Atualize no painel do Vercel

### Erro de Storage
- O Vercel não suporta uploads persistentes
- Use serviços como AWS S3 ou Cloudinary

## Comandos Úteis

```bash
# Ver logs
vercel logs

# Listar deployments
vercel ls

# Remover deployment
vercel remove

# Configurar projeto
vercel link
``` 