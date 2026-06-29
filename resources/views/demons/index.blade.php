@extends('layouts.app')

@section('title', 'Lista de Demônios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title">Todos os Demônios</h1>
    <span class="badge bg-secondary">{{ $demons->total() }} demônios</span>
</div>

<!-- Busca -->
<form method="GET" action="{{ route('demons.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="Buscar por nome ou raça..." value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">Buscar</button>
        @if(request('search'))
            <a href="{{ route('demons.index') }}" class="btn btn-secondary">Limpar</a>
        @endif
    </div>
</form>

<!-- Listagem -->
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Raça</th>
                <th>Nível</th>
                <th>Força</th>
                <th>Magia</th>
                <th>Vitalidade</th>
                <th>Agilidade</th>
                <th>Sorte</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demons as $demon)
            <tr>
                <td><strong>{{ $demon->name }}</strong></td>
                <td>{{ $demon->race }}</td>
                <td>{{ $demon->level }}</td>
                <td>{{ $demon->strength }}</td>
                <td>{{ $demon->magic }}</td>
                <td>{{ $demon->vitality }}</td>
                <td>{{ $demon->agility }}</td>
                <td>{{ $demon->luck }}</td>
                <td>
                    <a href="{{ route('demons.show', $demon) }}" class="btn btn-sm btn-outline-primary">Detalhes</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted">Nenhum demônio encontrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center">
    {{ $demons->appends(request()->query())->links() }}
</div>
@endsection