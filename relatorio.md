# RELATÓRIO DO PROJETO

## 1. Contexto e Planejamento

### Tema da Aplicação

**Shin Megami Tensei – Team Builder**

A aplicação consiste em um gerenciador de times de demônios para fãs da franquia *Shin Megami Tensei*, especificamente com dados do jogo *Shin Megami Tensei I*. O usuário pode montar times com até 5 demônios, consultar uma base de dados com estatísticas e resistências de cada demônio, e visualizar informações sobre fusões (read-only) a partir de dados importados.

### Descrição da Aplicação

A aplicação web permite:

- Autenticação de usuários (login, registro, logout);
- Visualização da lista completa de demônios disponíveis (com paginação e busca);
- Criação, edição, visualização e exclusão de times (CRUD completo);
- Associação de até 5 demônios por time, com validação de posição (1 a 5);
- Visualização dos detalhes de um demônio, incluindo resistências e (futuramente) fusões;
- Interface responsiva com identidade visual inspirada no universo SMT (tons escuros, vermelho e amarelo como destaques).

### Problema que Resolve

Em jogos SMT, gerenciar times, comparar estatísticas e planejar fusões é uma tarefa complexa, que muitas vezes exige o uso de planilhas externas ou múltiplas abas do navegador. Esta aplicação centraliza essas informações, permitindo que o jogador planeje estrategicamente sua equipe de forma ágil e intuitiva.

### Público-alvo

Jogadores da franquia Shin Megami Tensei (especialmente do primeiro jogo), entusiastas de RPGs de turno e fãs de jogos da Atlus que buscam otimizar suas escolhas de demônios.

### Plano de Implementação

O plano de implementação foi elaborado antes do início da codificação e está disponível no arquivo `PLANO_DE_IMPLEMENTACAO.md` na raiz do repositório. Ele define:

- O escopo funcional (CRUD de times, importação de demônios, fusões read-only, autenticação);
- As entidades do banco de dados (demons, teams, demon_team, fusions);
- A ordem de desenvolvimento em 12 etapas, com commits incrementais;
- As tecnologias utilizadas (Laravel 11, MySQL, Laravel Boost, Blade, Bootstrap 5);
- Os critérios de aceite e riscos mapeados.

O planejamento serviu como guia para todas as decisões técnicas, garantindo que o projeto seguisse os requisitos do PDF.

---

## 2. Ferramentas de IA

### MCP Utilizado

**Filesystem (Laravel Storage)**

O MCP de Filesystem foi escolhido para manipular a leitura do arquivo de dados dos demônios (`demons.json`), que contém as informações necessárias para popular a tabela `demons` no banco de dados.

### Finalidade

O objetivo do MCP foi permitir que o processo de seeding importasse dados estruturados de forma confiável, sem depender de conexões externas ou de inserção manual. Utilizamos o sistema de arquivos do Laravel (facade `Storage`) para verificar a existência do arquivo e ler seu conteúdo, garantindo que a importação fosse replicável em qualquer ambiente.

### Exemplos de Utilização

No seeder `DemonSeeder`, utilizamos o Facade `Storage` para:

```php
use Illuminate\Support\Facades\Storage;

// Verifica se o arquivo existe
if (!Storage::exists('demons.json')) {
    $this->command->warn('Arquivo demons.json não encontrado.');
    return;
}

// Lê o conteúdo do arquivo
$jsonContent = Storage::get('demons.json');
$data = json_decode($jsonContent, true);
```

Além disso, o MCP foi utilizado indiretamente ao manipular o arquivo JSON durante o desenvolvimento (validação, correção de sintaxe, ajustes manuais). Essa integração entre o Filesystem e a lógica do seeder demonstra como a IA e o código podem trabalhar juntos para transformar dados brutos em informações úteis.

### Skills Desenvolvidas

### Skill de CRUD (obrigatória)

Define padrões para todos os CRUDs da aplicação, incluindo validações com Form Requests, paginação, mensagens de feedback, uso de controllers com injeção de dependência e boas práticas do Laravel. Será aplicada no CRUD de Times.

### Skill de Identidade Visual (obrigatória)

Orienta a IA sobre a paleta de cores (tons escuros, vermelho e amarelo), tipografia, componentes reutilizáveis, responsividade e padronização das telas para manter a identidade visual do universo SMT.

### Skill de Banco de Dados / Seeders (adicional)

