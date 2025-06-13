<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1976d2;
            --secondary-color: #f4f8fb;
            --text-color: #2c3e50;
            --light-text: #6c757d;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
            background-color: var(--secondary-color);
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color);
        }

        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2196f3 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 60px;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            height: 100%;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--secondary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
        }

        .btn-primary:hover, .btn-outline-primary:hover {
            background-color: #1565c0;
            border-color: #1565c0;
        }

        .footer {
            background: white;
            padding: 3rem 0;
            margin-top: 60px;
        }

        .footer-link {
            color: var(--light-text);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--primary-color);
        }

        .social-icon {
            width: 40px;
            height: 40px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registrar</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Apostas em Grupo</h1>
                    <p class="lead">Crie grupos, faça apostas e compartilhe a emoção com seus amigos. Uma plataforma completa para gerenciar suas apostas em grupo.</p>
                    @guest
                    <div class="d-flex gap-3">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            Começar Agora
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            Entrar
                        </a>
                    </div>
                    @else
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">
                        Ir para Dashboard
                    </a>
                    @endguest
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="{{ asset('img/hero-illustration.svg') }}" alt="Hero Illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Grupos</h3>
                    <p class="text-muted">Crie e participe de grupos com seus amigos para fazer apostas juntos.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3>Apostas</h3>
                    <p class="text-muted">Faça apostas em diferentes jogos e acompanhe seus resultados em tempo real.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Prêmios</h3>
                    <p class="text-muted">Ganhe prêmios e compartilhe suas vitórias com o grupo.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="mb-3">{{ config('app.name', 'Laravel') }}</h5>
                    <p class="text-muted">Uma plataforma completa para gerenciar suas apostas em grupo.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 class="mb-3">Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="footer-link">Sobre</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="footer-link">Termos</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="footer-link">Privacidade</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h6 class="mb-3">Suporte</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="footer-link">FAQ</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="footer-link">Contato</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="footer-link">Ajuda</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h6 class="mb-3">Newsletter</h6>
                    <p class="text-muted">Receba novidades e atualizações.</p>
                    <form class="d-flex gap-2">
                        <input type="email" class="form-control" placeholder="Seu e-mail">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-muted mb-0">Desenvolvido com <i class="fas fa-heart text-danger"></i> por <a href="#" class="footer-link">Seu Nome</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
