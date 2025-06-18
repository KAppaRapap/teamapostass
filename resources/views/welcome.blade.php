<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TeamApostas - A tua plataforma para grupos de apostas online">
    <title>TeamApostas - Joga em Grupo, Ganha Mais!</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --neutral-bg: #F8F8F8; /* Fundo muito claro */
            --neutral-surface: #FFFFFF; /* Superfície de elementos */
            --neutral-text: #333333; /* Texto principal */
            --neutral-secondary-text: #777777; /* Texto secundário */
            --accent-green: #2ECC71; /* Verde esmeralda para destaque */
            --accent-green-dark: #27AE60;
            --border-light: #EBEBEB; /* Bordas e divisores */
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;
            background: var(--neutral-bg);
            color: var(--neutral-text);
            line-height: 1.7;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Source Sans Pro', sans-serif;
            font-weight: 700;
            color: var(--neutral-text);
        }

        .navbar {
            background-color: var(--neutral-surface);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-light);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--accent-green) !important;
            font-size: 1.85rem;
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: var(--neutral-text) !important;
            font-weight: 400;
            margin: 0 16px;
            transition: color 0.2s ease;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .nav-link:hover {
            color: var(--accent-green) !important;
        }

        .btn-minimalist {
            background-color: var(--accent-green);
            border: none;
            padding: 0.8rem 2.2rem;
            border-radius: 4px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .btn-minimalist:hover {
            background-color: var(--accent-green-dark);
            transform: translateY(-2px);
        }

        .btn-outline-minimalist {
            border: 1px solid var(--accent-green);
            color: var(--accent-green);
            padding: 0.8rem 2.2rem;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }

        .btn-outline-minimalist:hover {
            background-color: var(--accent-green);
            color: #fff;
            transform: translateY(-2px);
        }

        .hero {
            background-color: var(--neutral-bg);
            min-height: 80vh;
            display: flex;
            align-items: center;
            padding: 100px 0;
            position: relative;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.1;
            color: var(--neutral-text);
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--neutral-secondary-text);
            margin-bottom: 2.5rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4.5rem;
        }

        .section-title {
            font-size: 3.2rem;
            font-weight: 700;
            color: var(--neutral-text);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--neutral-secondary-text);
        }

        .card-minimalist {
            background: var(--neutral-surface);
            border-radius: 8px;
            padding: 2.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            transition: all 0.2s ease;
            height: 100%;
            border: 1px solid var(--border-light);
        }

        .card-minimalist:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .feature-icon-wrapper {
            width: 65px;
            height: 65px;
            background-color: var(--accent-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #fff;
            font-size: 2.2rem;
        }

        .game-card .card-img-top {
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border-bottom: 1px solid var(--border-light);
        }

        .game-card .card-body {
            padding: 1.2rem;
            text-align: center;
        }

        .cta-section {
            background-color: var(--accent-green);
            color: #fff;
            padding: 80px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #fff;
        }

        .cta-section p {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            opacity: 0.9;
        }

        .footer {
            background-color: var(--neutral-surface);
            color: var(--neutral-secondary-text);
            padding: 60px 0 30px;
            font-size: 0.9rem;
            border-top: 1px solid var(--border-light);
        }

        .footer a {
            color: var(--neutral-secondary-text);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer a:hover {
            color: var(--accent-green);
        }

        .footer h5 {
            color: var(--neutral-text);
            font-weight: 600;
            margin-bottom: 1.2rem;
            text-transform: uppercase;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: var(--border-light);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 8px;
            color: var(--neutral-text);
            font-size: 1.2rem;
            transition: all 0.2s ease;
        }

        .social-links a:hover {
            background: var(--accent-green);
            color: #fff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                TeamApostas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars" style="color: var(--neutral-text);"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#como-funciona">Como Funciona</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jogos">Jogos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contactos">Contactos</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item ms-lg-3">
                                <a href="{{ url('/dashboard') }}" class="btn btn-minimalist">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item ms-lg-3">
                                <a href="{{ route('login') }}" class="btn btn-outline-minimalist me-2">Entrar</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-minimalist">Registar</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <h1>Joga em Grupo,<br>Ganha Mais!</h1>
                    <p class="mb-0">A plataforma mais fácil e segura para criar, gerir e apostar em grupo nos principais jogos nacionais.</p>
                    <a href="{{ route('register') }}" class="btn btn-minimalist btn-lg mt-3">Começar Agora</a>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="{{ asset('img/hero-illustration.png') }}" alt="TeamApostas Illustration" class="img-fluid w-75">
                </div>
            </div>
        </div>
    </section>

    <!-- Como Funciona Section -->
    <section id="como-funciona" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Como Funciona</h2>
                <p class="section-subtitle">Três passos simples para começar a apostar em grupo</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-minimalist text-center">
                        <div class="feature-icon-wrapper mx-auto mb-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="fw-bold mb-3">1. Cria ou Entra num Grupo</h4>
                        <p>Junta-te a outros apostadores ou cria o teu próprio grupo para aumentar as tuas hipóteses.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-minimalist text-center">
                        <div class="feature-icon-wrapper mx-auto mb-4">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">2. Faz as tuas Apostas</h4>
                        <p>Escolhe o jogo, define a aposta e acompanha tudo de forma transparente.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-minimalist text-center">
                        <div class="feature-icon-wrapper mx-auto mb-4">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h4 class="fw-bold mb-3">3. Partilha os Ganhos</h4>
                        <p>Se o grupo ganhar, os prémios são distribuídos automaticamente e sem complicações!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jogos em Destaque Section -->
    <section id="jogos" class="py-5" style="background-color: var(--neutral-bg);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Jogos Disponíveis</h2>
                <p class="section-subtitle">Escolhe entre os melhores jogos nacionais</p>
            </div>
            <div class="row g-4">
                @foreach($games as $game)
                    <div class="col-md-3">
                        <div class="card-minimalist game-card">
                            @if($game->image_url)
                                <img src="{{ $game->image_url }}" alt="{{ $game->name }}" class="card-img-top">
                            @else
                                <img src="https://via.placeholder.com/400x180/EBEBEB/777777?text={{ urlencode($game->name) }}" alt="{{ $game->name }}" class="card-img-top">
                            @endif
                            <div class="card-body">
                                <h5 class="fw-bold mb-2">{{ $game->name }}</h5>
                                <p class="small mb-3">{{ $game->description ?? 'Jogo disponível para apostas.' }}</p>
                                <a href="{{ route('groups.index', ['game_id' => $game->id]) }}" class="btn btn-minimalist btn-sm mt-auto">Ver Grupos</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    @guest
    <section class="cta-section py-5">
        <div class="container">
            <h2 class="fw-bold mb-4">Pronto para jogar em grupo?</h2>
            <p class="lead mb-5">Regista-te gratuitamente e começa já a apostar com amigos!</p>
            <div>
                <a href="{{ route('register') }}" class="btn btn-minimalist btn-lg me-3">Criar Conta</a>
                <a href="{{ route('login') }}" class="btn btn-outline-minimalist btn-lg">Entrar</a>
            </div>
        </div>
    </section>
    @endguest

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">TeamApostas</h5>
                    <p>A melhor plataforma para apostas em grupo em Portugal. Joga de forma inteligente e partilha os teus ganhos!</p>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Navegação</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#hero">Início</a></li>
                        <li class="mb-2"><a href="#como-funciona">Como Funciona</a></li>
                        <li class="mb-2"><a href="#jogos">Jogos</a></li>
                        <li><a href="#contactos">Contactos</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Termos de Serviço</a></li>
                        <li class="mb-2"><a href="#">Política de Privacidade</a></li>
                        <li><a href="#">Política de Cookies</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Siga-nos</h5>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61575137439233" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(0,0,0,0.05);">
            <div class="text-center small" style="color: var(--neutral-secondary-text);">
                &copy; {{ date('Y') }} TeamApostas. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