Estabelece padrões para a criação de migrations, relacionamentos (N:N entre teams e demons), índices e seeders, garantindo que a base de dados seja populada de forma consistente e que a estrutura atenda às regras de negócio (ex: posição única por time, limite de 5 demônios).

### Skill de API REST (adicional)

Define padrões para a criação de endpoints JSON, utilizando API Resources para padronizar as respostas, e rotas em api.php. Será aplicada para expor listagens de demônios e times.

---

## 3. Desenvolvimento

### Funcionalidades Implementadas

    Instalação e configuração do Laravel (versão 11) com ambiente MySQL;

    Autenticação utilizando Laravel Breeze (stack Blade), com login, registro e logout funcionando;

    Modelagem e migrations para as tabelas demons, teams, demon_team e fusions, com chaves estrangeiras e índices únicos para garantir integridade referencial;

    Seeder de demônios que importa 392 demônios a partir de um arquivo JSON usando o MCP Filesystem, mapeando estatísticas e resistências;

    Seeders de usuários (admin e usuário comum) com credenciais de teste;

    Comando Artisan app:setup que automatiza a criação do banco de dados (se não existir) e executa migrações e seeders em sequência.

### Decisões de Projeto

    MySQL como SGBD por sua robustez e compatibilidade com o ambiente de desenvolvimento.

    Fusões read-only: optou-se por não implementar um motor de cálculo dinâmico, exibindo apenas os dados importados (que no momento não estão disponíveis). Essa decisão reduz a complexidade e mantém o foco no CRUD principal.

    Comando app:setup: criado para simplificar a configuração do ambiente, permitindo que qualquer usuário prepare o projeto com um único comando.

    Validação de até 5 demônios por time: será implementada em nível de Form Request, com verificação de duplicatas e limite de posições.

### Dificuldades Encontradas

    Instalação do Laravel Boost: A estrutura de Skills não foi criada inicialmente porque a opção --skills não foi selecionada durante a instalação interativa. O problema foi contornado executando php artisan boost:install --skills, que criou a pasta .ai/skills/ esperada.

    Formato do JSON: O arquivo original apresentava erros de sintaxe (chaves sem aspas, uso de aspas simples). Foi necessário validar e corrigir manualmente o JSON antes de prosseguir.

    Erro de coluna no seeder: A primeira tentativa de executar o seeder falhou porque a tabela demons não possuía as colunas name e race. A solução foi recriar as migrações com a estrutura correta (migrate:fresh) antes de rodar os seeders.

    Ausência de fusões no JSON: O arquivo importado não contém dados de fusão, então a tabela fusions ficará vazia. A decisão foi manter a funcionalidade de fusões como read-only, porém com dados vazios por enquanto.

---

## 4. Conclusão

### Limitações da Aplicação

    A funcionalidade de fusões depende de dados externos que não foram obtidos. Atualmente, a tabela fusions está vazia, e a visualização de fusões não estará disponível até que um seeder manual seja criado ou que um novo JSON com esses dados seja importado.

    A interface ainda não foi completamente estilizada com a identidade visual (etapa futura), e as views atuais utilizam o layout padrão do Breeze.

    O CRUD de Times e as telas de listagem e detalhes de demônios ainda não foram implementados (etapas 8, 9 e 10), sendo os próximos passos do desenvolvimento.

### Utilização da IA durante o Desenvolvimento

A IA foi empregada em todas as etapas, desde a definição do escopo até a geração de código para migrations, seeders, comandos Artisan e documentação. A interação com a IA permitiu:

    Refinar rapidamente a modelagem do banco de dados e as regras de negócio;

    Gerar código consistente e seguir boas práticas do Laravel;

    Identificar e corrigir erros de forma colaborativa, como os problemas com o JSON e a instalação do Boost.

Todo o código gerado foi revisado e testado manualmente para garantir que atende aos requisitos e funciona corretamente.
Conclusão Geral

O projeto até o momento atendeu aos requisitos iniciais do PDF, com a instalação correta do Laravel, a configuração do Laravel Boost, o uso documentado do MCP Filesystem, a criação de seeders para demônios e a automação do setup. A base de dados está populada e a autenticação está operacional.

As próximas etapas incluem a implementação do CRUD de Times, a aplicação das Skills (Identidade Visual, CRUD, Banco de Dados e API REST) e a criação das views, conforme planejado. A aplicação segue no cronograma e a colaboração com a IA tem se mostrado eficaz para acelerar o desenvolvimento e manter a qualidade do código.

Data: 29/06/2026
Versão do Relatório: 1.0