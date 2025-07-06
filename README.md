# 🎮 TeamApostas

**TeamApostas** é uma plataforma de apostas online inovadora que combina jogos emocionantes com funcionalidades sociais, permitindo que os utilizadores formem equipas, partilhem estratégias e apostem em conjunto.

![TeamApostas Logo](public/img/logo1111.png)

## 🌟 Características Principais

### 🎯 **Jogos Disponíveis**
- **🎲 Dice** - Jogo de dados clássico com apostas instantâneas
- **💣 Bomb Mine** - Evita as bombas e recolhe moedas
- **📉 Crash** - Aposta antes do gráfico colapsar e multiplica os ganhos

### 👥 **Sistema Social**
- **Grupos de Apostas** - Forma equipas com outros utilizadores
- **Chat em Tempo Real** - Comunica com membros do grupo
- **Partilha de Estratégias** - Observa e aprende com outros jogadores
- **Competição entre Equipas** - Compete com outras equipas

### 💰 **Sistema Financeiro**
- **Carteira Virtual** - Gestão de saldo em tempo real
- **Depósitos Seguros** - Sistema de depósitos com validação
- **Histórico de Transações** - Acompanha todas as movimentações
- **Atualizações AJAX** - Saldo atualizado automaticamente

### 🔐 **Segurança e Administração**
- **Autenticação Robusta** - Sistema de login seguro
- **Painel de Administração** - Gestão completa de utilizadores e grupos
- **Logs de Atividade** - Rastreamento de todas as ações
- **Relatórios Financeiros** - Análise detalhada das transações

## 🚀 Tecnologias Utilizadas

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS
- **Base de Dados**: MySQL
- **JavaScript**: Vanilla JS + AJAX
- **Tempo Real**: Firebase Realtime Database
- **Autenticação**: Laravel Breeze
- **Estilo**: Design Cyberpunk com cores neon

## 📋 Requisitos do Sistema

- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- MySQL 8.0+
- Extensões PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## ⚡ Instalação Rápida

### 1. **Clonar o Repositório**
```bash
git clone https://github.com/KAppaRapap/teamapostass.git
cd teamapostas
```

### 2. **Instalar Dependências**
```bash
# Dependências PHP
composer install

# Dependências JavaScript
npm install
```

### 3. **Configurar Ambiente**
```bash
# Copiar ficheiro de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 4. **Configurar Base de Dados**
Edita o ficheiro `.env` com as tuas configurações:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=teamapostas
DB_USERNAME=teu_utilizador
DB_PASSWORD=tua_password
```

### 5. **Executar Migrações**
```bash
# Criar tabelas da base de dados
php artisan migrate

# Popular com dados de exemplo (opcional)
php artisan db:seed
```

### 6. **Compilar Assets**
```bash
# Desenvolvimento
npm run dev

# Produção
npm run build
```

### 7. **Iniciar Servidor**
```bash
php artisan serve
```

Acede a `http://localhost:8000` no teu navegador.

## 🔧 Configuração Avançada

### **Firebase (Chat em Tempo Real)**
1. Cria um projeto no [Firebase Console](https://console.firebase.google.com/)
2. Ativa o Realtime Database
3. Atualiza as configurações em `resources/js/components/firebaseConfig.js`

### **Comandos Artisan Personalizados**
```bash
# Corrigir imagens e otimizar aplicação
php artisan app:fix-images

# Limpar todos os caches
php artisan optimize:clear
```

## 🗄️ Gestão da Base de Dados

### **Exportar Base de Dados (HeidiSQL)**
1. Abre o HeidiSQL e conecta-te ao servidor
2. Clica com botão direito no banco → **Exportar banco de dados como SQL**
3. Seleciona **Exportar estrutura e dados**
4. Guarda o ficheiro `.sql`

### **Importar Base de Dados (HeidiSQL)**
1. Cria novo banco de dados no HeidiSQL
2. Seleciona o banco criado
3. Menu **Ferramentas** → **Carregar arquivo SQL**
4. Seleciona o ficheiro `.sql` exportado
5. Clica **Iniciar** para importar

## 👨‍💼 Painel de Administração

### **Acesso Admin**
- URL: `/admin`
- Requer utilizador com `is_admin = true`

### **Funcionalidades Admin**
- **Dashboard** - Estatísticas gerais do sistema
- **Gestão de Utilizadores** - Banir/desbanir, tornar admin, ajustar saldo
- **Gestão de Grupos** - Visualizar e eliminar grupos
- **Relatórios Financeiros** - Análise de receitas e apostas
- **Logs do Sistema** - Histórico de atividades

## 🎨 Personalização do Design

### **Cores Principais**
```css
/* Neon Green */
--neon-green: #00FFB2;

/* Dark Background */
--dark-bg: #0a0a0a;

/* Card Background */
--card-bg: #1a1a1a;
```

### **Fontes**
- **Orbitron** - Títulos e elementos principais
- **Inter** - Texto geral

## 🚀 Deploy em Produção

### **Laravel Forge**
```bash
# Script de deploy personalizado
./fix-500-error.sh
```

### **Comandos de Produção**
```bash
# Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Criar link simbólico do storage
php artisan storage:link
```

## 🐛 Resolução de Problemas

### **Erro 500**
```bash
# Executar script de diagnóstico
./fix-500-error.sh
```

### **Problemas de Permissões**
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/ bootstrap/cache/
```

### **Cache Issues**
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📝 Estrutura do Projeto

```
teamapostas/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Models/              # Modelos Eloquent
│   └── Console/Commands/    # Comandos Artisan
├── database/
│   ├── migrations/          # Migrações da BD
│   └── seeders/            # Dados de exemplo
├── resources/
│   ├── views/              # Templates Blade
│   ├── js/                 # JavaScript
│   └── css/                # Estilos CSS
├── routes/
│   └── web.php             # Rotas da aplicação
└── public/
    ├── img/                # Imagens
    └── storage/            # Link simbólico
```

## 🤝 Contribuição

1. Faz fork do projeto
2. Cria uma branch para a tua funcionalidade (`git checkout -b feature/nova-funcionalidade`)
3. Commit das alterações (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abre um Pull Request

## 📄 Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

## 📞 Suporte

- **Email**: a36481@esas.pt
- **GitHub**: [KAppaRapap](https://github.com/KAppaRapap)
- **Repositório**: [teamapostass](https://github.com/KAppaRapap/teamapostass)

---

**Desenvolvido com ❤️ por Dinis Ivanets**

*TeamApostas - Onde as apostas se tornam sociais!* 🎮✨
