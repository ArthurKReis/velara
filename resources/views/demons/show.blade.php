@extends('layouts.app')

@section('title', $demon->name)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ $demon->name }} <small class="text-muted">({{ $demon->race }})</small></span>
        <a href="{{ route('demons.index') }}" class="btn btn-sm btn-outline-primary">Voltar</a>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Informações Gerais -->
            <div class="col-md-6">
                <h5>Informações Gerais</h5>
                <ul class="list-unstyled">
                    <li><strong>Nível:</strong> {{ $demon->level }}</li>
                    <li><strong>Raça:</strong> {{ $demon->race }}</li>
                </ul>

                <h5 class="mt-3">Estatísticas</h5>
                <ul class="list-unstyled">
                    <li><strong>Força:</strong> {{ $demon->strength }}</li>
                    <li><strong>Magia:</strong> {{ $demon->magic }}</li>
                    <li><strong>Vitalidade:</strong> {{ $demon->vitality }}</li>
                    <li><strong>Agilidade:</strong> {{ $demon->agility }}</li>
                    <li><strong>Sorte:</strong> {{ $demon->luck }}</li>
                </ul>
            </div>

            <!-- Resistências -->
            <div class="col-md-6">
                <h5>Resistências</h5>
                <ul class="list-unstyled">
                    <li><strong>Fogo:</strong> {{ $demon->res_fire ?: 'Normal' }}</li>
                    <li><strong>Gelo:</strong> {{ $demon->res_ice ?: 'Normal' }}</li>
                    <li><strong>Eletricidade:</strong> {{ $demon->res_elec ?: 'Normal' }}</li>
                    <li><strong>Força:</strong> {{ $demon->res_force ?: 'Normal' }}</li>
                    <li><strong>Luz:</strong> {{ $demon->res_light ?: 'Normal' }}</li>
                    <li><strong>Escuridão:</strong> {{ $demon->res_dark ?: 'Normal' }}</li>
                </ul>
                <small class="text-muted">Legenda: wk = Fraco, rs = Resistente, nu = Nulo, dr = Absorve, rp = Repete</small>
            </div>
        </div>

        <!-- Fusões (read-only) -->
        <hr>
        <h5 class="mt-3">Fusões</h5>

        @php
            $fusions = collect()
                ->merge($demon->fusionsAsA->map(fn($f) => ['ingredientes' => [$f->demonB->name ?? '?'], 'resultado' => $f->demonResult->name ?? '?']))
                ->merge($demon->fusionsAsB->map(fn($f) => ['ingredientes' => [$f->demonA->name ?? '?'], 'resultado' => $f->demonResult->name ?? '?']))
                ->merge($demon->fusionsAsResult->map(fn($f) => ['ingredientes' => [$f->demonA->name ?? '?', $f->demonB->name ?? '?'], 'resultado' => $demon->name]));
        @endphp

        @if($fusions->isNotEmpty())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ingredientes</th>
                            <th>Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fusions as $fusion)
                        <tr>
                            <td>{{ implode(' + ', $fusion['ingredientes']) }}</td>
                            <td>{{ $fusion['resultado'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Nenhuma informação de fusão disponível para este demônio no momento.</p>
        @endif
    </div>
</div>
@endsection