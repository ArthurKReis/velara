Abaixo está a versão revisada do `README.md`, com linguagem mais técnica e sem o uso de emojis.

---

```markdown
# SMT Team Builder

**Shin Megami Tensei – Team Builder**

Aplicaçao web para gerenciamento de times de demonios da franquia Shin Megami Tensei, permitindo montar times com ate 5 demonios, consultar estatisticas, resistencias e visualizar informacoes sobre fusoes.

---

## Descricao da Aplicacao

- Autenticacao de usuarios (login, registro, logout)
- Listagem completa de demonios com busca e paginacao
- CRUD completo de times (criar, editar, visualizar, excluir)
- Associacao de ate 5 demonios por time, com validacao de posicao
- Visualizacao de detalhes do demonio (estatisticas, resistencias)
- API REST com autenticacao via token (Sanctum)
- Interface responsiva com identidade visual SMT

---

## Tecnologias Utilizadas

- Backend: PHP 8.2, Laravel 12
- Banco de Dados: MySQL 8.0+
- Frontend: Blade, Bootstrap 5
- Autenticacao: Laravel Breeze (web) + Sanctum (API)
- Assistente de Desenvolvimento: Laravel Boost
- Versionamento: Git + GitHub
- Ferramentas de IA: MCP Filesystem para importacao de dados

---

## Pre-requisitos

Antes de iniciar, certifique-se de ter instalado:

- PHP 8.2 ou superior
- Composer
- MySQL 8.0 ou superior
- Node.js e NPM (para compilacao de assets)
- Git

---

## Instalacao e Configuracao

### 1. Clonar o repositorio

```bash
git clone <URL_DO_REPOSITORIO>
cd velara
```

### 2. Instalar as dependencias do PHP

```bash
composer install
```

### 3. Instalar as dependencias do Node.js

```bash
npm install
```

### 4. Configurar o arquivo .env

Copie o arquivo de exemplo e edite as credenciais do banco de dados:

```bash
cp .env.example .env
```

Abra o `.env` e ajuste as variaveis do MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smt_team_builder
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Executar o comando de setup automatizado

Este comando criara o banco de dados (se nao existir), executara as migracoes e os seeders:

```bash
php artisan app:setup
```

### 6. Compilar os assets

```bash
npm run build
```

### 7. Iniciar o servidor

```bash
php artisan serve
```

A aplicacao estara disponivel em: `http://localhost:8000`

---

## Usuarios de Teste

Os seguintes usuarios sao criados automaticamente pelos seeders:

| Perfil | E-mail | Senha | Acesso |
|--------|--------|-------|--------|
| Administrador | `admin@smt.com` | `password` | Pode gerenciar todos os times (exemplo) |
| Usuario Comum | `user@smt.com` | `password` | Gerencia apenas seus proprios times |

---

## Comandos Uteis

| Comando | Descricao |
|---------|-----------|
| `php artisan app:setup` | Configura o ambiente (cria banco, migracoes e seeders) |
| `php artisan serve` | Inicia o servidor de desenvolvimento |
| `php artisan migrate:fresh --seed` | Recria o banco e popula com dados iniciais |
| `php artisan route:list` | Lista todas as rotas |
| `php artisan boost:install --skills` | Recria a estrutura de Skills (se necessario) |

---

## Estrutura do Projeto

- `app/` – Codigo-fonte (Models, Controllers, Policies, etc.)
- `database/` – Migrations, Seeders e dados
- `resources/views/` – Templates Blade
- `routes/` – Definicoes de rotas (web e api)
- `.ai/skills/` – Skills do Laravel Boost
- `storage/app/demons.json` – Dados dos demonios

---

## Documentacao Adicional

- Plano de Implementacao: `PLANO_DE_IMPLEMENTACAO.md`
- Relatorio do Projeto: `RELATORIO.md`

---

## Contribuicao

Este projeto foi desenvolvido como atividade academica. Para contribuicoes, entre em contato com o autor.

---

## Licenca

Este projeto e de uso academico e nao possui licenca comercial.

---

**Desenvolvido para o IFSP – Prof. Dr. Reginaldo do Prado**