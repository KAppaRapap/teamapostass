


Entendido! O HeidiSQL é uma ótima ferramenta para gerenciar bancos de dados MySQL. Vou explicar como exportar e importar o banco de dados usando o HeidiSQL.

---

### **Passo 1: Exportar o Banco de Dados no Computador da Empresa**

1. **Abra o HeidiSQL** e conecte-se ao servidor do banco de dados.
2. Na lista de bancos de dados à esquerda, clique com o botão direito sobre o banco que você deseja exportar.
3. Selecione a opção **Exportar banco de dados como SQL**.
4. Na janela que abrir:

   * Certifique-se de que a opção **Exportar estrutura e dados** está selecionada.
   * Escolha onde salvar o arquivo `.sql` (por exemplo, `banco.sql`).
5. Clique em **Iniciar** para exportar o banco.
6. Transfira o arquivo `.sql` para o seu computador de casa (por exemplo, usando um pendrive ou um serviço na nuvem).

---

### **Passo 2: Importar o Banco de Dados no Computador de Casa**

1. **Abra o HeidiSQL** no seu computador de casa e conecte-se ao servidor do banco de dados local.
2. Crie um novo banco de dados:

   * Clique com o botão direito em um espaço vazio na lista de bancos de dados e selecione **Criar novo banco de dados**.
   * Escolha um nome para o banco (por exemplo, `meu_banco`).
3. Clique duas vezes no banco recém-criado para selecioná-lo.
4. Clique no menu **Ferramentas** e escolha a opção **Carregar arquivo SQL**.
5. Selecione o arquivo `.sql` exportado no computador da empresa.
6. Clique em **Iniciar** para importar os dados.

---

### **Passo 3: Configurar o `.env` no Projeto Laravel**

1. No arquivo `.env` do projeto em casa, atualize as informações para refletir o banco de dados local:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=meu_banco
   DB_USERNAME=usuario_local
   DB_PASSWORD=senha_local
   ```

   Substitua `meu_banco`, `usuario_local` e `senha_local` pelos dados configurados no seu servidor MySQL local.

---

### **Passo 4: Testar o Projeto**

Depois de configurar o banco de dados, inicie o servidor do Laravel e acesse o projeto para garantir que tudo está funcionando corretamente:

```bash
php artisan serve
```

Abra o navegador e acesse `http://127.0.0.1:8000`.
