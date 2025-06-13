@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gerir Jogos de Futebol</h2>
        <form action="{{ route('admin.football-matches.import') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-download me-1"></i> Importar Jogos da Época (API)
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Liga</th>
                            <th>Equipa da Casa</th>
                            <th>Equipa Visitante</th>
                            <th>Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matches as $match)
                        <tr>
                            <td>{{ $match->match_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $match->league }}</td>
                            <td>{{ $match->home_team }}</td>
                            <td>{{ $match->away_team }}</td>
                            <td>
                                @if($match->result)
                                    <span class="badge bg-success">{{ $match->result }}</span>
                                @else
                                    <span class="badge bg-warning">Pendente</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum jogo de futebol encontrado. Tente importar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $matches->links('groups.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection 