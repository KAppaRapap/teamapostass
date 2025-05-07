@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Nova Aposta</h2>
        <a href="{{ isset($group) ? route('groups.show', $group) : url('/games/' . ($game->id ?? '')) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
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
            @if((isset($group) && $group->game->name == 'Totobola') || (isset($game) && $game->name == 'Totobola'))
                <h5>Escolha a Liga</h5>
                <form method="GET" action="">
                    <input type="hidden" name="game_id" value="{{ $game->id ?? ($group->game->id ?? '') }}">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($leagues as $league)
                            <button type="submit" name="league" value="{{ $league['code'] ?? $league['id'] }}" class="btn btn-sm {{ (isset($selectedLeague) && $selectedLeague == ($league['code'] ?? $league['id'])) ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $league['display_name'] ?? $league['name'] }}
                            </button>
                        @endforeach
                    </div>
                </form>
                @if(isset($matches) && count($matches) > 0)
                    <form action="{{ isset($group) ? route('betting-slips.store', $group) : route('betting-slips.store-for-game') }}" method="POST">
                        @csrf
                        @if(isset($draws) && count($draws) > 0)
                            <div class="mb-3">
                                <label for="draw_id" class="form-label">Selecione o Sorteio</label>
                                <select class="form-select @error('draw_id') is-invalid @enderror" id="draw_id" name="draw_id" required>
                                    <option value="" selected disabled>Selecione o sorteio</option>
                                    @foreach($draws as $draw)
                                        <option value="{{ $draw->id }}">{{ $draw->name ?? 'Sorteio' }} - {{ \Carbon\Carbon::parse($draw->draw_date)->format('d/m/Y H:i') }}</option>
                                    @endforeach
                                </select>
                                @error('draw_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="alert alert-warning mb-3">Nenhum sorteio disponível para este jogo.</div>
                        @endif
                        @if(isset($game))
                            <input type="hidden" name="game_id" value="{{ $game->id }}">
                        @endif
                        <div id="totobola-matches">
                            @foreach($matches as $idx => $match)
                                <div class="mb-3 p-3 rounded shadow-sm bg-white border">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold fs-6">{{ $match['homeTeam'] ?? '?' }} <span class="text-muted">vs</span> {{ $match['awayTeam'] ?? '?' }}</span>
                                        <span class="badge bg-primary">{{ \Carbon\Carbon::parse($match['date'])->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="mb-1"><i class="fas fa-map-marker-alt text-danger"></i> <span class="text-secondary">{{ $match['venue'] ?? '-' }}</span></div>
                                    <div class="mb-1">
                                        <span class="fw-bold text-success">Favorito: {{ $match['favorite'] }}</span>
                                    </div>
                                    <div class="mb-1 row">
                                        <div class="col">
                                            <span class="fw-bold">Últimos 5 jogos ({{ $match['homeTeam'] }}):</span>
                                            <span>
                                                @foreach($match['last5_home'] as $res)
                                                    <span class="badge {{ $res == 'V' ? 'bg-success' : ($res == 'E' ? 'bg-secondary' : 'bg-danger') }}">{{ $res }}</span>
                                                @endforeach
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="fw-bold">Últimos 5 jogos ({{ $match['awayTeam'] }}):</span>
                                            <span>
                                                @foreach($match['last5_away'] as $res)
                                                    <span class="badge {{ $res == 'V' ? 'bg-success' : ($res == 'E' ? 'bg-secondary' : 'bg-danger') }}">{{ $res }}</span>
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex gap-2">
                                        <input class="btn-check" type="radio" name="predictions[{{ $idx }}]" id="pred1-{{ $idx }}" value="1" autocomplete="off">
                                        <label class="btn btn-outline-dark fw-bold px-4" for="pred1-{{ $idx }}">1</label>
                                        <input class="btn-check" type="radio" name="predictions[{{ $idx }}]" id="predX-{{ $idx }}" value="X" autocomplete="off">
                                        <label class="btn btn-outline-dark fw-bold px-4" for="predX-{{ $idx }}">X</label>
                                        <input class="btn-check" type="radio" name="predictions[{{ $idx }}]" id="pred2-{{ $idx }}" value="2" autocomplete="off">
                                        <label class="btn btn-outline-dark fw-bold px-4" for="pred2-{{ $idx }}">2</label>
                                        <input type="number" class="form-control ms-3" name="amounts[{{ $idx }}]" min="0" step="0.01" placeholder="Valor (€)" style="width:110px;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-grid">
                            <div class="mb-3">
                                <label for="custom_amount" class="form-label">Valor Total da Aposta (€)</label>
                                <input type="number" step="0.01" min="0.01" class="form-control @error('custom_amount') is-invalid @enderror" id="custom_amount" name="custom_amount" required>
                                @error('custom_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Registrar Aposta
                            </button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">Nenhum jogo disponível para esta liga nesta semana.</div>
                @endif
            @else
                @if(isset($group) && $group->game->name != 'Totobola')
                    <label for="draw_id" class="form-label">Selecione o Sorteio</label>
                    @if(empty($draws) || count($draws) == 0)
                        <div class="alert alert-warning">Não há sorteios futuros disponíveis para este jogo.</div>
                    @else
                        <select name="draw_id" id="draw_id" class="form-select @error('draw_id') is-invalid @enderror" required>
                            <option value="">Selecione um sorteio</option>
                            @foreach($draws as $draw)
                            <option value="{{ $draw->id }}">
                                {{ $draw->draw_date->format('d/m/Y H:i') }} - Jackpot: €{{ number_format($draw->jackpot_amount, 2) }}
                            </option>
                            @endforeach
                        </select>
                        @error('draw_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                @endif
                <div class="mb-4">
                    <h5>Selecione os Números</h5>
                    <p class="text-muted">
                        @if(isset($group) && $group->game->name == 'Euromilhões')
                        Selecione 5 números (1-50) e 2 estrelas (1-12)
                        @elseif(isset($group) && $group->game->name == 'Totoloto')
                        Selecione 6 números (1-49)
                        @elseif(isset($game))
                        Selecione os números conforme as regras do jogo
                        @endif
                    </p>
                    <button type="button" class="btn btn-warning mb-2 me-2" id="btn-surpresinha">Surpresinha</button>
                    <button type="button" class="btn btn-secondary mb-2" id="btn-clear">Limpar</button>
                    <div class="mb-3">
                        <div class="number-grid d-flex flex-wrap gap-1">
                            @for($i = 1; $i <= 49; $i++)
                                <div class="number-cell">
                                    <input type="checkbox" class="btn-check main-number" name="numbers[]" id="number-{{ $i }}" value="{{ $i }}" autocomplete="off">
                                    <label class="btn btn-outline-primary mb-1 w-100" for="number-{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const btnSurpresinha = document.getElementById('btn-surpresinha');
                        const btnClear = document.getElementById('btn-clear');
                        if(btnSurpresinha) {
                            btnSurpresinha.addEventListener('click', function() {
                                // Desmarcar todos
                                document.querySelectorAll('.main-number').forEach(cb => cb.checked = false);
                                // Sortear 6 números únicos entre 1 e 49
                                let nums = [];
                                while(nums.length < 6) {
                                    let n = Math.floor(Math.random() * 49) + 1;
                                    if(!nums.includes(n)) nums.push(n);
                                }
                                nums.forEach(n => {
                                    let cb = document.getElementById('number-' + n);
                                    if(cb) cb.checked = true;
                                });
                            });
                        }
                        if(btnClear) {
                            btnClear.addEventListener('click', function() {
                                document.querySelectorAll('.main-number').forEach(cb => cb.checked = false);
                            });
                        }
                    });
                </script>
            @endif
            <form action="{{ isset($group) ? route('betting-slips.store', $group) : route('betting-slips.store-for-game') }}" method="POST">
                @csrf
                <input type="hidden" name="numbers[]" value="1"> 
                <div class="mb-3">
                    <label for="main-bet-amount" class="form-label">Valor a Apostar Geral (€)</label>
                    <input type="number" name="main_bet_amount" id="main-bet-amount" class="form-control" min="0" step="0.01" placeholder="Se quiser apostar o mesmo valor em todos os jogos">
                    <div class="form-text">Preencha este campo <b>apenas</b> se quiser apostar o mesmo valor em todos os jogos. Caso contrário, use os campos de valor individuais de cada jogo.</div>
                </div>
                
                @if(isset($group) && $group->game->name != 'Totobola')
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_system" name="is_system" value="1">
                        <label class="form-check-label" for="is_system">
                            Aposta Múltipla (Desdobramento)
                            <span tabindex="0" data-bs-toggle="tooltip" title="Permite apostar em várias combinações de números automaticamente.">
                                <i class="fas fa-question-circle text-info"></i>
                            </span>
                        </label>
                    </div>
                </div>
                <div id="system_options" class="mb-4" style="display: none;">
                    <h5>Opções de Desdobramento</h5>
                    <div class="mb-3">
                        <label for="system_type" class="form-label">Tipo de Sistema</label>
                        <select name="system_details[type]" id="system_type" class="form-select">
                            <option value="full">Sistema Completo</option>
                            <option value="partial">Sistema Parcial</option>
                        </select>
                        <div class="form-text mt-2" id="system_explanation">
                            <!-- Explicação dinâmica aqui -->
                        </div>
                    </div>
                </div>
                @endif
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Registrar Aposta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .number-grid {
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .number-button {
        margin: 0;
    }
    .btn-check:checked + .btn-outline-primary {
        background-color: #0d6efd;
        color: white;
        box-shadow: 0 0 0 2px #2563eb44;
        border-color: #2563eb;
    }
    .btn-outline-primary:hover {
        background-color: #2563eb22;
    }
    /* Modern card and form UI */
    .card {
        border-radius: 18px;
        box-shadow: 0 4px 24px 0 #00000012;
        border: none;
    }
    .form-control, .form-select {
        border-radius: 10px;
        font-size: 1.08rem;
    }
    .btn-primary, .btn-outline-primary {
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.03em;
    }
    .form-label {
        font-weight: 600;
    }
    .mb-4 > h5, .mb-4 > label {
        color: #0d6efd;
    }
    .alert-info {
        background: linear-gradient(90deg, #e3f0ff 0%, #f7fbff 100%);
        border: none;
        color: #2563eb;
    }
    .alert-warning {
        background: linear-gradient(90deg, #fffbe6 0%, #fff8e1 100%);
        border: none;
        color: #8a6d3b;
    }
    .alert-danger {
        background: linear-gradient(90deg, #ffe6e6 0%, #fff8f8 100%);
        border: none;
        color: #b94a48;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle system bet checkbox
        const systemCheckbox = document.getElementById('is_system');
        const systemOptions = document.getElementById('system_options');
        systemCheckbox.addEventListener('change', function() {
            systemOptions.style.display = this.checked ? 'block' : 'none';
        });

        // --- Melhorias de UX ---
        const mainNumbers = document.querySelectorAll('.main-number');
        const selectedCount = document.getElementById('selected-count');
        const summaryDiv = document.getElementById('bet-summary');
        const summaryNumbers = document.getElementById('summary-numbers');
        const randomPickBtn = document.getElementById('random-pick');
        const clearPickBtn = document.getElementById('clear-pick');
        let mainLimit = 6;
        function updateSelected() {
            const checked = document.querySelectorAll('.main-number:checked');
            selectedCount.textContent = checked.length;
            if (checked.length > 0) {
                summaryDiv.style.display = 'block';
                summaryNumbers.textContent = Array.from(checked).map(cb => cb.value).join(', ');
            } else {
                summaryDiv.style.display = 'none';
            }
        }
        mainNumbers.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const checkedMainNumbers = document.querySelectorAll('.main-number:checked');
                if (checkedMainNumbers.length > mainLimit) {
                    this.checked = false;
                    alert('Você só pode selecionar ' + mainLimit + ' números.');
                }
                updateSelected();
            });
        });
        randomPickBtn.addEventListener('click', function() {
            mainNumbers.forEach(cb => cb.checked = false);
            let nums = Array.from({length: 49}, (_, i) => i + 1);
            nums = nums.sort(() => 0.5 - Math.random()).slice(0, mainLimit);
            mainNumbers.forEach(cb => {
                if (nums.includes(parseInt(cb.value))) cb.checked = true;
            });
            updateSelected();
        });
        clearPickBtn.addEventListener('click', function() {
            mainNumbers.forEach(cb => cb.checked = false);
            updateSelected();
        });
        updateSelected();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const systemCheckbox = document.getElementById('is_system');
        const systemOptions = document.getElementById('system_options');
        const systemType = document.getElementById('system_type');
        const systemExplanation = document.getElementById('system_explanation');
        const systemCostInfo = document.getElementById('system_cost_info');
        const systemCost = document.getElementById('system_cost');
        const mainNumbers = document.querySelectorAll('.main-number');
        const customAmount = document.getElementById('custom_amount');

        const gameId = {{ isset($group) ? $group->game->id : ($game->id ?? 'null') }};
        const pricePerBet = {{ isset($group) ? $group->game->price_per_bet : ($game->price_per_bet ?? 'null') }};
        const csrfToken = '{{ csrf_token() }}';

        function updateSystemExplanation() {
            let value = systemType.value;
            if (value === 'full') {
                systemExplanation.innerHTML = '<b>Sistema Completo:</b> Todas as combinações possíveis dos números selecionados serão apostadas.';
            } else if (value === 'partial') {
                systemExplanation.innerHTML = '<b>Sistema Parcial:</b> Apenas algumas combinações possíveis serão apostadas, reduzindo o custo.';
            } else {
                systemExplanation.innerHTML = '';
            }
        }

        function updateSystemCost() {
            const preco = pricePerBet;
            if (systemCheckbox.checked) {
                const mainSelected = Array.from(document.querySelectorAll('.main-number:checked')).map(cb => parseInt(cb.value));
                const starSelected = Array.from(document.querySelectorAll('.star-number:checked')).map(cb => parseInt(cb.value) - 50);
                const numbers = mainSelected.concat(starSelected);
                fetch("{{ route('betting-slips.generate-system-combinations') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        game_id: gameId,
                        numbers: numbers,
                        system_type: systemType.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    systemCost.textContent = data.total_cost.toFixed(2);
                    systemCostInfo.style.display = 'block';
                })
                .catch(err => console.error('Erro ao gerar combinações:', err));
            } else {
                systemCost.textContent = preco.toFixed(2);
                systemCostInfo.style.display = 'block';
            }
        }

        systemCheckbox.addEventListener('change', function() {
            systemOptions.style.display = this.checked ? 'block' : 'none';
            updateSystemCost();
        });
        systemType.addEventListener('change', function() {
            updateSystemExplanation();
            updateSystemCost();
        });
        mainNumbers.forEach(cb => cb.addEventListener('change', updateSystemCost));
        updateSystemExplanation();
        updateSystemCost();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Euromilhões random pick
        const euromainNumbers = document.querySelectorAll('.main-number');
        const starNumbers = document.querySelectorAll('.star-number');
        const selectedMainCount = document.getElementById('selected-main-count');
        const selectedStarCount = document.getElementById('selected-star-count');
        const randomPickMains = document.getElementById('random-pick-euromains');
        const clearPickMains = document.getElementById('clear-pick-euromains');
        const randomPickStars = document.getElementById('random-pick-stars');
        const clearPickStars = document.getElementById('clear-pick-stars');
        function updateEuroSelectionCounts() {
            const mainChecked = document.querySelectorAll('.main-number:checked').length;
            const starChecked = document.querySelectorAll('.star-number:checked').length;
            selectedMainCount.textContent = mainChecked;
            selectedStarCount.textContent = starChecked;
            euromainNumbers.forEach(cb => cb.disabled = !cb.checked && mainChecked >= 5);
            starNumbers.forEach(cb => cb.disabled = !cb.checked && starChecked >= 2);
        }
        euromainNumbers.forEach(cb => cb.addEventListener('change', updateEuroSelectionCounts));
        starNumbers.forEach(cb => cb.addEventListener('change', updateEuroSelectionCounts));
        randomPickMains.addEventListener('click', function() {
            euromainNumbers.forEach(cb => cb.checked = false);
            let nums = Array.from({length: 50}, (_, i) => i+1).sort(() => 0.5 - Math.random()).slice(0,5);
            euromainNumbers.forEach(cb => { if (nums.includes(parseInt(cb.value))) cb.checked = true; });
            updateEuroSelectionCounts();
        });
        clearPickMains.addEventListener('click', function() {
            euromainNumbers.forEach(cb => cb.checked = false);
            updateEuroSelectionCounts();
        });
        randomPickStars.addEventListener('click', function() {
            starNumbers.forEach(cb => cb.checked = false);
            let stars = Array.from({length: 12}, (_, i) => i+1).sort(() => 0.5 - Math.random()).slice(0,2).map(n => n + 50);
            starNumbers.forEach(cb => { if (stars.includes(parseInt(cb.value))) cb.checked = true; });
            updateEuroSelectionCounts();
        });
        clearPickStars.addEventListener('click', function() {
            starNumbers.forEach(cb => cb.checked = false);
            updateEuroSelectionCounts();
        });
        updateEuroSelectionCounts();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const leagueSelect = document.getElementById('league_select');
        const matchesDiv = document.getElementById('totobola-matches');
        leagueSelect.addEventListener('change', function() {
            const league = this.value;
            matchesDiv.innerHTML = '<div class="text-center py-3"><span class="spinner-border"></span> Carregando jogos...</div>';
            if (!league) {
                matchesDiv.innerHTML = '';
                return;
            }
            fetch(`/betting-slips/league-matches/${league}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.matches || data.matches.length === 0) {
                        matchesDiv.innerHTML = '<div class="alert alert-warning">Nenhum jogo disponível para esta liga neste período.</div>';
                        return;
                    }
                    let html = '';
                    data.matches.forEach((match, idx) => {
                        html += `<div class='mb-3 p-3 rounded shadow-sm bg-white border'>` +
                            `<div class='d-flex justify-content-between align-items-center mb-1'>` +
                                `<span class='fw-bold fs-6'>${match.homeTeam} <span class="text-muted">vs</span> ${match.awayTeam}</span>` +
                                `<span class='badge bg-primary'>${formatDate(match.date)} ${formatHour(match.date)}</span>` +
                            `</div>` +
                            `<div class='mb-1'><i class='fas fa-map-marker-alt text-danger'></i> <span class='text-secondary'>${match.venue ?? '-'}</span></div>` +
                            `<div class='mb-1'><i class='fas fa-star text-warning'></i> Favorito: <b>${match.favorite}</b></div>` +
                            `<div class='row'>` +
                                `<div class='col-auto small'>Últimos 5 ${match.homeTeam}: <span>${renderResults(match.last5_home)}</span></div>` +
                                `<div class='col-auto small'>Últimos 5 ${match.awayTeam}: <span>${renderResults(match.last5_away)}</span></div>` +
                            `</div>` +
                            `<div class='mt-2 d-flex gap-2'>` +
                                `<input class='btn-check' type='radio' name='predictions[${idx}]' id='pred1-${idx}' value='1' autocomplete='off'>` +
                                `<label class='btn btn-outline-dark fw-bold px-4' for='pred1-${idx}'>1</label>` +
                                `<input class='btn-check' type='radio' name='predictions[${idx}]' id='predX-${idx}' value='X' autocomplete='off'>` +
                                `<label class='btn btn-outline-dark fw-bold px-4' for='predX-${idx}'>X</label>` +
                                `<input class='btn-check' type='radio' name='predictions[${idx}]' id='pred2-${idx}' value='2' autocomplete='off'>` +
                                `<label class='btn btn-outline-dark fw-bold px-4' for='pred2-${idx}'>2</label>` +
                                `<input type='number' class='form-control ms-3' name='amounts[${idx}]' min='0' step='0.01' placeholder='Valor (€)' style='width:110px;'>` +
                            `</div>` +
                        `</div>`;
                    });
                    matchesDiv.innerHTML = html;
                })
                .catch(() => {
                    matchesDiv.innerHTML = '<div class="alert alert-danger">Erro ao carregar jogos.</div>';
                });
        });
        function renderResults(arr) {
            if (!arr || arr.length === 0) return '-';
            return arr.map(r => {
                if (r === 'V') return '<span class="text-success fw-bold">V</span>';
                if (r === 'E') return '<span class="text-secondary fw-bold">E</span>';
                if (r === 'D') return '<span class="text-danger fw-bold">D</span>';
                return r;
            }).join(' ');
        }
        function formatDate(str) {
            if (!str) return '-';
            const d = new Date(str);
            return d.toLocaleDateString('pt-PT', { weekday: 'short', day: '2-digit', month: '2-digit' });
        }
        function formatHour(str) {
            if (!str) return '';
            const d = new Date(str);
            return d.toLocaleTimeString('pt-PT', { hour: '2-digit', minute: '2-digit' });
        }
    });
</script>

@endsection
