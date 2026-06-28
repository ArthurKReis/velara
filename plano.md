# PLANO DE IMPLEMENTAÇÃO
## Projeto: Shin Megami Tensei – Team Builder
**Data:** 28/06/2026  
**Versão:** 1.0

---

## 1. CONTEXTO

| **Item** | **Descrição** |
| :--- | :--- |
| **Objetivo da Aplicação** | Desenvolver uma aplicação web que permita aos fãs da franquia *Shin Megami Tensei* (SMT) montarem e gerenciarem times de até 5 demônios, consultarem a base de dados de demônios e visualizarem as combinações de fusão disponíveis no jogo *Shin Megami Tensei I*. |
| **Problema que Resolve** | Em jogos SMT, gerenciar manualmente quais demônios compõem o time, suas estatísticas e as complexas regras de fusão é trabalhoso e propenso a erros. Esta aplicação centraliza essas informações, permitindo que o jogador planeje estrategicamente suas fusões e times em um único lugar, com dados extraídos diretamente de fontes comunitárias confiáveis. |
| **Público-alvo** | Jogadores da franquia SMT (especialmente do SMT I), entusiastas de RPGs de turno e fãs de jogos da Atlus que desejam otimizar suas escolhas de demônios sem precisar consultar múltiplas planilhas ou sites externos durante o jogo. |

---

## 2. ESCOPO

### 2.1. Funcionalidades (Priorizadas)

| **Prioridade** | **Funcionalidade** | **Descrição** |
| :--- | :--- | :--- |
| **Essencial (P0)** | Autenticação de Usuários | Sistema de login, registro e logout implementado com o scaffolding nativo do Laravel (Breeze ou Jetstream, com Boost). |
| **Essencial (P0)** | CRUD de Times (Teams) | Operações completas de **C**reate, **R**ead, **U**pdate, **D**elete para os times de demônios do usuário. Cada time deve ter nome, descrição opcional e uma lista de até 5 demônios. |
| **Essencial (P0)** | Importação/Listagem de Demônios | População da tabela `demons` via Seeder a partir de um JSON público (fonte: megaten-fusion-tool). O usuário deve poder visualizar a lista completa de demônios disponíveis. |
| **Importante (P1)** | Visualização de Detalhes do Demônio | Página de detalhes que exibe estatísticas, resistências e, crucialmente, as **combinações de fusão** (read-only) em que aquele demônio participa como ingrediente ou resultado. |
| **Importante (P1)** | Validação de Time (Regras de Negócio) | Garantir que um time não exceda 5 demônios e que um mesmo demônio não seja duplicado dentro do mesmo time. |
| **Desejável (P2)** | API REST Básica | Exposição dos endpoints de listagem de demônios e times via API (JSON) para futura integração com outros frontends. |

### 2.2. Entidades do Banco de Dados (Modelagem Conceitual)

| **Entidade** | **Atributos Principais** | **Relacionamentos** |
| :--- | :--- | :--- |
| **User** (*usuários*) | `id`, `name`, `email`, `password`, `remember_token`, `timestamps` | (Padrão Laravel) - Um usuário pode ter muitos times. |
| **Demon** (*demônios*) | `id`, `name` (string), `race` (string), `level` (integer), `strength` (integer), `magic` (integer), `vitality` (integer), `agility` (integer), `luck` (integer), `res_fire` (string), `res_ice`, `res_elec`, `res_force`, `res_light`, `res_dark`, `image_url` (string, opcional), `timestamps`. | Relacionamento N:N com Teams via tabela pivô. Relacionamento 1:N com Fusions (como ingrediente ou resultado). |
| **Team** (*times*) | `id`, `user_id` (foreign key), `name` (string, obrigatório), `description` (text, opcional), `timestamps`. | Pertence a um usuário. Relaciona-se com Demônios via pivô. |
| **DemonTeam** (*pivô*) | `id`, `team_id`, `demon_id`, `position` (integer, entre 1 e 5, único por time). | Controla a ordem e a quantidade de demônios no time. |
| **Fusion** (*fusões*) | `id`, `demon_a_id` (FK), `demon_b_id` (FK), `demon_result_id` (FK). | Tabela dedicada para armazenar as receitas de fusão extraídas do JSON. Permite consulta rápida (read-only). |

### 2.3. Telas Previstas (UX)

