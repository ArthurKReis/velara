# Skill de CRUD

## Objetivo

Padronizar a construção de operações CRUD (Create, Read, Update, Delete) em toda a aplicação, garantindo consistência, reutilização de código e aderência às boas práticas do Laravel.

## Estrutura de um CRUD

### Controller

- Utilizar **Resource Controllers** com os métodos: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.
- Injetar o Model e os Form Requests nos métodos conforme necessário.
- Utilizar **Eloquent** para operações com o banco.
- Retornar views com os dados necessários (ex: `compact('teams')`).

### Form Requests

- Criar Form Requests específicos para Store e Update, ex: `StoreTeamRequest`, `UpdateTeamRequest`.
- Definir regras de validação no método `rules()`.
- Mensagens de erro personalizadas no método `messages()` se necessário.
- Autorização (`authorize()`) retornando `true` ou verificando permissões.

### Views

- Utilizar Blade com layout principal (app.blade.php).
- Incluir mensagens de feedback (sucesso/erro) via sessão.
- Paginação com `->paginate()` no controller e `{{ $items->links() }}` na view.
- Formulários com CSRF, método correto (POST, PUT, DELETE).
- Utilizar componentes Blade reutilizáveis para formulários, tabelas, etc.

### Rotas

- Definir rotas resource no arquivo `web.php`, ex: `Route::resource('teams', TeamController::class)`.
- Rotas adicionais podem ser definidas separadamente se necessário.

### Validações Comuns

- Limite máximo de itens (ex: 5 demônios por time).
- Unicidade de campos (ex: nome do time por usuário).
- Integridade referencial (ex: não deletar demônio se estiver em uso).

### Mensagens de Feedback

- Utilizar `session()->flash('success', 'Mensagem')` após operações bem-sucedidas.
- Utilizar `session()->flash('error', 'Mensagem')` para erros.
- Exibir no layout principal com Bootstrap alerts.

### Boas Práticas

- Manter Controllers enxutos, delegando lógica complexa para Models ou Services.
- Utilizar **Route Model Binding** sempre que possível.
- Evitar consultas N+1 com `with()`.
- Utilizar **Policy** para autorização (se necessário).
- Paginar listagens com `paginate()`.
- Ordenar listagens por padrão (ex: `orderBy('created_at', 'desc')`).

## Exemplo de CRUD de Times

### Model Team

```php
class Team extends Model
{
    protected $fillable = ['user_id', 'name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function demons()
    {
        return $this->belongsToMany(Demon::class, 'demon_team')
                    ->withPivot('position')
                    ->orderBy('pivot_position');
    }
}

Controller TeamController

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::where('user_id', auth()->id())->paginate(10);
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $demons = Demon::orderBy('name')->get();
        return view('teams.create', compact('demons'));
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('demons')) {
            $this->syncDemons($team, $request->demons);
        }

        return redirect()->route('teams.index')
                         ->with('success', 'Time criado com sucesso!');
    }

    // outros métodos...
}

Form Request StoreTeamRequest

class StoreTeamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'demons' => 'required|array|max:5',
            'demons.*' => 'exists:demons,id',
        ];
    }

    public function messages()
    {
        return [
            'demons.max' => 'Um time pode ter no máximo 5 demônios.',
        ];
    }
}
```

Views (Blade)

    index.blade.php: tabela com listagem e botões de ação.

    create.blade.php: formulário com campos name, description e seleção de demônios (múltiplos).

    edit.blade.php: similar ao create, com dados preenchidos.

    show.blade.php: exibição detalhada do time e seus demônios.

Padrão de Nomes de Rotas e Views

    Rotas: teams.index, teams.create, teams.store, teams.show, teams.edit, teams.update, teams.destroy.

    Views: teams/index.blade.php, teams/create.blade.php, teams/edit.blade.php, teams/show.blade.php.

Conclusão

Seguir esta Skill garante que todos os CRUDs da aplicação sejam desenvolvidos de forma consistente, facilitando a manutenção e a compreensão do código.