@props(['paginator', 'label' => 'Paginação'])

@if ($paginator->hasPages())
<nav aria-label="{{ $label }}" class="cyber-pagination">
    <div class="flex items-center justify-center gap-2">
        <!-- Botão Primeira Página -->
        @if ($paginator->currentPage() > 3)
            <a href="{{ $paginator->url(1) }}" class="pagination-btn pagination-btn-active" title="Primeira página">
                <i class="fas fa-angle-double-left"></i>
            </a>
        @endif

        <!-- Botão Anterior -->
        @if ($paginator->onFirstPage())
            <span class="pagination-btn pagination-btn-disabled" title="Página anterior">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn pagination-btn-active" title="Página anterior">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        <!-- Números das Páginas -->
        <div class="flex items-center gap-1">
            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pagination-number pagination-number-current" title="Página atual">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-number pagination-number-inactive" title="Ir para página {{ $page }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                <span class="pagination-dots">...</span>
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination-number pagination-number-inactive" title="Última página">{{ $paginator->lastPage() }}</a>
            @endif
        </div>

        <!-- Botão Próximo -->
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn pagination-btn-active" title="Próxima página">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="pagination-btn pagination-btn-disabled" title="Próxima página">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif

        <!-- Botão Última Página -->
        @if ($paginator->currentPage() < $paginator->lastPage() - 2)
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination-btn pagination-btn-active" title="Última página">
                <i class="fas fa-angle-double-right"></i>
            </a>
        @endif
    </div>

    <!-- Informações da Paginação -->
    <div class="pagination-info">
        <span class="text-gray-400 text-sm">
            @if ($paginator->firstItem())
                Mostrando <strong class="text-neon-green">{{ $paginator->firstItem() }}</strong> a 
                <strong class="text-neon-green">{{ $paginator->lastItem() }}</strong> 
                de <strong class="text-neon-green">{{ $paginator->total() }}</strong> resultados
            @else
                Nenhum resultado encontrado
            @endif
        </span>
    </div>

    <!-- Navegação Rápida (opcional) -->
    @if ($paginator->lastPage() > 10)
    <div class="pagination-quick-nav">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-gray-400">Ir para:</span>
            <input type="number" 
                   min="1" 
                   max="{{ $paginator->lastPage() }}" 
                   value="{{ $paginator->currentPage() }}"
                   class="pagination-quick-input"
                   onchange="window.location.href = '{{ $paginator->url(1) }}'.replace('page=1', 'page=' + this.value)"
                   title="Digite o número da página">
            <span class="text-gray-400">de {{ $paginator->lastPage() }}</span>
        </div>
    </div>
    @endif
</nav>
@endif

<style>
/* Navegação Rápida */
.pagination-quick-nav {
    margin-top: 0.5rem;
}

.pagination-quick-input {
    width: 60px;
    height: 32px;
    background: rgba(31, 41, 55, 0.8);
    border: 1px solid rgba(75, 85, 99, 0.5);
    border-radius: 6px;
    color: #D1D5DB;
    text-align: center;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.pagination-quick-input:focus {
    outline: none;
    border-color: #00FFB2;
    background: rgba(0, 255, 178, 0.1);
    box-shadow: 0 0 0 2px rgba(0, 255, 178, 0.2);
}

.pagination-quick-input:hover {
    border-color: rgba(0, 255, 178, 0.3);
}

/* Melhorias nos botões de primeira/última página */
.pagination-btn i.fa-angle-double-left,
.pagination-btn i.fa-angle-double-right {
    font-size: 14px;
}

/* Animação para números destacados */
.pagination-number-current strong,
.pagination-info strong {
    color: #00FFB2;
    text-shadow: 0 0 8px rgba(0, 255, 178, 0.5);
}

/* Estados de carregamento */
.pagination-loading {
    opacity: 0.6;
    pointer-events: none;
}

.pagination-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid #00FFB2;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsividade aprimorada */
@media (max-width: 480px) {
    .pagination-quick-nav {
        display: none;
    }
    
    .pagination-btn i.fa-angle-double-left,
    .pagination-btn i.fa-angle-double-right {
        display: none;
    }
}
</style>
