@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Editar Sorteio</h2>
        <a href="{{ route('draws.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('draws.update', $draw) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="game_id" class="form-label">Jogo</label>
                    <select class="form-select @error('game_id') is-invalid @enderror" id="game_id" name="game_id" required>
                        <option value="" disabled>Selecione o jogo</option>
                        @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ (old('game_id', $draw->game_id) == $game->id) ? 'selected' : '' }}>{{ $game->name }}</option>
                        @endforeach
                    </select>
                    @error('game_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="draw_number" class="form-label">Número do Sorteio (opcional)</label>
                    <input type="text" class="form-control @error('draw_number') is-invalid @enderror" id="draw_number" name="draw_number" value="{{ old('draw_number', $draw->draw_number) }}">
                    @error('draw_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="draw_date" class="form-label">Data e Hora do Sorteio</label>
                    <input type="datetime-local" class="form-control @error('draw_date') is-invalid @enderror" id="draw_date" name="draw_date" value="{{ old('draw_date', $draw->draw_date ? $draw->draw_date->format('Y-m-d\TH:i') : '') }}" required>
                    @error('draw_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jackpot_amount" class="form-label">Valor do Jackpot (€)</label>
                    <input type="number" step="0.01" min="0" class="form-control @error('jackpot_amount') is-invalid @enderror" id="jackpot_amount" name="jackpot_amount" value="{{ old('jackpot_amount', $draw->jackpot_amount) }}">
                    @error('jackpot_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input @error('is_completed') is-invalid @enderror" id="is_completed" name="is_completed" value="1" {{ old('is_completed', $draw->is_completed) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_completed">Sorteio já realizado</label>
                    @error('is_completed')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="winning_numbers_section" class="mb-3" style="{{ old('is_completed', $draw->is_completed) ? '' : 'display: none;' }}">
                    <label class="form-label">Números Sorteados</label>
                    <div class="row g-2" id="winning_numbers_container">
                        @php
                        $winningNumbers = old('winning_numbers', $draw->winning_numbers ?? []);
                        @endphp
                        @foreach($winningNumbers as $number)
                        <div class="col-md-2">
                            <div class="input-group mb-2">
                                <input type="number" min="1" class="form-control winning-number" name="winning_numbers[]" placeholder="Número" value="{{ $number }}">
                                <button type="button" class="btn btn-outline-danger remove-number">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 me-2" id="generate_random_numbers">
                        <i class="fas fa-random me-1"></i> Gerar Números Aleatórios
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="add_number">
                        <i class="fas fa-plus me-1"></i> Adicionar Número
                    </button>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isCompletedCheckbox = document.getElementById('is_completed');
        const winningNumbersSection = document.getElementById('winning_numbers_section');
        const addNumberButton = document.getElementById('add_number');
        const winningNumbersContainer = document.getElementById('winning_numbers_container');

        // Mostrar/ocultar seção de números sorteados
        isCompletedCheckbox.addEventListener('change', function() {
            winningNumbersSection.style.display = this.checked ? 'block' : 'none';
        });

        // Adicionar campo para novo número
        addNumberButton.addEventListener('click', function() {
            const col = document.createElement('div');
            col.className = 'col-md-2';
            col.innerHTML = `
                <div class="input-group mb-2">
                    <input type="number" min="1" class="form-control winning-number" name="winning_numbers[]" placeholder="Número">
                    <button type="button" class="btn btn-outline-danger remove-number">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            winningNumbersContainer.appendChild(col);

            // Adicionar evento para remover número
            col.querySelector('.remove-number').addEventListener('click', function() {
                col.remove();
            });
        });

        // Gerar números aleatórios
        const generateRandomButton = document.getElementById('generate_random_numbers');
        if (generateRandomButton) {
            generateRandomButton.addEventListener('click', function() {
                winningNumbersContainer.innerHTML = '';
                let numbers = [];
                while (numbers.length < 6) {
                    let num = Math.floor(Math.random() * 49) + 1;
                    if (!numbers.includes(num)) numbers.push(num);
                }
                numbers.sort((a, b) => a - b);
                numbers.forEach(function(num) {
                    const col = document.createElement('div');
                    col.className = 'col-md-2';
                    col.innerHTML = `
                        <div class="input-group mb-2">
                            <input type="number" min="1" class="form-control winning-number" name="winning_numbers[]" placeholder="Número" value="${num}">
                            <button type="button" class="btn btn-outline-danger remove-number">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    winningNumbersContainer.appendChild(col);
                    col.querySelector('.remove-number').addEventListener('click', function() {
                        col.remove();
                    });
                });
            });
        }
    });
</script>
@endsection