| **Rota/Módulo** | **Tela** | **Descrição da Interface** |
| :--- | :--- | :--- |
| `guest` | Login / Registro | Tela limpa com formulários centralizados, utilizando a identidade visual do SMT (tons escuros, letras brancas/amarelas, detalhes em vermelho). |
| `authenticated` | Dashboard | Página inicial após o login, exibindo um resumo dos times do usuário e um atalho para a lista de demônios. |
| `authenticated` | Lista de Demônios | Tabela paginada com todos os demônios disponíveis, com campo de busca por nome/raça. |
| `authenticated` | Detalhes do Demônio | Página com informações completas do demônio. Na parte inferior, listagem das fusões (ingredientes -> resultado). |
| `authenticated` | Lista de Times (Index) | Exibição de todos os times do usuário logado em cards ou lista, com botões para editar, visualizar e deletar. |
| `authenticated` | Criar Time (Create) | Formulário com campos `nome` e `descrição`. Um seletor para adicionar até 5 demônios (com busca/autocomplete). |
| `authenticated` | Editar Time (Edit) | Mesmo layout do Create, porém com os dados atuais preenchidos. |
| `authenticated` | Visualizar Time (Show) | Exibe o time em detalhe, com os 5 demônios ordenados por `position`, mostrando nome, raça e nível de cada um. |

### 2.4. Ordem de Implementação (Etapas para Commits)

*A ordem foi pensada para permitir commits incrementais, conforme exigência do PDF (mínimo de 10 commits distribuídos).*

| **Etapa** | **Descrição** | **Entregáveis / Commits** |
| :--- | :--- | :--- |
| **1** | **Configuração Inicial do Projeto** | Criação do projeto Laravel via Composer. Configuração do `.env` para MySQL. Criação do repositório Git e primeiro commit (`feat: initial laravel setup`). |
| **2** | **Instalação do Laravel Boost** | Executar `composer require laravel/boost --dev` e `php artisan boost:install`. Commit da estrutura do Boost (`feat: install laravel boost`). |
| **3** | **Autenticação (Auth)** | Instalar o Laravel Breeze (ou similar). Configurar as views de login/registro. Commit (`feat: add authentication scaffold`). |
| **4** | **Modelagem e Migrations** | Criar Migrations para as 4 tabelas: `demons`, `teams`, `demon_team`, `fusions`. Definir as FKs e índices. Commit (`feat: create database schema with migrations`). |
| **5** | **Seeder de Demônios (Base de Dados)** | Baixar o JSON do site (ex: via MCP HTTP ou manual). Criar um Seeder (`DemonSeeder`) que leia o JSON e insira os dados na tabela `demons`. Criar Seeder para `Users` (admin e test). Commit (`feat: seed demons and test users`). |
| **6** | **Configuração e Uso do MCP** | Documentar e implementar a lógica de consumo do JSON usando o MCP de Filesystem/HTTP. Commit (`feat: use MCP for data import`). |
| **7** | **Criação das Skills (Parte 4)** | Criar os 4 arquivos de Skills na pasta do Boost: `SkillCRUD.md`, `SkillVisual.md`, `SkillDatabase.md`, `SkillAPI.md`. Commit (`feat: add boost skills`). |
| **8** | **Backend - CRUD de Times** | Criar Model `Team` e Model `Demon` (com relacionamentos). Criar Controller `TeamController`, Form Requests (Store/Update), aplicar as validações de limite de 5 demônios e posição. Registrar rotas em `web.php`. Commit (`feat: implement team CRUD backend logic`). |
| **9** | **Views - CRUD de Times** | Criar as views Blade para `index`, `create`, `edit` e `show` de times, aplicando a Skill de Identidade Visual (Layout, cores, responsividade). Commit (`feat: implement team CRUD views`). |
| **10** | **Listagem e Detalhes de Demônios + Fusões** | Criar `DemonController` com métodos `index` e `show`. Na `show`, listar as fusões importadas (relacionamento via Model `Fusion`). Criar as respectivas views. Commit (`feat: add demon listing and fusion details`). |
| **11** | **API REST (Skill Adicional)** | Criar `api.php` rotas para `/demons`, `/teams`. Criar Resources (API Resources) para padronizar as saídas JSON. Commit (`feat: add REST API endpoints`). |
| **12** | **Revisão, Testes e Documentação** | Validar todos os CRUDs, corrigir bugs. Escrever `README.md` (com credenciais) e `RELATORIO.md`. Commit final (`docs: add readme and final report`). |

---

## 3. TÉCNICO

### 3.1. Tecnologias Utilizadas

