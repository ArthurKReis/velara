@extends('layouts.app')

@section('title', 'Meus Times')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title">Meus Times</h1>
    <a href="{{ route('teams.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo Time
    </a>
</div>

@if($teams->isEmpty())
    <div class="card text-center py-5">
        <p class="mb-3">Você ainda não criou nenhum time.</p>
        <a href="{{ route('teams.create') }}" class="btn btn-primary">Criar Meu Primeiro Time</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Demônios</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $team)
                <tr>
                    <td><strong>{{ $team->name }}</strong></td>
                    <td>{{ Str::limit($team->description, 50) ?: '-' }}</td>
                    <td>
                        <span class="badge">{{ $team->demons->count() }}/5</span>
                    </td>
                    <td>{{ $team->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('teams.show', $team) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                            <a href="{{ route('teams.edit', $team) }}" class="btn btn-sm btn-secondary">Editar</a>
                            <form action="{{ route('teams.destroy', $team) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este time?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $teams->links() }}
    </div>
@endif
@endsection