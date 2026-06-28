# PLANO DE IMPLEMENTACAO

## Projeto: Shin Megami Tensei – Team Builder

**Data:** 28/06/2026
**Versao:** 1.0

---

## 1. CONTEXTO

### Objetivo da Aplicacao

Desenvolver uma aplicacao web que permita aos fas da franquia Shin Megami Tensei (SMT) montarem e gerenciarem times de ate 5 demonios.

A aplicacao tambem deve permitir a consulta a base de dados de demonios e a visualizacao das combinacoes de fusao disponiveis no jogo Shin Megami Tensei I, utilizando dados extraidos de fontes comunitarias confiaveis.

### Problema que Resolve

Nos jogos SMT, gerenciar manualmente quais demonios compoem o time, suas estatisticas e as complexas regras de fusao e uma tarefa trabalhosa e propensa a erros.

Esta aplicacao centraliza essas informacoes em um unico ambiente, permitindo que o jogador planeje estrategicamente suas fusoes e times sem precisar consultar multiplas planilhas ou sites externos durante o jogo.

### Publico-alvo

Jogadores da franquia SMT (especialmente do SMT I), entusiastas de RPGs de turno e fas de jogos da Atlus.

O publico busca otimizar suas escolhas de demonios e deseja ter uma ferramenta pratica para experimentar composicoes de time antes de aplica-las no jogo.

---

## 2. ESCOPO

### 2.1. Funcionalidades (Priorizadas)

A lista abaixo organiza as funcionalidades por prioridade, sendo P0 (essencial), P1 (importante) e P2 (desejavel).

- Autenticacao de Usuarios (P0): Sistema de login, registro e logout implementado com o scaffolding nativo do Laravel (Breeze ou similar, integrado ao Boost).
- CRUD de Times (P0): Operacoes completas de Criacao, Leitura, Atualizacao e Delecao para os times de demonios do usuario. Cada time deve ter nome, descricao opcional e uma lista de ate 5 demonios. 
- Importacao e Listagem de Demonios (P0): Povoamento da tabela `demons` via Seeder a partir de um JSON publico (fonte: megaten-fusion-tool). O usuario deve poder visualizar a lista completa de demonios disponiveis. 
- Visualizacao de Detalhes do Demonio (P1):  Pagina de detalhes que exibe estatisticas, resistencias e as combinacoes de fusao (read-only) em que aquele demonio participa como ingrediente ou resultado.
- Validacao de Time (Regras de Negocio) (P1): Garantir que um time nao exceda 5 demonios e que um mesmo demonio nao seja duplicado dentro do mesmo time.
- API REST Basica (P2): Exposicao de endpoints para listagem de demonios e times via JSON, permitindo futura integracao com outros frontends.

---

### 2.2. Entidades do Banco de Dados (Modelagem Conceitual)

A estrutura do banco de dados sera composta pelas seguintes entidades e seus respectivos atributos.

**Tabela: users (padrao Laravel)**
- Atributos: `id`, `name`, `email`, `password`, `remember_token`, `timestamps`.
- Relacionamento: Um usuario pode ter muitos times.

**Tabela: demons (demonios)**
- Atributos: `id`, `name` (string), `race` (string, ex: Beast, Fiend), `level` (integer), `strength` (integer), `magic` (integer), `vitality` (integer), `agility` (integer), `luck` (integer), `res_fire` (string, ex: "wk", "nu"), `res_ice`, `res_elec`, `res_force`, `res_light`, `res_dark`, `image_url` (string, opcional), `timestamps`.
- Relacionamentos: 
  - N:N com Teams via tabela pivô.
  - 1:N com Fusions (como ingrediente ou resultado).

**Tabela: teams (times do usuario)**
- Atributos: `id`, `user_id` (foreign key), `name` (string, obrigatorio), `description` (text, opcional), `timestamps`.
- Relacionamentos: Pertence a um usuario. Relaciona-se com Demonios via pivô.

**Tabela: demon_team (pivô)**
- Atributos: `id`, `team_id`, `demon_id`, `position` (integer, entre 1 e 5, unico por time).
- Finalidade: Controla a ordem e a quantidade de demonios dentro de um time especifico.

**Tabela: fusions (fusoes)**
- Atributos: `id`, `demon_a_id` (FK), `demon_b_id` (FK), `demon_result_id` (FK).
- Finalidade: Armazenar as receitas de fusao extraidas do JSON. Permite consulta rapida (read-only) para exibicao nas telas de detalhes.

---

### 2.3. Telas Previstas (UX)