| **Camada** | **Tecnologia / Ferramenta** | **Versão / Especificação** |
| :--- | :--- | :--- |
| **Backend** | PHP com Framework Laravel | 11.x |
| **Assistente de Desenvolvimento** | Laravel Boost | Pacote dev para gerenciamento de Skills e padronização com IA. |
| **Banco de Dados** | MySQL | 8.0+ (ou compatível). |
| **Frontend (Views)** | Blade (Laravel) + Bootstrap 5 | Layout customizado com identidade visual SMT. |
| **Cache/Assets** | Vite (Padrão Laravel) | Para compilar CSS/JS. |
| **Versionamento** | Git + GitHub | Mínimo de 10 commits. |
| **IA/Assistência** | MCP (HTTP e Filesystem) | Para consumo e leitura do JSON externo. |

### 3.2. Riscos Mapeados e Estratégias de Mitigação

| **Risco** | **Impacto** | **Mitigação / Plano de Contingência** |
| :--- | :--- | :--- |
| **Formato do JSON externo mudar ou ficar indisponível** | Impossibilidade de popular a tabela `demons` e `fusions`. | Salvar uma cópia estática do JSON na pasta `storage/app/demons.json` e usá-la como fallback. Utilizar o MCP Filesystem para leitura local, garantindo que os seeders sempre funcionem. |
| **Dados do JSON não mapearem 1:1 com as colunas do MySQL** | Quebra na execução do Seeder. | Criar uma função de "parse" no Seeder que mapeie os campos (ex: `str` -> `strength`). Usar valores padrão (ex: `null` ou `0`) para campos não encontrados. |
| **Validação de limite de 5 demônios falhar em edições** | Time com mais de 5 demônios corrompe a regra de negócio. | Implementar validação customizada no `FormRequest` que conte os demônios no request e impeça a submissão se exceder 5. Adicionar regra no banco (índice único composto por `team_id + position` com constraint `position BETWEEN 1 AND 5`). |
| **Cronograma apertado** | Não entregar as Skills opcionais a tempo. | Priorizar a Skill de Database (essencial para Seeders) e a Skill de API (que pode ser simplificada para apenas 2 endpoints). |

### 3.3. Critérios de Aceite (Definition of Done)

Para que a aplicação seja considerada completa e aprovada, cada um dos itens abaixo deve estar funcionando e documentado:

1. **Instalação**: O projeto roda perfeitamente com `php artisan serve` após o clone, com todas as dependências instaladas.
2. **Banco de Dados**: As migrações criam todas as tabelas sem erros. Os seeders populam a tabela `demons`, `fusions` e criam os usuários de teste.
3. **Autenticação**: O usuário `user@smt.com` e `admin@smt.com` conseguem fazer login.
4. **CRUD de Times**: O usuário logado consegue criar, listar, editar e deletar seus times. A edição permite adicionar/remover demônios, respeitando o limite de 5.
5. **Demônios e Fusões**: A listagem de demônios exibe todos os registros. A página de detalhes de um demônio exibe a lista de fusões (read-only) corretamente.
6. **MCP**: O relatório (`RELATORIO.md`) contém a documentação do MCP utilizado, com exemplos práticos.
7. **Skills**: Os 4 arquivos de Skill estão na pasta correta do Laravel Boost, com conteúdo coerente e aplicado ao código.
8. **Commits**: O repositório contém no mínimo 10 commits distribuídos ao longo das etapas, e não concentrados em um único dia.
9. **Documentação**: `README.md` contém todas as instruções de instalação e as credenciais de teste. `RELATORIO.md` contém todas as seções exigidas.

---

## 4. RESUMO DAS DECISÕES TÉCNICAS E DE ESCOPO

- **Tema:** Shin Megami Tensei – Montador de Times.
- **CRUD Principal:** Times (Teams), com até 5 demônios.
- **Fusões:** Abordagem *Read-Only* (visualização estática dos dados importados, sem engine de cálculo dinâmico).
- **Banco de Dados:** MySQL.
- **MCPs:** HTTP/API + Filesystem (para importação dos dados).
- **Skills Obrigatórias:** CRUD e Identidade Visual.
- **Skills Adicionais:** Banco de Dados/Seeders e API REST.
- **Usuários de Teste:** 
  - Admin: `admin@smt.com` / `password`
  - Comum: `user@smt.com` / `password`

---

**Este plano está formalmente estabelecido e servirá como guia para todas as etapas de desenvolvimento.**