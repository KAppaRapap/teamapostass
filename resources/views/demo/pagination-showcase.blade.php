@extends('layouts.app')

@section('title', 'Demo - Nova Paginação Cyberpunk')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-gray-900 rounded-lg shadow-lg p-6 border border-gray-800 mb-8">
            <h1 class="text-3xl font-bold text-neon-green mb-4">
                <i class="fas fa-rocket mr-3"></i>Nova Paginação Cyberpunk
            </h1>
            <p class="text-gray-300 mb-4">
                Sistema de paginação completamente redesenhado com tema cyberpunk, animações suaves e melhor experiência do usuário.
            </p>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-neon-green bg-opacity-20 text-neon-green rounded-full text-sm">✨ Animações Suaves</span>
                <span class="px-3 py-1 bg-blue-500 bg-opacity-20 text-blue-400 rounded-full text-sm">📱 Responsivo</span>
                <span class="px-3 py-1 bg-purple-500 bg-opacity-20 text-purple-400 rounded-full text-sm">🎨 Tema Cyberpunk</span>
                <span class="px-3 py-1 bg-yellow-500 bg-opacity-20 text-yellow-400 rounded-full text-sm">⚡ Performance</span>
            </div>
        </div>

        <!-- Comparação Antes/Depois -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <!-- Antes -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h2 class="text-xl font-bold text-red-400 mb-4">
                    <i class="fas fa-times-circle mr-2"></i>Antes (Antigo)
                </h2>
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <div class="flex justify-center gap-2">
                        <span class="px-3 py-2 bg-gray-700 text-gray-300 rounded text-sm">&laquo;</span>
                        <span class="px-3 py-2 bg-blue-600 text-white rounded text-sm">1</span>
                        <span class="px-3 py-2 bg-gray-700 text-gray-300 rounded text-sm">2</span>
                        <span class="px-3 py-2 bg-gray-700 text-gray-300 rounded text-sm">3</span>
                        <span class="px-3 py-2 bg-gray-700 text-gray-300 rounded text-sm">&raquo;</span>
                    </div>
                </div>
                <ul class="text-sm text-gray-400 space-y-2">
                    <li>❌ Design básico do Bootstrap</li>
                    <li>❌ Sem animações</li>
                    <li>❌ Cores padrão</li>
                    <li>❌ Sem efeitos visuais</li>
                    <li>❌ Pouco espaçamento</li>
                </ul>
            </div>

            <!-- Depois -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h2 class="text-xl font-bold text-neon-green mb-4">
                    <i class="fas fa-check-circle mr-2"></i>Depois (Novo)
                </h2>
                <div class="bg-gray-800 rounded-lg p-4 mb-4">
                    <!-- Simulação da nova paginação -->
                    <div class="flex items-center justify-center gap-2">
                        <span class="pagination-btn pagination-btn-active">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <div class="flex items-center gap-1">
                            <span class="pagination-number pagination-number-current">1</span>
                            <span class="pagination-number pagination-number-inactive">2</span>
                            <span class="pagination-number pagination-number-inactive">3</span>
                        </div>
                        <span class="pagination-btn pagination-btn-active">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </div>
                </div>
                <ul class="text-sm text-neon-green space-y-2">
                    <li>✅ Design cyberpunk personalizado</li>
                    <li>✅ Animações suaves</li>
                    <li>✅ Cores neon vibrantes</li>
                    <li>✅ Efeitos de hover/focus</li>
                    <li>✅ Espaçamento otimizado</li>
                </ul>
            </div>
        </div>

        <!-- Recursos Principais -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mb-8">
            <h2 class="text-2xl font-bold text-neon-green mb-6">
                <i class="fas fa-star mr-2"></i>Recursos Principais
            </h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-neon-green to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-magic text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Animações Fluidas</h3>
                    <p class="text-gray-400 text-sm">Transições suaves com efeitos de hover, transformações 3D e animações de carregamento.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Totalmente Responsivo</h3>
                    <p class="text-gray-400 text-sm">Adapta-se perfeitamente a todos os tamanhos de tela, desde mobile até desktop.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-palette text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Tema Cyberpunk</h3>
                    <p class="text-gray-400 text-sm">Cores neon, gradientes futuristas e efeitos de brilho que combinam com o site.</p>
                </div>
            </div>
        </div>

        <!-- Demonstração Interativa -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mb-8">
            <h2 class="text-2xl font-bold text-neon-green mb-6">
                <i class="fas fa-play mr-2"></i>Demonstração Interativa
            </h2>
            <p class="text-gray-300 mb-6">Experimente a nova paginação em ação:</p>
            
            <!-- Simulação de paginação -->
            <div class="cyber-pagination">
                <div class="flex items-center justify-center gap-2">
                    <span class="pagination-btn pagination-btn-disabled">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                    <div class="flex items-center gap-1">
                        <span class="pagination-number pagination-number-current">1</span>
                        <a href="#" class="pagination-number pagination-number-inactive" onclick="return false;">2</a>
                        <a href="#" class="pagination-number pagination-number-inactive" onclick="return false;">3</a>
                        <span class="pagination-dots">...</span>
                        <a href="#" class="pagination-number pagination-number-inactive" onclick="return false;">10</a>
                    </div>
                    <a href="#" class="pagination-btn pagination-btn-active" onclick="return false;">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="pagination-info">
                    <span class="text-gray-400 text-sm">
                        Mostrando <strong class="text-neon-green">1</strong> a 
                        <strong class="text-neon-green">20</strong> 
                        de <strong class="text-neon-green">200</strong> resultados
                    </span>
                </div>
            </div>
        </div>

        <!-- Código de Implementação -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <h2 class="text-2xl font-bold text-neon-green mb-4">
                <i class="fas fa-code mr-2"></i>Como Usar
            </h2>
            <p class="text-gray-300 mb-4">
                A nova paginação é super fácil de usar. Basta substituir a paginação antiga por:
            </p>
            <div class="bg-gray-800 rounded-lg p-4 font-mono text-sm">
                <div class="text-green-400 mb-2">{{-- Uso simples --}}</div>
                <div class="text-white mb-4">&lt;x-cyber-pagination :paginator="$items" /&gt;</div>
                
                <div class="text-green-400 mb-2">{{-- Com label personalizado --}}</div>
                <div class="text-white">&lt;x-cyber-pagination :paginator="$items" label="Paginação de produtos" /&gt;</div>
            </div>
        </div>
    </div>
</div>
@endsection
