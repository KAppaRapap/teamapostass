@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Resultados</h2>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" id="resultsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="euromilhoes-tab" data-bs-toggle="tab" data-bs-target="#euromilhoes" type="button" role="tab" aria-controls="euromilhoes" aria-selected="true">Euromilhões</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="totoloto-tab" data-bs-toggle="tab" data-bs-target="#totoloto" type="button" role="tab" aria-controls="totoloto" aria-selected="false">Totoloto</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="totobola-tab" data-bs-toggle="tab" data-bs-target="#totobola" type="button" role="tab" aria-controls="totobola" aria-selected="false">Totobola</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="placard-tab" data-bs-toggle="tab" data-bs-target="#placard" type="button" role="tab" aria-controls="placard" aria-selected="false">Placard</button>
                </li>
            </ul>
            
            <div class="tab-content" id="resultsTabsContent">
                <!-- Euromilhões -->
                <div class="tab-pane fade show active" id="euromilhoes" role="tabpanel" aria-labelledby="euromilhoes-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Números</th>
                                    <th>Jackpot</th>
                                    <th>Suas Apostas</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completedDraws->where('game.name', 'Euromilhões') as $draw)
                                <tr>
                                    <td>{{ $draw->draw_date->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @foreach($draw->winning_numbers as $number)
                                                @if($number <= 50)
                                                <span class="badge rounded-pill bg-primary p-2">{{ $number }}</span>
                                                @else
                                                <span class="badge rounded-pill bg-warning p-2">{{ $number - 50 }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>€{{ number_format($draw->jackpot_amount, 2) }}</td>
                                    <td>
                                        @if(Auth::check())
                                            {{ Auth::user()->bettingSlips()->where('draw_id', $draw->id)->count() }} apostas
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('results.show', $draw) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">Nenhum resultado disponível para Euromilhões</td>
                                </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Totoloto -->
                <div class="tab-pane fade" id="totoloto" role="tabpanel" aria-labelledby="totoloto-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Números</th>
                                    <th>Jackpot</th>
                                    <th>Suas Apostas</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completedDraws->where('game.name', 'Totoloto') as $draw)
                                <tr>
                                    <td>{{ $draw->draw_date->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @foreach($draw->winning_numbers as $number)
                                                <span class="badge rounded-pill bg-primary p-2">{{ $number }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>€{{ number_format($draw->jackpot_amount, 2) }}</td>
                                    <td>
                                        @if(Auth::check())
                                            {{ Auth::user()->bettingSlips()->where('draw_id', $draw->id)->count() }} apostas
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('results.show', $draw) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">Nenhum resultado disponível para Totoloto</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Totobola -->
                <div class="tab-pane fade" id="totobola" role="tabpanel" aria-labelledby="totobola-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Jogos</th>
                                    <th>Jackpot</th>
                                    <th>Suas Apostas</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completedDraws->where('game.name', 'Totobola') as $draw)
                                <tr>
                                    <td>{{ $draw->draw_date->format('d/m/Y') }}</td>
                                    <td>{{ $draw->draw_number ?: '13 jogos de futebol' }}</td>
                                    <td>€{{ number_format($draw->jackpot_amount, 2) }}</td>
                                    <td>
                                        @if(Auth::check())
                                            {{ Auth::user()->bettingSlips()->where('draw_id', $draw->id)->count() }} apostas
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('results.show', $draw) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">Nenhum resultado disponível para Totobola</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Placard -->
                <div class="tab-pane fade" id="placard" role="tabpanel" aria-labelledby="placard-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Eventos</th>
                                    <th>Suas Apostas</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completedDraws->where('game.name', 'Placard') as $draw)
                                <tr>
                                    <td>{{ $draw->draw_date->format('d/m/Y') }}</td>
                                    <td>{{ $draw->draw_number ?: 'Eventos desportivos diversos' }}</td>
                                    <td>
                                        @if(Auth::check())
                                            {{ Auth::user()->bettingSlips()->where('draw_id', $draw->id)->count() }} apostas
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('results.show', $draw) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Nenhum resultado disponível para Placard</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
