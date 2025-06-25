# Casino Roulette - Jogo da Roleta Clássica

## Descrição
O Casino Roulette é um jogo de roleta europeia integrado à aplicação Laravel TeamApostas. O jogo oferece uma experiência completa de casino com apostas em números, cores e paridade.

## Características

### 🎰 Interface do Jogo
- **Design Cyberpunk**: Interface moderna com gradientes azuis e efeitos neon
- **Responsivo**: Funciona em dispositivos móveis e desktop
- **Animações**: Roleta animada com efeitos visuais realistas

### 🎲 Tipos de Aposta
1. **Apostas em Números (0-90)**: Paga 35:1
   - Clique em qualquer número do tabuleiro para apostar
   - Múltiplas apostas permitidas

2. **Apostas em Cores**: Paga 1:1
   - **Vermelho**: Números ímpares (exceto 0)
   - **Preto**: Números pares (exceto 0)
   - **Verde**: Apenas o número 0

3. **Apostas em Paridade**: Paga 1:1
   - **Par**: Números pares (exceto 0)
   - **Ímpar**: Números ímpares (exceto 0)

### 💰 Sistema de Apostas
- **Fichas Disponíveis**: €5, €10, €20, €50, €100
- **Seleção de Fichas**: Clique na ficha desejada para selecionar
- **Apostas Múltiplas**: Combine diferentes tipos de aposta
- **Saldo Real**: Integração com o saldo do usuário no sistema

### 🎯 Roleta Europeia
- **37 Números**: 0-36 (padrão europeu)
- **Ordem Oficial**: Sequência real de casino
- **Animação Realista**: Giro suave com desaceleração natural
- **Ponteiro Fixo**: Indicador amarelo na borda direita

### 🏆 Sistema de Pontuação
- **High Scores**: Ranking dos melhores jogadores
- **Armazenamento Local**: Scores salvos no navegador
- **Top 5**: Exibe os 5 melhores resultados

## Como Jogar

### 1. Acessar o Jogo
- Faça login na aplicação
- Navegue para "Jogos de Casino" → "Roleta"
- Ou acesse diretamente: `/games/roleta-classica`

### 2. Fazer Apostas
1. **Selecione uma Ficha**: Clique em uma das fichas disponíveis (€5-€100)
2. **Escolha suas Apostas**:
   - Clique nos números desejados no tabuleiro
   - Clique em "RED" ou "BLACK" para apostar em cores
   - Clique em "ODD" ou "EVEN" para apostar em paridade
3. **Verifique suas Apostas**: Os valores aparecem abaixo das opções selecionadas

### 3. Girar a Roleta
- Clique no botão "Spin Roulette"
- A roleta girará com animação realista
- O resultado será exibido automaticamente

### 4. Receber Pagamentos
- **Vitórias**: O valor ganho é adicionado ao seu saldo
- **Derrotas**: O valor apostado é debitado do seu saldo
- **Histórico**: Todas as apostas são registradas no sistema

## Integração com Laravel

### APIs Disponíveis
- `POST /api/apostar`: Registra uma aposta
- `POST /api/ganhar`: Registra um ganho

### Modelo de Dados
- **BettingSlip**: Registra todas as apostas e ganhos
- **User**: Saldo atualizado automaticamente
- **Histórico**: Rastreamento completo das atividades

### Segurança
- **Autenticação**: Apenas usuários logados podem jogar
- **Validação**: Verificação de saldo antes das apostas
- **CSRF Protection**: Proteção contra ataques CSRF

## Arquivos Principais

### Frontend
- `resources/views/games/roleta-classica.blade.php`: Template principal
- `public/css/roleta-classica.css`: Estilos do jogo
- `public/js/roleta-classica.js`: Lógica do jogo

### Backend
- `app/Http/Controllers/GameController.php`: Controlador do jogo
- `routes/api.php`: Rotas da API
- `app/Models/BettingSlip.php`: Modelo de dados

## Personalização

### Cores e Estilo
O jogo usa um tema cyberpunk com:
- Gradientes azuis (#010130, #001f54)
- Efeitos neon (text-shadow)
- Bordas ciano (#00ffff)
- Destaques magenta (#ff00ff)

### Configuração
- **Números da Roleta**: Editável em `roleta-classica.js`
- **Valores das Fichas**: Modificável no template
- **Odds**: Configuráveis na função `evaluateBets()`

## Suporte

Para dúvidas ou problemas:
1. Verifique o console do navegador para erros JavaScript
2. Consulte os logs do Laravel em `storage/logs/`
3. Verifique a conectividade com as APIs

## Licença
Este jogo é parte da aplicação TeamApostas e segue as mesmas políticas de licenciamento. 