<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TeamApostas - A tua plataforma para grupos de apostas online">
    <title>TeamApostas - Joga em Grupo, Ganha Mais!</title>
    <link rel="icon" type="icon" href="favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }
        /* Top-right auth buttons */
        .auth-top-right {
            position: fixed;
            top: 24px;
            right: 40px;
            z-index: 1050;
        }
        .auth-top-right .btn {
            min-width: 90px;
            font-weight: 500;
            border-radius: 20px;
        }
        .btn-highlight {
            background: linear-gradient(90deg, #2563eb 0%, #10b981 100%);
            color: #fff !important;
            border: none;
            box-shadow: 0 2px 8px rgba(37,99,235,0.08);
            transition: background 0.2s;
        }
        .btn-highlight:hover, .btn-highlight:focus {
            background: linear-gradient(90deg, #1d4ed8 0%, #059669 100%);
            color: #fff !important;
        }
        .hero {
            background: linear-gradient(120deg, #2563eb 0%, #10b981 100%);
            color: #fff;
            padding: 160px 0 80px 0; /* mais espaço acima */
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
        }
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }
        .hero .btn-primary {
            font-size: 1.1rem;
            padding: 0.8rem 2.5rem;
            border-radius: 30px;
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #2563eb;
            margin-bottom: 1rem;
        }
        .step-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(37,99,235,0.07);
            padding: 2rem 1.5rem;
            text-align: center;
            transition: transform 0.2s;
        }
        .step-card:hover {
            transform: translateY(-5px) scale(1.03);
        }
        .game-logos img {
            height: 60px;
            margin: 0 15px 15px 0;
            filter: grayscale(0.2);
            opacity: 0.85;
        }
        .cta-section {
            background: #2563eb;
            color: #fff;
            padding: 60px 0;
            text-align: center;
        }
        .footer {
            background: #fff;
            color: #64748b;
            padding: 30px 0 10px 0;
            border-top: 1px solid #e2e8f0;
        }
        .social-links a {
            color: #2563eb;
            margin: 0 10px;
            font-size: 1.25rem;
            transition: color 0.2s;
        }
        .social-links a:hover { color: #10b981; }
    </style>
</head>
<body>
    <!-- Auth Buttons Top Right (fixed) -->
    <div class="auth-top-right">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-highlight me-2">Entrar</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Registar</a>
                @endif
            @endauth
        @endif
    </div>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Joga em Grupo, Ganha Mais!</h1>
            <p>A plataforma mais fácil e segura para criar, gerir e apostar em grupo nos principais jogos nacionais.</p>
            <a href="{{ route('register') }}" class="btn btn-primary shadow">Começar Agora</a>
        </div>
    </section>

    <!-- Como Funciona -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="feature-icon mb-2"><i class="fas fa-users"></i></div>
                        <h5 class="fw-bold mb-2">1. Cria ou Entra num Grupo</h5>
                        <p>Junta-te a outros apostadores ou cria o teu próprio grupo para aumentar as tuas hipóteses.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="feature-icon mb-2"><i class="fas fa-ticket-alt"></i></div>
                        <h5 class="fw-bold mb-2">2. Faz as tuas Apostas</h5>
                        <p>Escolhe o jogo, define a aposta e acompanha tudo de forma transparente.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="feature-icon mb-2"><i class="fas fa-trophy"></i></div>
                        <h5 class="fw-bold mb-2">3. Partilha os Ganhos</h5>
                        <p>Se o grupo ganhar, os prémios são distribuídos automaticamente e sem complicações!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefícios -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="feature-icon"><i class="fas fa-lock"></i></div>
                        <h6 class="fw-bold">Segurança</h6>
                        <p class="mb-0">Os teus dados e prémios estão sempre protegidos.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                        <h6 class="fw-bold">Simplicidade</h6>
                        <p class="mb-0">Interface intuitiva, fácil de usar em qualquer dispositivo.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="feature-icon"><i class="fas fa-sync-alt"></i></div>
                        <h6 class="fw-bold">Resultados em Tempo Real</h6>
                        <p class="mb-0">Acompanha apostas e prémios assim que são atualizados.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="feature-icon"><i class="fas fa-users-cog"></i></div>
                        <h6 class="fw-bold">Comunidade</h6>
                        <p class="mb-0">Partilha estratégias e celebra vitórias com outros apostadores.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jogos em Destaque -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h4 class="fw-bold mb-4">Jogos Disponíveis</h4>
            <div class="row justify-content-center g-4">
                @foreach($games as $game)
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card h-100 shadow-sm border-0">
                            @if($game->image_url)
                                <img src="{{ $game->image_url }}" alt="{{ $game->name }}" class="card-img-top p-3" style="height:90px;object-fit:contain;">
                            @else
                                <img src="https://via.placeholder.com/120x90?text=Jogo" alt="{{ $game->name }}" class="card-img-top p-3" style="height:90px;object-fit:contain;">
                            @endif
                            <div class="card-body">
                                <h6 class="fw-bold">{{ $game->name }}</h6>
                                <p class="small text-muted">{{ $game->description ?? 'Jogo disponível para apostas.' }}</p>
                                <a href="{{ route('groups.index', ['game_id' => $game->id]) }}" class="btn btn-primary btn-sm">Ver Grupos</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    @guest
    <section class="cta-section">
        <div class="container">
            <h2 class="fw-bold mb-4">Pronto para jogar em grupo?</h2>
            <p class="mb-4">Regista-te gratuitamente e começa já a apostar com amigos!</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">Criar Conta</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg ms-2">Entrar</a>
        </div>
    </section>
    @endguest

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <div class="mb-3">
                <a href="#" class="me-3">Início</a>
                <a href="#" class="me-3">Jogos</a>
                <a href="#" class="me-3">Como Funciona</a>
                <a href="#">Contactos</a>
            </div>
            <div class="social-links mb-2">
                <a href="https://www.facebook.com/profile.php?id=61575137439233"><i class="fab fa-facebook-f"></i></a>
                <a href="#isto"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="copyright">
                <small>&copy; {{ date('Y') }} TeamApostas. Todos os direitos reservados.</small>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
