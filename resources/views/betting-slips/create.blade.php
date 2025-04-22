@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Nova Aposta</h2>
        <a href="{{ route('groups.show', $group) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao Grupo
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
            <form action="{{ route('betting-slips.store', $group) }}" method="POST">
                @csrf
                <input type="hidden" name="numbers[]" value="1"> 
                <div class="mb-4">
                    <h5>Informações da Aposta</h5>
                    <p class="text-muted">Grupo: {{ $group->name }} ({{ $group->game->name }})</p>
                </div>
                
                <div class="mb-3">
                    <label for="draw_id" class="form-label">Selecione o Sorteio</label>
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
                </div>
                
                <div class="mb-4">
                    <h5>Selecione os Números</h5>
                    <p class="text-muted">
                        @if($group->game->name == 'Euromilhões')
                        Selecione 5 números (1-50) e 2 estrelas (1-12)
                        @elseif($group->game->name == 'Totoloto')
                        Selecione 6 números (1-49)
                        @else
                        Selecione os números conforme as regras do jogo
                        @endif
                    </p>
                    
                    <div class="mb-3">
                        <div class="number-grid">
                            @if($group->game->name == 'Euromilhões')
                                <div class="mb-3">
                                    <label class="form-label">Números (selecione 5)</label>
                                    <div class="d-flex flex-wrap">
                                        @for($i = 1; $i <= 50; $i++)
                                        <div class="number-button">
                                            <input type="checkbox" name="numbers[]" id="number{{ $i }}" value="{{ $i }}" class="btn-check main-number" autocomplete="off">
                                            <label for="number{{ $i }}" class="btn btn-outline-primary">{{ $i }}</label>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Estrelas (selecione 2)</label>
                                    <div class="d-flex flex-wrap">
                                        @for($i = 1; $i <= 12; $i++)
                                        <div class="number-button">
                                            <input type="checkbox" name="numbers[]" id="star{{ $i }}" value="{{ $i + 50 }}" class="btn-check star-number" autocomplete="off">
                                            <label for="star{{ $i }}" class="btn btn-outline-warning">{{ $i }}</label>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                            @elseif($group->game->name == 'Totoloto')
                                <div class="mb-2 d-flex align-items-center">
                                    <span class="fw-bold">Selecionados: <span id="selected-count">0</span></span>
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" id="random-pick">Surpresinha</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="clear-pick">Limpar</button>
                                </div>
                                <div class="mb-3">
                                    <div class="number-grid">
                                        @for($i = 1; $i <= 49; $i++)
                                        <div class="number-button">
                                            <input type="checkbox" name="numbers[]" id="number{{ $i }}" value="{{ $i }}" class="btn-check main-number" autocomplete="off">
                                            <label for="number{{ $i }}" class="btn btn-outline-primary">{{ $i }}</label>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mt-3" id="bet-summary" style="display:none;">
                                    <strong>Aposta:</strong> <span id="summary-numbers"></span>
                                </div>
                            @else
                                <div class="mb-2 d-flex align-items-center">
                                    <span class="fw-bold">Selecionados: <span id="selected-count">0</span></span>
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" id="random-pick">Surpresinha</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="clear-pick">Limpar</button>
                                </div>
                                <div class="mb-3">
                                    <div class="number-grid">
                                        @for($i = 1; $i <= 49; $i++)
                                        <div class="number-button">
                                            <input type="checkbox" name="numbers[]" id="number{{ $i }}" value="{{ $i }}" class="btn-check main-number" autocomplete="off">
                                            <label for="number{{ $i }}" class="btn btn-outline-primary">{{ $i }}</label>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mt-3" id="bet-summary" style="display:none;">
                                    <strong>Aposta:</strong> <span id="summary-numbers"></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="custom_amount" class="form-label">Valor a Apostar (€)</label>
                    <input type="number" min="0.01" step="0.01" class="form-control" id="custom_amount" name="custom_amount" value="" placeholder="Insira o valor a apostar" required>
                    <div class="form-text">O valor deve ser igual ou maior ao custo estimado do sistema ou mínimo do jogo.</div>
                    <div class="alert alert-info py-2 mt-2" id="system_cost_info" style="display:block;">
                        Custo estimado: <span id="system_cost">-</span> €
                    </div>
                </div>
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

        function calcularCombinacoes(n, k) {
            // Combinação simples: n! / (k! * (n-k)!)
            function fatorial(x) { return x <= 1 ? 1 : x * fatorial(x-1); }
            return fatorial(n) / (fatorial(k) * fatorial(n-k));
        }
        function updateSystemCost() {
            let checked = document.querySelectorAll('.main-number:checked');
            let numSelected = checked.length;
            let tipo = systemType.value;
            let preco = {{ $group->game->price_per_bet ?? 2 }};
            let info = '-';
            let show = false;
            if (systemCheckbox.checked && numSelected > 0) {
                if (tipo === 'full') {
                    let k = {{ $group->game->name == 'Euromilhões' ? 5 : ($group->game->name == 'Totoloto' ? 6 : 0) }};
                    if (numSelected >= k && k > 0) {
                        let comb = calcularCombinacoes(numSelected, k);
                        info = (comb * preco).toFixed(2);
                        show = true;
                    }
                } else if (tipo === 'partial') {
                    // Simulação: metade das combinações do sistema completo
                    let k = {{ $group->game->name == 'Euromilhões' ? 5 : ($group->game->name == 'Totoloto' ? 6 : 0) }};
                    if (numSelected >= k && k > 0) {
                        let comb = calcularCombinacoes(numSelected, k);
                        info = ((comb/2) * preco).toFixed(2);
                        show = true;
                    }
                }
            }
            systemCost.textContent = info;
            systemCostInfo.style.display = show ? 'block' : 'none';
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
@endsection
