@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Criar Novo Sorteio</h2>
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
            <form action="{{ route('draws.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="game_id" class="form-label">Jogo</label>
                    <select class="form-select @error('game_id') is-invalid @enderror" id="game_id" name="game_id" required>
                        <option value="" selected disabled>Selecione o jogo</option>
                        @foreach($games as $game)
                        <option value="{{ $game->id }}" @if(old('game_id', $selectedGameId ?? null) == $game->id) selected @endif @if($game->name === 'Totobola') disabled @endif>
                            {{ $game->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('game_id')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                    @if(isset($selectedGame) && $selectedGame->name === 'Totobola')
                    <div class="alert alert-warning mt-3">
                        Não é possível criar sorteios para o Totobola. Ele é permanente.
                    </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="draw_number" class="form-label">Número do Sorteio (opcional)</label>
                    <input type="text" class="form-control @error('draw_number') is-invalid @enderror" id="draw_number" name="draw_number" value="{{ old('draw_number') }}">
                    @error('draw_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="draw_date" class="form-label">Data e Hora do Sorteio</label>
                    <input type="datetime-local" class="form-control @error('draw_date') is-invalid @enderror" id="draw_date" name="draw_date" value="{{ old('draw_date') }}" required>
                    @error('draw_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jackpot" class="form-label">Valor do Jackpot (€)</label>
                    <input type="number" step="0.01" min="0" class="form-control @error('jackpot') is-invalid @enderror" id="jackpot" name="jackpot" value="{{ old('jackpot') }}">
                    @error('jackpot')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input @error('is_completed') is-invalid @enderror" id="is_completed" name="is_completed" value="1" {{ old('is_completed') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_completed">Sorteio já realizado</label>
                    @error('is_completed')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="winning_numbers_section" class="mb-3" style="{{ old('is_completed') ? '' : 'display: none;' }}">
                    <label class="form-label">Números Sorteados</label>
                    <div class="row g-2" id="winning_numbers_container">
                        <div class="col-md-2">
                            <input type="number" min="1" class="form-control winning-number" name="winning_numbers[]" placeholder="Número">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="add_number">
                        <i class="fas fa-plus me-1"></i> Adicionar Número
                    </button>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Salvar Sorteio
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

        // Adicionar campos iniciais se o sorteio já estiver marcado como realizado
        if (isCompletedCheckbox.checked) {
            // Adicionar mais 5 campos (já existe 1 por padrão)
            for (let i = 0; i < 5; i++) {
                addNumberButton.click();
            }
        }
    });
</script>
@endsection
