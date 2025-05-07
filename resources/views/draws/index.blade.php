@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gerenciar Sorteios</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('draws.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Novo Sorteio
            </a>
            <form action="{{ route('draws.destroyCompleted') }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir todos os sorteios realizados?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Excluir Concluídos
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-header bg-white">
            <form action="{{ route('draws.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="game_id" class="form-label">Jogo</label>
                    <select class="form-select" id="game_id" name="game_id">
                        <option value="">Todos os jogos</option>
                        @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Próximos</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Realizados</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('draws.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jogo</th>
                            <th>Número</th>
                            <th>Data</th>
                            <th>Jackpot</th>
                            <th>Status</th>
                            <th>Apostas</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($draws as $draw)
                        <tr>
                            <td>{{ $draw->game->name }}</td>
                            <td>{{ $draw->draw_number ?: '-' }}</td>
                            <td>
                                @if($draw->game->name === 'Totobola')
                                    <span class="text-muted">-</span>
                                @elseif($draw->end_date)
                                    {{ $draw->end_date->format('d/m/Y H:i') }}<br>
                                    <span class="text-muted small countdown" data-end="{{ $draw->end_date->format('Y-m-d H:i:s') }}" id="countdown-{{ $draw->id }}"></span>
                                @else
                                    {{ $draw->draw_date->format('d/m/Y H:i') }}<br>
                                    <span class="text-muted small">Data de fim não definida</span>
                                @endif
                            </td>
                            <td>
                                @if($draw->game->name === 'Totobola')
                                    <span class="text-muted">-</span>
                                @elseif(($draw->jackpot_amount ?? 0) > 0)
                                    €{{ number_format($draw->jackpot_amount, 2) }}
                                @else
                                    <span class="text-muted">Sem jackpot</span>
                                @endif
                            </td>
                            <td>
                                @if($draw->game->name === 'Totobola')
                                    <span class="text-muted">-</span>
                                @elseif($draw->is_completed)
                                <span class="badge bg-success">Realizado</span>
                                @elseif($draw->draw_date < now())
                                <span class="badge bg-warning">Pendente</span>
                                @else
                                <span class="badge bg-primary">Próximo</span>
                                @endif
                            </td>
                            <td>{{ $draw->bettingSlips->count() }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('draws.show', $draw) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('draws.edit', $draw) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDrawModal{{ $draw->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- Modal de confirmação de exclusão -->
                                <div class="modal fade" id="deleteDrawModal{{ $draw->id }}" tabindex="-1" aria-labelledby="deleteDrawModalLabel{{ $draw->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteDrawModalLabel{{ $draw->id }}">Confirmar Exclusão</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Tem certeza que deseja excluir o sorteio de {{ $draw->game->name }} do dia {{ $draw->draw_date->format('d/m/Y') }}?</p>
                                                <p class="text-danger"><strong>Atenção:</strong> Esta ação não pode ser desfeita e todas as apostas associadas a este sorteio serão excluídas.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('draws.destroy', $draw) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">Nenhum sorteio encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $draws->withQueryString()->links('groups.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.countdown').forEach(function(elem) {
        var end = new Date(elem.getAttribute('data-end').replace(' ', 'T'));
        function updateCountdown() {
            var now = new Date();
            var diff = end - now;
            if (diff > 0) {
                var hours = Math.floor(diff / 1000 / 60 / 60);
                var minutes = Math.floor((diff / 1000 / 60) % 60);
                var seconds = Math.floor((diff / 1000) % 60);
                elem.textContent = `Termina em ${hours}h ${minutes}m ${seconds}s`;
            } else {
                elem.textContent = 'Sorteio encerrado';
            }
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});
</script>
@endsection
