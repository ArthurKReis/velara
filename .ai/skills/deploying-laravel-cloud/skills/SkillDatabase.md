# Skill de Banco de Dados / Seeders

## Objetivo

Padronizar a criação de estruturas de banco de dados (migrations, relacionamentos, índices) e a população de dados via seeders, garantindo integridade, performance e consistência.

## Migrations

### Nomenclatura

- Nome da tabela no plural, snake_case: `create_demons_table`, `create_teams_table`.
- Colunas: snake_case, ex: `user_id`, `created_at`.

### Estrutura Básica

```php
public function up(): void
{
    Schema::create('teams', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamps();
    });
}
```
Chaves Estrangeiras

    Sempre usar foreignId()->constrained() com onDelete('cascade') ou restrict conforme a regra de negócio.

    Para relacionamentos N:N, criar tabela pivô com dois foreignId e índices únicos compostos.

Índices

    Adicionar índices para colunas frequentemente usadas em where, orderBy ou join.

    Exemplo: $table->index('user_id');.

Constraints

    Utilizar unique() para garantir unicidade (ex: $table->unique(['team_id', 'demon_id'])).

    Utilizar unsigned para colunas de chave estrangeira (já feito pelo foreignId).

Seeders
Organização

    Criar seeders separados por entidade: UserSeeder, DemonSeeder, TeamSeeder, FusionSeeder.

    Chamar todos no DatabaseSeeder.

População de Dados

    Utilizar firstOrCreate ou updateOrCreate para evitar duplicatas ao rodar múltiplas vezes.

    Para grandes conjuntos de dados, usar insert em lotes para performance.

Exemplo de Seeder
```php
class DemonSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('seeders/data/demons.php');

        foreach ($data as $demonData) {
            Demon::firstOrCreate(
                ['name' => $demonData['name'], 'race' => $demonData['race']],
                $demonData
            );
        }
    }
}
```
Dados de Teste

    Criar usuários de teste com credenciais fixas.

    Criar dados relacionais (ex: times para cada usuário).

Relacionamentos
1:N

    Model pai: hasMany, Model filho: belongsTo.

N:N

    Utilizar belongsToMany com nome da tabela pivô.

    Definir withPivot para colunas extras.

    Em consultas, usar with() para eager loading.

Exemplo de Relacionamento N:N
```php
// Team model
public function demons()
{
    return $this->belongsToMany(Demon::class, 'demon_team')
                ->withPivot('position')
                ->orderBy('pivot_position');
}
```

Boas Práticas

    Evitar N+1: usar with() em consultas.

    Soft Deletes: se necessário, adicionar $table->softDeletes().

    Timestamps: manter timestamps() para created_at e updated_at.

    Charset: usar utf8mb4 para suporte a emojis e caracteres especiais.

Conclusão

Seguir esta Skill garante que a estrutura do banco de dados seja robusta, eficiente e alinhada com as necessidades da aplicação, facilitando manutenção e expansão futura.