@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detalhes da Aposta</h2>
        <a href="{{ route('groups.show', $bettingSlip->group) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao Grupo
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informações da Aposta</h5>
                        <span class="badge bg-{{ $bettingSlip->status === 'won' ? 'success' : ($bettingSlip->status === 'lost' ? 'danger' : 'primary') }}">
                            {{ ucfirst($bettingSlip->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Grupo</h6>
                            <p class="mb-0">{{ $bettingSlip->group->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Jogo</h6>
                            <p class="mb-0">{{ $bettingSlip->group->game->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Data da Aposta</h6>
                            <p class="mb-0">{{ $bettingSlip->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Sorteio</h6>
                            <p class="mb-0">{{ $bettingSlip->draw->draw_date->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Valor Apostado</h6>
                            <p class="mb-0 text-primary fw-bold">€{{ number_format($bettingSlip->total_cost, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Tipo de Aposta</h6>
                            <p class="mb-0">{{ ucfirst($bettingSlip->bet_type) }}</p>
                        </div>
                    </div>

                    @if($bettingSlip->status === 'won')
                    <div class="alert alert-success mb-4">
                        <h5 class="alert-heading mb-2">Parabéns! Você ganhou!</h5>
                        <p class="mb-0">Valor ganho: <strong>€{{ number_format($bettingSlip->prize_amount, 2) }}</strong></p>
                    </div>
                    @endif

                    @if($bettingSlip->validation_errors)
                    <div class="alert alert-warning mb-4">
                        <h5 class="alert-heading mb-2">Avisos</h5>
                        <ul class="mb-0">
                            @foreach(json_decode($bettingSlip->validation_errors) as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($bettingSlip->group->game->name === 'Totobola')
                    <div class="mb-4">
                        <h5>Previsões</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jogo</th>
                                        <th>Previsão</th>
                                        <th>Resultado</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(json_decode($bettingSlip->predictions) as $index => $prediction)
                                    <tr>
                                        <td>Jogo {{ $index + 1 }}</td>
                                        <td>{{ $prediction }}</td>
                                        <td>{{ $bettingSlip->draw->results[$index] ?? '-' }}</td>
                                        <td>
                                            @if(isset($bettingSlip->draw->results[$index]))
                                                @if($prediction === $bettingSlip->draw->results[$index])
                                                <span class="badge bg-success">Acertou</span>
                                                @else
                                                <span class="badge bg-danger">Errou</span>
                                                @endif
                                            @else
                                            <span class="badge bg-secondary">Pendente</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="mb-4">
                        <h5>Números Apostados</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(json_decode($bettingSlip->numbers) as $number)
                            <div class="number-badge {{ in_array($number, $bettingSlip->draw->winning_numbers ?? []) ? 'winning' : '' }}">
                                {{ $number }}
                            </div>
                            @endforeach
                        </div>
                    </div>

                    @if($bettingSlip->group->game->name === 'Euromilhões')
                    <div class="mb-4">
                        <h5>Estrelas</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(json_decode($bettingSlip->stars) as $star)
                            <div class="star-badge {{ in_array($star, $bettingSlip->draw->winning_stars ?? []) ? 'winning' : '' }}">
                                {{ $star }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($bettingSlip->draw->is_completed)
                    <div class="mb-4">
                        <h5>Números Sorteados</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($bettingSlip->draw->winning_numbers as $number)
                            <div class="number-badge winning">
                                {{ $number }}
                            </div>
                            @endforeach
                        </div>

                        @if($bettingSlip->group->game->name === 'Euromilhões')
                        <h5 class="mt-3">Estrelas Sorteadas</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($bettingSlip->draw->winning_stars as $star)
                            <div class="star-badge winning">
                                {{ $star }}
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informações do Sorteio</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Status do Sorteio</h6>
                        <p class="mb-0">
                            @if($bettingSlip->draw->is_completed)
                            <span class="badge bg-success">Concluído</span>
                            @else
                            <span class="badge bg-warning">Pendente</span>
                            @endif
                        </p>
                    </div>

                    @if($bettingSlip->draw->is_completed)
                    <div class="mb-3">
                        <h6>Data do Sorteio</h6>
                        <p class="mb-0">{{ $bettingSlip->draw->completed_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Jackpot</h6>
                        <p class="text-primary fw-bold mb-0">€{{ number_format($bettingSlip->draw->jackpot_amount, 2) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($bettingSlip->group->admin_id === auth()->id() && !$bettingSlip->draw->is_completed)
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Verificar Resultados</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('betting-slips.check-results', $bettingSlip) }}" method="POST">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i> Verificar Resultados
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.number-badge, .star-badge {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
    background-color: #e9ecef;
    color: #495057;
}

.number-badge.winning {
    background-color: #198754;
    color: white;
}

.star-badge {
    background-color: #ffc107;
    color: #000;
}

.star-badge.winning {
    background-color: #fd7e14;
    color: white;
}
</style>
@endpush
@endsection