| Rota / Modulo | Tela | Descricao da Interface |
| :--- | :--- | :--- |
| guest | Login / Registro | Tela limpa com formularios centralizados. Utilizara a identidade visual do SMT (tons escuros, letras brancas/amarelas, detalhes em vermelho). |
| authenticated | Dashboard | Pagina inicial apos o login. Exibira um resumo dos times do usuario e um atalho para a lista de demonios. |
| authenticated | Lista de Demonios | Tabela paginada com todos os demonios disponiveis, contendo campo de busca por nome ou raca. |
| authenticated | Detalhes do Demonio | Pagina com informacoes completas do demonio (estatisticas e resistencias). Na parte inferior, listagem das fusoes (ingredientes -> resultado). |
| authenticated | Lista de Times (Index) | Exibicao de todos os times do usuario logado em cards ou lista, com botoes para editar, visualizar e deletar. |
| authenticated | Criar Time (Create) | Formulario com campos `nome` e `descricao`. Um seletor para adicionar ate 5 demonios (com busca ou autocomplete). |
| authenticated | Editar Time (Edit) | Mesmo layout do Create, porem com os dados atuais preenchidos. |
| authenticated | Visualizar Time (Show) | Exibe o time em detalhe, com os 5 demonios ordenados por `position`, mostrando nome, raca e nivel de cada um. |

---

### 2.4. Ordem de Implementacao (Etapas para Commits)

A ordem abaixo foi pensada para permitir commits incrementais, conforme a exigencia do PDF de minimo 10 commits distribuidos ao longo do desenvolvimento.

| Etapa | Descricao | Entregaveis / Mensagens de Commit |
| :--- | :--- | :--- |
| 1 | Configuracao Inicial do Projeto | Criacao do projeto Laravel via Composer. Configuracao do `.env` para MySQL. Criacao do repositorio Git e primeiro commit: `feat: initial laravel setup`. |
| 2 | Instalacao do Laravel Boost | Executar `composer require laravel/boost --dev` e `php artisan boost:install`. Commit da estrutura gerada: `feat: install laravel boost`. |
| 3 | Autenticacao (Auth) | Instalar o Laravel Breeze (ou similar). Configurar as views de login e registro. Commit: `feat: add authentication scaffold`. |
| 4 | Modelagem e Migrations | Criar Migrations para as 4 tabelas: `demons`, `teams`, `demon_team`, `fusions`. Definir as chaves estrangeiras e indices. Commit: `feat: create database schema with migrations`. |
| 5 | Seeder de Demonios (Base de Dados) | Baixar o JSON do site (via MCP HTTP ou manualmente). Criar um Seeder (`DemonSeeder`) que leia o JSON e insira os dados na tabela `demons`. Criar Seeder para `Users` (admin e test). Commit: `feat: seed demons and test users`. |
| 6 | Configuracao e Uso do MCP | Documentar e implementar a logica de consumo do JSON usando o MCP de Filesystem ou HTTP. Commit: `feat: use MCP for data import`. |
| 7 | Criacao das Skills (Parte 4) | Criar os 4 arquivos de Skills na pasta do Boost: `SkillCRUD.md`, `SkillVisual.md`, `SkillDatabase.md`, `SkillAPI.md`. Commit: `feat: add boost skills`. |
| 8 | Backend - CRUD de Times | Criar Model `Team` e Model `Demon` (com relacionamentos). Criar Controller `TeamController`, Form Requests (Store/Update), aplicar as validacoes de limite de 5 demonios e posicao. Registrar rotas em `web.php`. Commit: `feat: implement team CRUD backend logic`. |
| 9 | Views - CRUD de Times | Criar as views Blade para `index`, `create`, `edit` e `show` de times, aplicando a Skill de Identidade Visual (Layout, cores, responsividade). Commit: `feat: implement team CRUD views`. |
| 10 | Listagem e Detalhes de Demonios + Fusoes | Criar `DemonController` com metodos `index` e `show`. Na `show`, listar as fusoes importadas (relacionamento via Model `Fusion`). Criar as respectivas views. Commit: `feat: add demon listing and fusion details`. |
| 11 | API REST (Skill Adicional) | Criar rotas em `api.php` para `/demons` e `/teams`. Criar Resources (API Resources) para padronizar as saidas JSON. Commit: `feat: add REST API endpoints`. |
| 12 | Revisao, Testes e Documentacao | Validar todos os CRUDs e corrigir bugs. Escrever `README.md` (com credenciais) e `RELATORIO.md`. Commit final: `docs: add readme and final report`. |

---

## 3. TECNICO

### 3.1. Tecnologias Utilizadas

| Camada | Tecnologia / Ferramenta | Versao / Especificacao |
| :--- | :--- | :--- |
| Backend | PHP com Framework Laravel | 11.x |
| Assistente de Desenvolvimento | Laravel Boost | Pacote dev para gerenciamento de Skills e padronizacao com IA. |
| Banco de Dados | MySQL | 8.0+ (ou compativel). |
| Frontend (Views) | Blade (Laravel) + Bootstrap 5 | Layout customizado com identidade visual SMT. |
| Cache e Assets | Vite (Padrao Laravel) | Para compilar CSS e JS. |
| Versionamento | Git + GitHub | Minimo de 10 commits distribuidos. |
| IA e Assistencia | MCP (HTTP e Filesystem) | Para consumo e leitura do JSON externo. |

