@extends('layouts.app')

@section('title', 'Detalhes do Time')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ $team->name }}</span>
        <div>
            <a href="{{ route('teams.edit', $team) }}" class="btn btn-sm btn-secondary">Editar</a>
            <a href="{{ route('teams.index') }}" class="btn btn-sm btn-outline-primary">Voltar</a>
        </div>
    </div>
    <div class="card-body">
        <p><strong>Descrição:</strong> {{ $team->description ?: 'Nenhuma descrição fornecida.' }}</p>
        <p><strong>Demônios ({{ $team->demons->count() }}/5):</strong></p>

        @if($team->demons->isEmpty())
            <p class="text-muted">Nenhum demônio adicionado a este time.</p>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Posição</th>
                            <th>Nome</th>
                            <th>Raça</th>
                            <th>Nível</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($team->demons as $demon)
                        <tr>
                            <td><span class="position-badge">{{ $demon->pivot->position }}</span></td>
                            <td>{{ $demon->name }}</td>
                            <td>{{ $demon->race }}</td>
                            <td>{{ $demon->level }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <p class="text-muted mt-3">
            <small>Criado em: {{ $team->created_at->format('d/m/Y H:i') }}</small><br>
            <small>Última atualização: {{ $team->updated_at->format('d/m/Y H:i') }}</small>
        </p>
    </div>
</div>
@endsection