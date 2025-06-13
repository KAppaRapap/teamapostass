<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TeamApostas - Apostas Desportivas em Grupo</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&family=Montserrat:wght@700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            --primary-color: #10B981;
            /* Green */
            --primary-dark: #059669;
            --secondary-color: #3B82F6;
            /* Blue */
            --dark-bg: #111827;
            --dark-card: #1F2937;
            --light-text: #F9FAFB;
            --muted-text: #9CA3AF;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--dark-bg);
            color: var(--light-text);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* --- Header --- */
        .main-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            padding: 1.5rem 0;
        }

        .main-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            color: var(--light-text);
            text-decoration: none;
            font-weight: 700;
        }
        .logo i {
            color: var(--primary-color);
        }

        .auth-buttons .btn {
            text-decoration: none;
            color: var(--light-text);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .auth-buttons .btn-login {
            margin-right: 0.5rem;
        }
        .auth-buttons .btn-login:hover {
            background: rgba(255,255,255,0.1);
        }

        .auth-buttons .btn-register {
            background-color: var(--primary-color);
        }
        .auth-buttons .btn-register:hover {
            background-color: var(--primary-dark);
        }

        /* --- Hero Section --- */
        .hero {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            text-align: center;
            background: url('https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=1935&auto=format&fit=crop') no-repeat center center/cover;
            overflow: hidden;
        }
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            animation: fadeIn 1s ease-in-out;
        }

        .hero h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        }
        .hero h1 span {
            color: var(--primary-color);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            font-weight: 400;
        }
        .hero .cta-button {
            background-color: var(--primary-color);
            color: var(--light-text);
            padding: 1rem 3rem;
            font-size: 1.2rem;
            font-weight: 700;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .hero .cta-button:hover {
            background-color: var(--primary-dark);
            transform: scale(1.05);
        }

        /* --- Sections --- */
        .section {
            padding: 6rem 0;
        }
        .section-title {
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 2.8rem;
            margin-bottom: 3rem;
        }
        .section-title span {
            color: var(--primary-color);
        }

        /* --- Games Section --- */
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .game-card {
            background: var(--dark-card);
            border-radius: 12px;
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .game-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            border-color: var(--primary-color);
        }

        .game-card-image {
            height: 150px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 1rem;
        }
        .game-card-image img {
            max-height: 80px;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));
        }

        .game-card-content {
            padding: 1.5rem;
        }

        .game-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .game-card p {
            color: var(--muted-text);
            margin-bottom: 1.5rem;
        }
        .game-card .btn-view {
            background: var(--secondary-color);
            color: var(--light-text);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
        }

        /* --- Ranking Section --- */
        .ranking-table {
            background: var(--dark-card);
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
            border-collapse: collapse;
        }

        .ranking-table th, .ranking-table td {
            padding: 1.2rem 1.5rem;
            text-align: left;
        }

        .ranking-table thead {
            background-color: rgba(31, 41, 55, 0.5);
            font-size: 0.9rem;
            text-transform: uppercase;
            color: var(--muted-text);
        }
        .ranking-table tbody tr {
            border-bottom: 1px solid #374151;
            transition: background-color 0.2s;
        }
        .ranking-table tbody tr:last-child {
            border-bottom: none;
        }
        .ranking-table tbody tr:hover {
            background-color: #374151;
        }
        .ranking-table .rank {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        .ranking-table .user-info {
            display: flex;
            align-items: center;
        }
        .ranking-table .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 1rem;
        }

        /* --- CTA Section --- */
        .cta-section {
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            text-align: center;
            padding: 5rem 0;
        }
        .cta-section h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .cta-section .cta-button {
             background-color: var(--light-text);
             color: var(--dark-bg);
             padding: 1rem 3rem;
             font-size: 1.2rem;
             font-weight: 700;
             border-radius: 50px;
             text-decoration: none;
             transition: all 0.3s ease;
        }
        .cta-section .cta-button:hover {
            transform: scale(1.05);
            background: #e2e8f0;
        }


        /* --- Footer --- */
        .main-footer {
            background: #000;
            padding: 3rem 0;
            text-align: center;
            color: var(--muted-text);
        }
        .footer-socials a {
            color: var(--muted-text);
            margin: 0 0.8rem;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }
        .footer-socials a:hover {
            color: var(--primary-color);
        }
        .footer-links {
            margin: 1.5rem 0;
        }
        .footer-links a {
            color: var(--muted-text);
            text-decoration: none;
            margin: 0 1rem;
        }
        .footer-links a:hover {
            color: var(--light-text);
        }


        /* --- Animations & Responsive --- */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 3rem; }
            .hero p { font-size: 1.1rem; }
            .section-title { font-size: 2.2rem; }
            .main-header { padding: 1rem 0; background: rgba(17, 24, 39, 0.8); backdrop-filter: blur(5px); }
            .auth-buttons .btn-login {
                display: none; /* Hide login text on mobile, keep icon or similar */
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <a href="/" class="logo"><i class="fas fa-trophy"></i> TeamApostas</a>
            <div class="auth-buttons">
                 @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-register">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">Entrar</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-register">Registar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>A Emoção do Jogo, <span>Potenciada</span> em Equipa</h1>
            <p>Cria ou junta-te a grupos de apostas para os teus jogos favoritos. Mais estratégia, mais diversão, mais vitórias.</p>
            <a href="{{ route('register') }}" class="cta-button">Começar a Apostar</a>
        </div>
    </section>

    <!-- Featured Games Section -->
    <section id="games" class="section">
        <div class="container">
            <h2 class="section-title">Jogos <span>Disponíveis</span></h2>
            <div class="games-grid">
                @forelse($games as $game)
                    <div class="game-card" onclick="window.location.href='{{ route('groups.index', ['game_id' => $game->id]) }}'">
                        <div class="game-card-image" style="background-color: {{ $game->color ?? '#2a3b50' }};">
                             @if($game->image_url)
                                <img src="{{ $game->image_url }}" alt="{{ $game->name }}">
                            @else
                                <!-- Placeholder icon if no image -->
                               <i class="fas fa-futbol" style="font-size: 50px; color: rgba(255,255,255,0.5)"></i>
                            @endif
                        </div>
                        <div class="game-card-content">
                            <h3>{{ $game->name }}</h3>
                            <p>{{ $game->description ?? 'Apostas abertas para este evento.' }}</p>
                            <span class="btn-view">Ver Grupos</span>
                        </div>
                    </div>
                @empty
                    <p>De momento, não existem jogos disponíveis. Volte mais tarde.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Ranking Section -->
    <section id="ranking" class="section" style="background: #1F2937;">
        <div class="container">
            <h2 class="section-title">Top <span>Apostadores</span></h2>
            <div class="ranking-table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Utilizador</th>
                            <th>Grupos</th>
                            <th>Vitórias</th>
                            <th>Ganhos Totais</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Placeholder Data -->
                        <tr>
                            <td class="rank">1</td>
                            <td class="user-info">
                                <img src="https://i.pravatar.cc/40?u=user1" alt="Avatar" class="user-avatar">
                                <span>MestreDasApostas</span>
                            </td>
                            <td>12</td>
                            <td>8</td>
                            <td>€ 1,250.75</td>
                        </tr>
                         <tr>
                            <td class="rank">2</td>
                            <td class="user-info">
                                <img src="https://i.pravatar.cc/40?u=user2" alt="Avatar" class="user-avatar">
                                <span>ReiDoPalpite</span>
                            </td>
                            <td>15</td>
                            <td>7</td>
                            <td>€ 980.50</td>
                        </tr>
                         <tr>
                            <td class="rank">3</td>
                            <td class="user-info">
                                <img src="https://i.pravatar.cc/40?u=user3" alt="Avatar" class="user-avatar">
                                <span>SorteDeCampeao</span>
                            </td>
                            <td>8</td>
                            <td>6</td>
                            <td>€ 810.00</td>
                        </tr>
                        <tr>
                            <td class="rank">4</td>
                            <td class="user-info">
                                <img src="https://i.pravatar.cc/40?u=user4" alt="Avatar" class="user-avatar">
                                <span>ApostaCerta</span>
                            </td>
                            <td>10</td>
                            <td>5</td>
                            <td>€ 720.20</td>
                        </tr>
                         <tr>
                            <td class="rank">5</td>
                            <td class="user-info">
                                <img src="https://i.pravatar.cc/40?u=user5" alt="Avatar" class="user-avatar">
                                <span>GénioDaBola</span>
                            </td>
                            <td>7</td>
                            <td>5</td>
                            <td>€ 650.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    @guest
    <section class="cta-section">
        <div class="container">
            <h2>Pronto para entrar em campo?</h2>
            <p>Regista-te agora, é grátis e demora menos de um minuto!</p>
            <a href="{{ route('register') }}" class="cta-button">Criar a Minha Conta</a>
        </div>
    </section>
    @endguest


    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-socials">
                <a href="https://www.facebook.com/profile.php?id=61575137439233" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="footer-links">
                 <a href="#games">Jogos</a>
                 <a href="#ranking">Ranking</a>
                 <a href="#">Termos e Condições</a>
                 <a href="#">Contactos</a>
            </div>
            <p>&copy; {{ date('Y') }} TeamApostas. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>
