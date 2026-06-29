@extends('layouts.app')

@section('title', 'Editar Time')

@section('content')
<div class="card">
    <div class="card-header">Editar Time: {{ $team->name }}</div>
    <div class="card-body">
        <form action="{{ route('teams.update', $team) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nome do Time <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $team->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $team->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="demons" class="form-label">Selecione até 5 demônios</label>
                <select multiple class="form-control @error('demons') is-invalid @enderror" id="demons" name="demons[]" size="10" style="height: auto;">
                    @foreach($demons as $demon)
                        <option value="{{ $demon->id }}" {{ in_array($demon->id, old('demons', $selectedDemons)) ? 'selected' : '' }}>
                            {{ $demon->name }} ({{ $demon->race }}) - Nv. {{ $demon->level }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Segure Ctrl (ou Cmd) para selecionar múltiplos demônios. Deixe em branco para remover todos.</small>
                @error('demons')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('teams.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar Time</button>
            </div>
        </form>
    </div>
</div>
@endsection