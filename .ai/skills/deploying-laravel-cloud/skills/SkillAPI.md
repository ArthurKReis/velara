# Skill de API REST

## Objetivo

Padronizar a criação de endpoints JSON para a aplicação, utilizando API Resources para transformar dados, e mantendo consistência nas respostas.

## Estrutura

### Rotas

- Definir rotas no arquivo `routes/api.php`.
- Agrupar rotas com prefixo `api/v1` para versionamento.
- Utilizar métodos HTTP apropriados: GET, POST, PUT, DELETE.

### Controllers

- Criar controllers específicos para API, ou reutilizar os mesmos controllers com verificação de formato.
- Utilizar `Resource` para retornar dados formatados.

### API Resources

- Criar Resources para cada modelo: `DemonResource`, `TeamResource`.
- Transformar os dados para o formato desejado (campos, relações).
- Utilizar `whenLoaded` para incluir relações condicionalmente.

## Exemplo de DemonResource

```php
class DemonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'race' => $this->race,
            'level' => $this->level,
            'stats' => [
                'strength' => $this->strength,
                'magic' => $this->magic,
                'vitality' => $this->vitality,
                'agility' => $this->agility,
                'luck' => $this->luck,
            ],
            'resistances' => [
                'fire' => $this->res_fire,
                'ice' => $this->res_ice,
                'elec' => $this->res_elec,
                'force' => $this->res_force,
                'light' => $this->res_light,
                'dark' => $this->res_dark,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```
Endpoints Exemplo
Listar Demônios

    Rota: GET /api/v1/demons

    Controller: DemonController@index retornando DemonResource::collection(Demon::paginate())

Listar Times do Usuário

    Rota: GET /api/v1/teams

    Controller: TeamController@index retornando TeamResource::collection(auth()->user()->teams()->with('demons')->paginate())

Criar Time (via API)

    Rota: POST /api/v1/teams

    Controller: TeamController@store recebendo JSON e retornando TeamResource.

Atualizar Time

    Rota: PUT /api/v1/teams/{team}

    Controller: TeamController@update

Deletar Time

    Rota: DELETE /api/v1/teams/{team}

    Controller: TeamController@destroy

Autenticação na API

    Utilizar tokens (Sanctum) para autenticação.

    Proteger rotas com middleware auth:sanctum.

Respostas Padrão

    Sucesso (GET): status 200 com dados.

    Sucesso (POST/PUT): status 201 ou 200 com dados.

    Erro de validação: status 422 com erros.

    Não autorizado: status 401.

    Não encontrado: status 404.

Exemplo de Resposta

    ```json
    {
    "data": [
        {
            "id": 1,
            "name": "Time dos Anjos",
            "demons": [
                {
                    "id": 10,
                    "name": "Angel",
                    "level": 10
                }
            ]
        }
    ],
    "links": {
        "first": "http://localhost/api/v1/teams?page=1",
        "last": "http://localhost/api/v1/teams?page=5",
        "prev": null,
        "next": "http://localhost/api/v1/teams?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 10,
        "to": 10,
        "total": 50
    }
}
    ```
    Boas Práticas

    Versionar a API (/api/v1/...).

    Utilizar recursos (Resource) para consistência.

    Incluir paginação com paginate().

    Usar request->user() para obter usuário autenticado.

    Não expor campos sensíveis (ex: senha).

    Utilizar Route::apiResource() para rotas RESTful.

Conclusão

Seguir esta Skill garante uma API REST consistente, bem documentada e fácil de consumir, preparada para futuras integrações com frontends separados.