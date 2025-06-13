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

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('betting-slips.store', $group) }}" method="POST" id="bettingForm">
                        @csrf
                        
                        <div class="mb-4">
                            <h5>Informações da Aposta</h5>
                            <p class="text-muted">Grupo: {{ $group->name }} ({{ $group->game->name }})</p>
                        </div>

                        <div class="mb-4">
                            <label for="draw_id" class="form-label">Sorteio</label>
                            <select name="draw_id" id="draw_id" class="form-select" required>
                                <option value="">Selecione um sorteio</option>
                                @foreach($group->game->draws()->where('is_completed', false)->get() as $draw)
                                <option value="{{ $draw->id }}" data-date="{{ $draw->draw_date->format('d/m/Y H:i') }}">
                                    Sorteio de {{ $draw->draw_date->format('d/m/Y H:i') }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        @if($group->game->name === 'Totobola')
                            <div class="mb-4">
                                <h5>Previsões</h5>
                                <div class="row g-3">
                                    @for($i = 1; $i <= 13; $i++)
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">Jogo {{ $i }}</h6>
                                                <div class="btn-group w-100" role="group">
                                                    <input type="radio" class="btn-check" name="predictions[{{ $i-1 }}]" id="pred{{ $i }}_1" value="1" required>
                                                    <label class="btn btn-outline-primary" for="pred{{ $i }}_1">1</label>
                                                    
                                                    <input type="radio" class="btn-check" name="predictions[{{ $i-1 }}]" id="pred{{ $i }}_X" value="X" required>
                                                    <label class="btn btn-outline-primary" for="pred{{ $i }}_X">X</label>
                                                    
                                                    <input type="radio" class="btn-check" name="predictions[{{ $i-1 }}]" id="pred{{ $i }}_2" value="2" required>
                                                    <label class="btn btn-outline-primary" for="pred{{ $i }}_2">2</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <h5>Seleção de Números</h5>
                                <div class="row g-3">
                                    @for($i = 1; $i <= ($group->game->name === 'Euromilhões' ? 50 : 49); $i++)
                                    <div class="col-2 col-md-1">
                                        <div class="form-check">
                                            <input class="form-check-input number-checkbox" type="checkbox" 
                                                   name="numbers[]" value="{{ $i }}" 
                                                   id="number{{ $i }}"
                                                   data-max="{{ $group->game->name === 'Euromilhões' ? 5 : 5 }}">
                                            <label class="form-check-label" for="number{{ $i }}">
                                                {{ $i }}
                                            </label>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                                
                                @if($group->game->name === 'Euromilhões')
                                <div class="mt-4">
                                    <h6>Estrelas</h6>
                                    <div class="row g-3">
                                        @for($i = 1; $i <= 12; $i++)
                                        <div class="col-2 col-md-1">
                                            <div class="form-check">
                                                <input class="form-check-input star-checkbox" type="checkbox" 
                                                       name="numbers[]" value="{{ $i }}" 
                                                       id="star{{ $i }}"
                                                       data-max="2">
                                                <label class="form-check-label" for="star{{ $i }}">
                                                    {{ $i }}
                                                </label>
                                            </div>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="mb-4">
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
                                    <div class="form-text mt-2" id="system_explanation"></div>
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="custom_amount" class="form-label">Valor a Apostar (€)</label>
                            <input type="number" name="custom_amount" id="custom_amount" 
                                   class="form-control" min="0.01" step="0.01" required>
                            <div class="form-text" id="cost_explanation"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="submitBet">
                                <i class="fas fa-save me-1"></i> Registrar Aposta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informações do Jogo</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Preço por Aposta</h6>
                        <p class="text-primary fw-bold mb-0">€{{ number_format($group->game->price_per_bet, 2) }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Limite Diário</h6>
                        <p class="text-muted mb-0">€{{ number_format($group->game->daily_limit, 2) }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Seu Saldo</h6>
                        <p class="text-success fw-bold mb-0">€{{ number_format(Auth::user()->virtual_balance, 2) }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Regras do Jogo</h6>
                        <p class="text-muted mb-0">{{ $group->game->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
    const customAmount = document.getElementById('custom_amount');
    const costExplanation = document.getElementById('cost_explanation');
    const numberCheckboxes = document.querySelectorAll('.number-checkbox');
    const starCheckboxes = document.querySelectorAll('.star-checkbox');
    const submitButton = document.getElementById('submitBet');

    // Atualizar explicação do sistema
    function updateSystemExplanation() {
        const value = systemType.value;
        if (value === 'full') {
            systemExplanation.innerHTML = '<b>Sistema Completo:</b> Todas as combinações possíveis dos números selecionados serão apostadas.';
        } else if (value === 'partial') {
            systemExplanation.innerHTML = '<b>Sistema Parcial:</b> Apenas algumas combinações possíveis serão apostadas, reduzindo o custo.';
        }
    }

    // Mostrar/ocultar opções do sistema
    systemCheckbox.addEventListener('change', function() {
        systemOptions.style.display = this.checked ? 'block' : 'none';
        updateSystemExplanation();
    });

    // Atualizar explicação quando mudar o tipo de sistema
    systemType.addEventListener('change', updateSystemExplanation);

    // Controlar número máximo de seleções
    function handleCheckboxGroup(checkboxes, max) {
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checked = document.querySelectorAll(`.${this.classList.contains('star-checkbox') ? 'star-checkbox' : 'number-checkbox'}:checked`);
                if (checked.length > max) {
                    this.checked = false;
                    alert(`Você pode selecionar no máximo ${max} números.`);
                }
            });
        });
    }

    handleCheckboxGroup(numberCheckboxes, 5);
    handleCheckboxGroup(starCheckboxes, 2);

    // Atualizar explicação do custo
    function updateCostExplanation() {
        const amount = parseFloat(customAmount.value) || 0;
        const gamePrice = {{ $group->game->price_per_bet }};
        const isSystem = systemCheckbox.checked;
        
        if (isSystem) {
            const selectedNumbers = document.querySelectorAll('.number-checkbox:checked').length;
            const selectedStars = document.querySelectorAll('.star-checkbox:checked').length;
            const totalNumbers = selectedNumbers + selectedStars;
            
            if (totalNumbers < 5) {
                costExplanation.innerHTML = '<span class="text-danger">Selecione pelo menos 5 números para apostas múltiplas.</span>';
                submitButton.disabled = true;
            } else {
                const combinations = calculateCombinations(selectedNumbers, selectedStars);
                const totalCost = combinations * gamePrice;
                
                costExplanation.innerHTML = `
                    <span class="text-info">
                        Total de combinações: ${combinations}<br>
                        Custo mínimo: €${totalCost.toFixed(2)}
                    </span>
                `;
                submitButton.disabled = amount < totalCost;
            }
        } else {
            costExplanation.innerHTML = `
                <span class="text-info">
                    Custo por aposta: €${gamePrice.toFixed(2)}
                </span>
            `;
            submitButton.disabled = amount < gamePrice;
        }
    }

    // Calcular combinações para apostas múltiplas
    function calculateCombinations(numbers, stars) {
        if (numbers < 5) return 0;
        
        let combinations = 1;
        for (let i = 0; i < 5; i++) {
            combinations *= (numbers - i);
        }
        combinations /= 120; // 5! = 120
        
        if (stars > 0) {
            combinations *= (stars * (stars - 1)) / 2;
        }
        
        return Math.floor(combinations);
    }

    // Atualizar explicação do custo quando mudar valores
    customAmount.addEventListener('input', updateCostExplanation);
    systemCheckbox.addEventListener('change', updateCostExplanation);
    numberCheckboxes.forEach(cb => cb.addEventListener('change', updateCostExplanation));
    starCheckboxes.forEach(cb => cb.addEventListener('change', updateCostExplanation));

    // Validação do formulário
    document.getElementById('bettingForm').addEventListener('submit', function(e) {
        const selectedNumbers = document.querySelectorAll('.number-checkbox:checked').length;
        const selectedStars = document.querySelectorAll('.star-checkbox:checked').length;
        
        if (selectedNumbers < 5) {
            e.preventDefault();
            alert('Selecione pelo menos 5 números.');
            return;
        }
        
        if ({{ $group->game->name === 'Euromilhões' ? 'true' : 'false' }} && selectedStars < 2) {
            e.preventDefault();
            alert('Selecione 2 estrelas.');
            return;
        }
        
        const amount = parseFloat(customAmount.value);
        if (isNaN(amount) || amount <= 0) {
            e.preventDefault();
            alert('Insira um valor válido para a aposta.');
            return;
        }
    });
});
</script>
@endpush
