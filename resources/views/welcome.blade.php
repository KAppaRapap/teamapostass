@extends('layouts.app')

@section('title', 'Início')
@section('description', 'O casino da nova geração. Aposta, vence e partilha com a tua equipa.')

@section('content')
    <!-- Hero Section -->
    <section id="inicio" class="hero-bg flex items-center justify-center relative">
        <canvas id="particles" class="particles-bg"></canvas>
        <div class="hero-content text-center px-6 max-w-6xl mx-auto">
            <h1 class="hero-title font-orbitron font-bold text-5xl lg:text-7xl mb-6 animate-slide-up">
                O Casino da <span class="text-neon-green">Nova Geração</span>.<br>
                Aposta. Vence. <span class="text-neon-pink">Partilha</span>.
            </h1>
            <p class="hero-subtitle text-xl lg:text-2xl text-gray-300 mb-12 animate-fade-in max-w-3xl mx-auto">
                Explora Bomb Mine, Crash e Dice. Apostas em tempo real com a tua equipa.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in">
                <a href="#jogos" class="btn-primary text-lg px-8 py-4">
                    🎮 Jogar Agora
                </a>
            </div>
            
            <!-- Ilustração animada -->
            <div class="mt-16 animate-float">
                <div class="relative">
                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-neon-green to-neon-pink rounded-full opacity-20 blur-xl"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-dice text-6xl text-neon-green"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Jogos -->
    <section id="jogos" class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-orbitron font-bold text-4xl lg:text-5xl mb-6">
                    Os Nossos <span class="text-neon-green">Jogos</span>
                </h2>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Três jogos únicos, uma experiência inesquecível. Escolhe o teu favorito e começa a ganhar.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Bomb Mine -->
                <div class="game-card p-8 text-center group">
                    <div class="text-6xl mb-6 group-hover:scale-110 transition-transform duration-300">💣</div>
                    <h3 class="font-orbitron font-bold text-2xl mb-4 text-white">Bomb Mine</h3>
                    <p class="text-gray-300 mb-8 leading-relaxed">
                        Escolhe as casas. Evita as bombas. Recolhe moedas.
                    </p>
                    <a href="{{ route('games.bombmine') }}" class="btn-primary w-full">
                        Jogar Agora
                    </a>
                </div>
                
                <!-- Crash -->
                <div class="game-card p-8 text-center group">
                    <div class="text-6xl mb-6 group-hover:scale-110 transition-transform duration-300">📉</div>
                    <h3 class="font-orbitron font-bold text-2xl mb-4 text-white">Crash</h3>
                    <p class="text-gray-300 mb-8 leading-relaxed">
                        Aposta antes do gráfico colapsar. Sai a tempo.
                    </p>
                    <a href="{{ route('games.crash') }}" class="btn-primary w-full">
                        Jogar Agora
                    </a>
                </div>
                
                <!-- Dice -->
                <div class="game-card p-8 text-center group">
                    <div class="text-6xl mb-6 group-hover:scale-110 transition-transform duration-300">🎲</div>
                    <h3 class="font-orbitron font-bold text-2xl mb-4 text-white">Dice</h3>
                    <p class="text-gray-300 mb-8 leading-relaxed">
                        Lança os dados. Aposta alto. Multiplica.
                    </p>
                    <a href="{{ route('games.dice') }}" class="btn-primary w-full">
                        Jogar Agora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Comunidade -->
    <section id="comunidade" class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-orbitron font-bold text-4xl lg:text-5xl mb-6">
                    <span class="text-neon-green">Comunidade</span> & Grupos
                </h2>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Conecta-te com outros jogadores. Estratégia, conversa e apostas em equipa.
                </p>
            </div>
            
            <div class="flex flex-col items-center justify-center min-h-[400px] text-center">
                <h3 class="font-orbitron font-bold text-3xl mb-6 text-white">Junta-te à Comunidade</h3>
                <p class="text-gray-300 mb-6 max-w-xl">Cria ou participa em grupos exclusivos. Partilha estratégias, acompanha as vitórias dos outros jogadores e forma equipas para apostas em conjunto. A nossa comunidade é o coração do TeamApostas.</p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-3 text-white justify-center"><i class="fas fa-users"></i> +5,000 jogadores ativos</li>
                    <li class="flex items-center gap-3 text-white justify-center"><i class="fas fa-comments"></i> Chats em tempo real</li>
                    <li class="flex items-center gap-3 text-white justify-center"><i class="fas fa-trophy"></i> Competições semanais</li>
                </ul>
                <a href="{{ route('groups.index') }}" class="btn-primary">Ver Grupos</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
    // Particles Animation for Hero Section
    const canvas = document.getElementById('particles');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        const particles = [];
        const particleCount = 50;
        
        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 2 + 1,
                color: Math.random() > 0.5 ? '#00FFB2' : '#FF005C'
            });
        }
        
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(particle => {
                ctx.beginPath();
                ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                ctx.fillStyle = particle.color;
                ctx.globalAlpha = 0.6;
                ctx.fill();
                
                particle.x += particle.vx;
                particle.y += particle.vy;
                
                if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
                if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;
            });
            
            requestAnimationFrame(animate);
        }
        
        animate();
        
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    }
    </script>
@endpush
