@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detalhes da Aposta</h2>
        <div>
            <a href="{{ route('betting-slips.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Voltar às Apostas
            </a>
            @if($bettingSlip->group)
            <a href="{{ route('groups.show', $bettingSlip->group) }}" class="btn btn-outline-primary">
                <i class="fas fa-users me-1"></i> Ver Grupo
            </a>
            @endif
        </div>
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informações da Aposta</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Jogo</h6>
                            <p class="text-muted">{{ $bettingSlip->draw->game->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Grupo</h6>
                            <p class="text-muted">
                                @if($bettingSlip->group)
                                {{ $bettingSlip->group->name }}
                                @else
                                Aposta Individual
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Data do Sorteio</h6>
                            <p class="text-muted">{{ $bettingSlip->draw->draw_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Status do Sorteio</h6>
                            <p>
                                @if($bettingSlip->draw->is_completed)
                                <span class="badge bg-success">Realizado</span>
                                @else
                                <span class="badge bg-warning">Pendente</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Tipo de Aposta</h6>
                            <p class="text-muted">
                                @if($bettingSlip->is_system)
                                Sistema (Desdobramento)
                                @else
                                Simples
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Custo Total</h6>
                            <p class="text-muted">€{{ number_format($bettingSlip->total_cost, 2) }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Números Apostados</h6>
                        <div class="d-flex flex-wrap">
                            @if($bettingSlip->draw->game->name == 'Euromilhões')
                                @php
                                $mainNumbers = array_slice($bettingSlip->numbers, 0, 5);
                                $stars = array_slice($bettingSlip->numbers, 5);
                                @endphp
                                
                                <div class="me-4 mb-3">
                                    <p class="mb-2">Números:</p>
                                    <div class="d-flex flex-wrap">
                                        @foreach($mainNumbers as $number)
                                        <span class="number-ball main-ball">{{ $number }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="mb-2">Estrelas:</p>
                                    <div class="d-flex flex-wrap">
                                        @foreach($stars as $star)
                                        <span class="number-ball star-ball">{{ $star - 50 }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="d-flex flex-wrap">
                                    @foreach($bettingSlip->numbers as $number)
                                    <span class="number-ball main-ball">{{ $number }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($bettingSlip->draw->is_completed)
                    <div class="mb-4">
                        <h6>Números Sorteados</h6>
                        <div class="d-flex flex-wrap">
                            @if($bettingSlip->draw->game->name == 'Euromilhões')
                                @php
                                $winningMainNumbers = array_slice($bettingSlip->draw->winning_numbers, 0, 5);
                                $winningStars = array_slice($bettingSlip->draw->winning_numbers, 5);
                                @endphp
                                
                                <div class="me-4 mb-3">
                                    <p class="mb-2">Números:</p>
                                    <div class="d-flex flex-wrap">
                                        @foreach($winningMainNumbers as $number)
                                        <span class="number-ball main-ball {{ in_array($number, $mainNumbers) ? 'matched' : '' }}">{{ $number }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="mb-2">Estrelas:</p>
                                    <div class="d-flex flex-wrap">
                                        @foreach($winningStars as $star)
                                        <span class="number-ball star-ball {{ in_array($star, $stars) ? 'matched' : '' }}">{{ $star - 50 }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="d-flex flex-wrap">
                                    @foreach($bettingSlip->draw->winning_numbers as $number)
                                    <span class="number-ball main-ball {{ in_array($number, $bettingSlip->numbers) ? 'matched' : '' }}">{{ $number }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Resultado</h5>
                </div>
                <div class="card-body">
                    @php
                        $matches = is_array($bettingSlip->numbers) && is_array($bettingSlip->draw->winning_numbers)
                            ? count(array_intersect($bettingSlip->numbers, $bettingSlip->draw->winning_numbers))
                            : 0;
                    @endphp
                    @if($bettingSlip->draw->is_completed)
                        @if($matches > 0)
                            <div class="text-center mb-4">
                                <div class="display-4 text-success mb-2"><i class="fas fa-trophy"></i></div>
                                <h4 class="mb-1">Parabéns! Você acertou {{ $matches }} número{{ $matches > 1 ? 's' : '' }}!</h4>
                                @if($bettingSlip->winnings > 0)
                                    <p class="text-success">Prêmio Virtual: <span class="fw-bold">€{{ number_format($bettingSlip->winnings, 2) }}</span></p>
                                    @if(auth()->check() && $bettingSlip->group && auth()->id() === $bettingSlip->group->admin_id && !$bettingSlip->is_claimed)
                                        <form method="POST" action="{{ route('betting-slips.claim', $bettingSlip) }}" class="mt-3">@csrf<button type="submit" class="btn btn-warning"><i class="fas fa-gift me-1"></i> Premiar/Claimar Prêmio</button></form>
                                    @elseif($bettingSlip->is_claimed)
                                        <div class="alert alert-success mt-3"><i class="fas fa-check-circle me-1"></i> Prêmio já creditado ao apostador.</div>
                                    @endif
                                @else
                                    <p class="text-muted">Infelizmente sem prêmio para {{ $matches }} número{{ $matches > 1 ? 's' : '' }}.</p>
                                @endif
                            </div>
                        @else
                            <div class="text-center mb-4"><div class="display-4 text-muted mb-2"><i class="fas fa-times-circle"></i></div><h4 class="mb-1">Sem Prêmio</h4><p class="text-muted">Infelizmente esta aposta não foi premiada.</p></div>
                            <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i> Continue tentando! A sorte pode sorrir para você no próximo sorteio.</div>
                        @endif
                    @else
                        <div class="text-center mb-4"><div class="display-4 text-warning mb-2"><i class="fas fa-hourglass-half"></i></div><h4 class="mb-1">Aguardando Sorteio</h4><p class="text-muted">O sorteio ainda não foi realizado.</p></div>
                        <div class="alert alert-warning"><i class="fas fa-info-circle me-2"></i> O resultado será atualizado automaticamente após o sorteio.</div>
                    @endif
                </div>
            </div>
            
            @if($bettingSlip->group)
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informações do Grupo</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Membros Ativos:</span>
                        <span class="fw-bold">{{ $bettingSlip->group->members()->wherePivot('status', 'active')->count() }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Custo por Membro:</span>
                        <span class="fw-bold">€{{ number_format($bettingSlip->cost_per_member, 2) }}</span>
                    </div>
                    
                    <div class="d-grid mt-3">
                        <a href="{{ route('groups.show', $bettingSlip->group) }}" class="btn btn-primary">
                            <i class="fas fa-users me-1"></i> Ver Detalhes do Grupo
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .number-ball {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 8px;
        margin-bottom: 8px;
        font-weight: bold;
    }
    
    .main-ball {
        background-color: #e9ecef;
        color: #212529;
    }
    
    .star-ball {
        background-color: #fff3cd;
        color: #664d03;
    }
    
    .matched {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 2px solid #0f5132;
    }
</style>
@endsection