---

### 3.2. Riscos Mapeados e Estrategias de Mitigacao

**Risco 1: Formato do JSON externo mudar ou ficar indisponivel**

O site de origem pode alterar a estrutura dos dados ou ficar temporariamente fora do ar.

Impacto: Impossibilidade de popular a tabela `demons` e `fusions`.

Mitigacao: Salvar uma copia estatica do JSON na pasta `storage/app/demons.json` e utiliza-la como fallback. Utilizar o MCP Filesystem para leitura local, garantindo que os seeders sempre funcionem independentemente da disponibilidade da rede.

---

**Risco 2: Dados do JSON nao mapearem 1:1 com as colunas do MySQL**

O JSON pode conter campos aninhados ou nomenclaturas diferentes das definidas na migration.

Impacto: Quebra na execucao do Seeder.

Mitigacao: Criar uma funcao de "parse" no Seeder que mapeie os campos (ex: `str` para `strength`). Usar valores padrao (ex: `null` ou `0`) para campos nao encontrados, garantindo que a insercao sempre ocorra.

---

**Risco 3: Validacao de limite de 5 demonios falhar em edicoes**

Durante a edicao de um time, a logica de adicao/remocao pode permitir que mais de 5 demonios sejam salvos.

Impacto: Time com mais de 5 demonios corrompe a regra de negocio.

Mitigacao: Implementar validacao customizada no `FormRequest` que conte os demonios no request e impeca a submissao se exceder 5. Adicionar regra no banco (indice unico composto por `team_id + position` com constraint `position BETWEEN 1 AND 5`).

---

**Risco 4: Cronograma apertado**

O prazo de entrega e 01/07 e ha muitas etapas a serem cumpridas.

Impacto: Nao entregar as Skills opcionais a tempo.

Mitigacao: Priorizar a Skill de Database (essencial para a populacao dos Seeders) e a Skill de API (que pode ser simplificada para apenas 2 endpoints: listagem de demonios e listagem de times). As demais funcionalidades serao entregues como planejado, mas com escopo ajustado se necessario.

---

### 3.3. Criterios de Aceite (Definition of Done)

Para que a aplicacao seja considerada completa e aprovada, cada um dos itens abaixo deve estar funcionando e devidamente documentado:

1. Instalacao: O projeto roda perfeitamente com `php artisan serve` apos o clone, com todas as dependencias instaladas.

2. Banco de Dados: As migracoes criam todas as tabelas sem erros. Os seeders populam a tabela `demons`, `fusions` e criam os usuarios de teste.

3. Autenticacao: O usuario `user@smt.com` e `admin@smt.com` conseguem fazer login com a senha `password`.

4. CRUD de Times: O usuario logado consegue criar, listar, editar e deletar seus proprios times. A edicao permite adicionar e remover demonios, respeitando o limite maximo de 5.

5. Demonios e Fusoes: A listagem de demonios exibe todos os registros importados. A pagina de detalhes de um demonio exibe a lista de fusoes (read-only) corretamente, mostrando os ingredientes e o resultado.

6. MCP: O relatorio (`RELATORIO.md`) contem a documentacao do MCP utilizado, com exemplos praticos de uso durante o desenvolvimento.

7. Skills: Os 4 arquivos de Skill estao na pasta correta do Laravel Boost, com conteudo coerente e que foi efetivamente aplicado ao codigo gerado.

8. Commits: O repositorio contem no minimo 10 commits distribuidos ao longo das etapas, e nao concentrados em um unico dia ou horario.

9. Documentacao: O `README.md` contem todas as instrucoes de instalacao e as credenciais de teste. O `RELATORIO.md` contem todas as secoes exigidas pelo PDF (Contexto, Ferramentas de IA, Desenvolvimento e Conclusao).

---

## 4. RESUMO DAS DECISOES FINAIS

Para consulta rapida, consolidei abaixo todas as decisoes tecnicas e de escopo que fechamos ate o momento:

- Tema da aplicacao: Shin Megami Tensei – Montador de Times.

- CRUD principal: Times (Teams), com capacidade para armazenar ate 5 demonios por time.

- Abordagem para fusoes: Read-Only (visualizacao estatica dos dados importados do JSON, sem implementacao de um motor de calculo dinamico).

- Banco de dados: MySQL.

- MCPs selecionados: HTTP/API e Filesystem (focados na importacao e leitura dos dados externos).

- Skills obrigatorias: CRUD e Identidade Visual.

- Skills adicionais (minimo 2): Banco de Dados / Seeders e API REST.

- Usuarios de teste:
  - Administrador: `admin@smt.com` com senha `password`.
  - Usuario comum: `user@smt.com` com senha `password`.

---

**Este plano esta formalmente estabelecido e servira como guia unico para todas as etapas de desenvolvimento ate a entrega final.**