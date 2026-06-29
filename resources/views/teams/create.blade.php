@extends('layouts.app')

@section('title', 'Criar Time')

@section('content')
<div class="card">
    <div class="card-header">Criar Novo Time</div>
    <div class="card-body">
        <form action="{{ route('teams.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nome do Time <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="demons" class="form-label">Selecione até 5 demônios <span class="text-danger">*</span></label>
                <select multiple class="form-control @error('demons') is-invalid @enderror" id="demons" name="demons[]" size="10" style="height: auto;">
                    @foreach($demons as $demon)
                        <option value="{{ $demon->id }}" {{ in_array($demon->id, old('demons', [])) ? 'selected' : '' }}>
                            {{ $demon->name }} ({{ $demon->race }}) - Nv. {{ $demon->level }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Segure Ctrl (ou Cmd) para selecionar múltiplos demônios.</small>
                @error('demons')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('teams.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Criar Time</button>
            </div>
        </form>
    </div>
</div>
@endsection