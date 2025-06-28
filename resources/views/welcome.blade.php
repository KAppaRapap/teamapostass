@extends('layouts.app')

@section('title', 'Início')
@section('description', 'O casino da nova geração. Aposta, vence e partilha com a tua equipa.')

@section('content')
    <!-- Hero Section -->
    <section id="inicio" class="hero-bg flex items-center justify-center relative min-h-screen">
        <canvas id="particles" class="particles-bg"></canvas>
        <div class="hero-content text-center max-w-7xl mx-auto px-8 lg:px-16">
            <h1 class="hero-title font-orbitron font-bold text-6xl lg:text-8xl mb-8 animate-slide-up leading-tight">
                O Casino da <span class="text-neon-green">Nova Geração</span>.<br>
                Aposta. Vence. <span class="text-neon-pink">Partilha</span>.
            </h1>
            <p class="hero-subtitle text-2xl lg:text-3xl text-gray-300 mb-16 animate-fade-in max-w-4xl mx-auto leading-relaxed">
                Explora Bomb Mine, Crash e Dice. Apostas em tempo real com a tua equipa.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-fade-in mb-20">
                <a href="#jogos" class="btn-primary text-xl px-12 py-5 shadow-2xl">
                    🎮 Jogar Agora
                </a>
                <a href="{{ route('groups.index') }}" class="btn-outline text-xl px-12 py-5">
                    👥 Ver Comunidade
                </a>
            </div>

            <!-- Ilustração animada -->
            <div class="mt-20 animate-float">
                <div class="relative">
                    <div class="w-40 h-40 mx-auto bg-gradient-to-br from-neon-green to-neon-pink rounded-full opacity-20 blur-2xl"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-dice text-7xl text-neon-green drop-shadow-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Jogos -->
    <section id="jogos" class="py-32">
        <div class="max-w-8xl mx-auto">
            <div class="text-center mb-24">
                <h2 class="font-orbitron font-bold text-5xl lg:text-6xl mb-8">
                    Os Nossos <span class="text-neon-green">Jogos</span>
                </h2>
                <p class="text-2xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Três jogos únicos, uma experiência inesquecível. Escolhe o teu favorito e começa a ganhar.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-12 px-8">
                <!-- Bomb Mine -->
                <div class="game-card p-12 text-center group">
                    <div class="text-8xl mb-8 group-hover:scale-110 transition-transform duration-300">💣</div>
                    <h3 class="font-orbitron font-bold text-3xl mb-6 text-white">Bomb Mine</h3>
                    <p class="text-gray-300 mb-10 leading-relaxed text-lg">
                        Escolhe as casas. Evita as bombas. Recolhe moedas.
                    </p>
                    <a href="{{ route('games.bombmine') }}" class="btn-primary w-full text-lg py-4">
                        Jogar Agora
                    </a>
                </div>

                <!-- Crash -->
                <div class="game-card p-12 text-center group">
                    <div class="text-8xl mb-8 group-hover:scale-110 transition-transform duration-300">📉</div>
                    <h3 class="font-orbitron font-bold text-3xl mb-6 text-white">Crash</h3>
                    <p class="text-gray-300 mb-10 leading-relaxed text-lg">
                        Aposta antes do gráfico colapsar. Sai a tempo.
                    </p>
                    <a href="{{ route('games.crash') }}" class="btn-primary w-full text-lg py-4">
                        Jogar Agora
                    </a>
                </div>

                <!-- Dice -->
                <div class="game-card p-12 text-center group">
                    <div class="text-8xl mb-8 group-hover:scale-110 transition-transform duration-300">🎲</div>
                    <h3 class="font-orbitron font-bold text-3xl mb-6 text-white">Dice</h3>
                    <p class="text-gray-300 mb-10 leading-relaxed text-lg">
                        Lança os dados. Aposta alto. Multiplica.
                    </p>
                    <a href="{{ route('games.dice') }}" class="btn-primary w-full text-lg py-4">
                        Jogar Agora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Comunidade -->
    <section id="comunidade" class="py-32">
        <div class="max-w-8xl mx-auto px-8">
            <div class="text-center mb-24">
                <h2 class="font-orbitron font-bold text-5xl lg:text-6xl mb-8">
                    <span class="text-neon-green">Comunidade</span> & Grupos
                </h2>
                <p class="text-2xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Conecta-te com outros jogadores. Estratégia, conversa e apostas em equipa.
                </p>
            </div>

            <div class="flex flex-col items-center justify-center min-h-[500px] text-center">
                <div class="content-card max-w-4xl mx-auto p-16">
                    <h3 class="font-orbitron font-bold text-4xl mb-8 text-white">Junta-te à Comunidade</h3>
                    <p class="text-gray-300 mb-12 text-xl leading-relaxed">Cria ou participa em grupos exclusivos. Partilha estratégias, acompanha as vitórias dos outros jogadores e forma equipas para apostas em conjunto. A nossa comunidade é o coração do TeamApostas.</p>
                    <div class="grid md:grid-cols-3 gap-8 mb-12">
                        <div class="flex flex-col items-center gap-4 text-white">
                            <i class="fas fa-users text-4xl text-neon-green"></i>
                            <span class="text-xl font-semibold">+5,000 jogadores ativos</span>
                        </div>
                        <div class="flex flex-col items-center gap-4 text-white">
                            <i class="fas fa-comments text-4xl text-neon-green"></i>
                            <span class="text-xl font-semibold">Chats em tempo real</span>
                        </div>
                        <div class="flex flex-col items-center gap-4 text-white">
                            <i class="fas fa-trophy text-4xl text-neon-green"></i>
                            <span class="text-xl font-semibold">Competições semanais</span>
                        </div>
                    </div>
                    <a href="{{ route('groups.index') }}" class="btn-primary text-xl px-12 py-5">Ver Grupos</a>
                </div>
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
                x: Math.random() * canvas.width * 0.8 + canvas.width * 0.2, // Concentrar mais à direita
                y: Math.random() * canvas.height * 0.6, // Concentrar na parte superior
                vx: (Math.random() - 0.5) * 0.3,
                vy: (Math.random() - 0.5) * 0.3,
                size: Math.random() * 1.5 + 0.5,
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
