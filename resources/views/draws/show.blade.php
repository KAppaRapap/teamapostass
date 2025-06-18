@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detalhes do Sorteio</h2>
        <a href="{{ route('draws.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ $draw->game->name }}</h5>
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item"><strong>Número do Sorteio:</strong> {{ $draw->draw_number ?: '-' }}</li>
                <li class="list-group-item"><strong>Data e Hora:</strong> {{ $draw->draw_date->format('d/m/Y H:i') }}</li>
                <li class="list-group-item"><strong>Jackpot:</strong> €{{ number_format($draw->jackpot_amount, 2) }}</li>
                <li class="list-group-item"><strong>Estado:</strong> 
                    @if($draw->is_completed)
                        <span class="badge bg-success">Realizado</span>
                    @elseif($draw->draw_date < now())
                        <span class="badge bg-warning">Pendente</span>
                    @else
                        <span class="badge bg-primary">Próximo</span>
                    @endif
                </li>
                @if(!empty($draw->winning_numbers))
                <li class="list-group-item"><strong>Números Sorteados:</strong> 
                    <div class="d-flex flex-wrap gap-1">
                        @if($draw->game->name == 'Euromilhões')
                            @if(isset($draw->winning_numbers['numbers']) && is_array($draw->winning_numbers['numbers']))
                                @foreach($draw->winning_numbers['numbers'] as $number)
                                    <span class="badge bg-primary p-2">{{ $number }}</span>
                                @endforeach
                            @endif
                            @if(isset($draw->winning_numbers['stars']) && is_array($draw->winning_numbers['stars']))
                                @foreach($draw->winning_numbers['stars'] as $star)
                                    <span class="badge bg-warning p-2"><i class="fas fa-star me-1"></i>{{ $star }}</span>
                                @endforeach
                            @endif
                        @elseif($draw->game->name == 'Totoloto')
                            @if(is_array($draw->winning_numbers))
                                @foreach($draw->winning_numbers as $number)
                                    <span class="badge bg-primary p-2">{{ $number }}</span>
                                @endforeach
                            @endif
                        @elseif($draw->game->name == 'Totobola')
                            @if(is_array($draw->winning_numbers))
                                @foreach($draw->winning_numbers as $result)
                                    <span class="badge bg-info p-2">{{ $result }}</span>
                                @endforeach
                            @endif
                        @elseif($draw->game->name == 'Placard')
                            @if(isset($draw->winning_numbers['message']))
                                <span>{{ $draw->winning_numbers['message'] }}</span>
                            @else
                                {{-- Fallback if Placard has a different array structure --}}
                                <span>{{ implode(', ', (array) $draw->winning_numbers) }}</span>
                            @endif
                        @else
                            {{-- Fallback for unknown game types or simple arrays --}}
                            @if(is_array($draw->winning_numbers))
                                <span>{{ implode(', ', $draw->winning_numbers) }}</span>
                            @else
                                <span>{{ $draw->winning_numbers }}</span>
                            @endif
                        @endif
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Apostas deste Sorteio</h5>
        </div>
        <div class="card-body">
            @if($bettingSlips->count())
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Grupo</th>
                            <th>Utilizador</th>
                            <th>Data da Aposta</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                        
                    </thead>
                    <tbody>
                        @foreach($bettingSlips as $bettingSlip)
                        <tr>
                            <td>{{ $bettingSlip->group->name ?? '-' }}</td>
                            <td>{{ $bettingSlip->user->name ?? '-' }}</td>
                            <td>{{ $bettingSlip->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($bettingSlip->is_winner)
                                    <span class="badge bg-success">Ganhou</span>
                                @else
                                    <span class="badge bg-secondary">Pendente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('betting-slips.show', $bettingSlip) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $bettingSlips->links('groups.pagination') }}
            </div>
            @else
            <div class="alert alert-info mb-0">Nenhuma aposta registada para este sorteio.</div>
            @endif
        </div>
    </div>
</div>
@endsection
