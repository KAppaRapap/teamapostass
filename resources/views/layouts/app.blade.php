<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TeamApostas</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
<!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --secondary: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
            --accent: #f59e0b;
            --danger: #ef4444;
            --success: #10b981;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
        }
        
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark);
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary);
        }
        
        .sidebar {
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            min-height: 100vh;
            padding-top: 1.5rem;
        }
        .sidebar-header {
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #2563eb;
            margin-bottom: 2.1rem;
            text-align: center;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            color: #1e293b;
            text-decoration: none;
            font-size: 1.07rem;
            border-radius: 0.5rem 0 0 0.5rem;
            margin-bottom: 0.2rem;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            position: relative;
        }
        .sidebar-link i {
            font-size: 1.25rem;
            width: 28px;
            text-align: center;
            margin-right: 12px;
        }
        .sidebar-link.active, .sidebar-link:hover {
            background: #e8f0fe;
            color: #2563eb;
            font-weight: 600;
            box-shadow: 2px 0 0 0 #2563eb inset;
            text-decoration: none;
        }
        .sidebar-divider {
            border-top: 1px solid #e2e8f0;
            margin: 1.5rem 0;
        }
        @media (max-width: 991px) {
            .sidebar {
                min-height: auto;
                padding-top: 1rem;
            }
            .sidebar-header {
                font-size: 1rem;
                margin-bottom: 1rem;
            }
        }
        
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Correção para o dropdown de notificações */
        .dropdown-menu[aria-labelledby="navbarDropdown"] {
            max-width: 320px;
            min-width: 300px;
            padding: 0.5rem 0.5rem;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .dropdown-menu .dropdown-header,
        .dropdown-menu .dropdown-item {
            white-space: normal !important;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .dropdown-menu .dropdown-item .small {
            font-size: 0.95em;
            line-height: 1.3;
            white-space: normal !important;
        }
        
        .footer {
            background: #f8fafc; /* igual ao fundo do site */
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            box-shadow: none;
        }
        .footer-title {
            font-weight: 600;
            color: #222;
            font-size: 1.05rem;
        }
        .footer-link {
            color: #2563eb;
            text-decoration: none;
            transition: color .2s;
            font-size: 0.97rem;
        }
        .footer-link:hover {
            color: #0d47a1;
            text-decoration: underline;
        }
        .social-links a {
            color: #2563eb;
            transition: color .2s;
            font-size: 1.07rem;
        }
        .social-links a:hover {
            color: #0d47a1;
        }
        .footer .container {
            max-width: 1100px;
        }
        hr.my-3 {
            border-top: 1px solid #e2e8f0;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="/favicon.ico" alt="TeamApostas" height="38" style="vertical-align: middle;">
                <span class="ms-2 fw-bold" style="color: #2563eb; letter-spacing: 1px;">TeamApostas</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('games.*') ? 'active' : '' }}" href="{{ route('games.index') }}">Jogos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('groups.*') ? 'active' : '' }}" href="{{ route('groups.index') }}">Grupos</a>
                    </li>
                    <li class="nav-item dropdown">
                        @php
                            $unreadNotifications = Auth::user()->userNotifications()->where('is_read', false)->take(5)->get();
                            $unreadCount = $unreadNotifications->count();
                        @endphp
                        <a class="nav-link position-relative" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="width: 300px;">
                            <li><h6 class="dropdown-header">Notificações</h6></li>
                            @forelse($unreadNotifications as $notification)
                                <li>
                                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                @switch($notification->type)
                                                    @case('new_draw')
                                                        <i class="fas fa-calendar-alt text-primary"></i>
                                                        @break
                                                    @case('result')
                                                        <i class="fas fa-trophy text-success"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-bell text-secondary"></i>
                                                @endswitch
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $notification->title }}</div>
                                                <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item small text-muted">Sem notificações não lidas.</span></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                        </ul>
                    </li>
                    <!-- Avatar e dropdown -->
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('img/default-avatar.png') }}" class="rounded-circle me-1" width="32" height="32" alt="Avatar">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-user-cog me-2"></i>Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit"><i class="fas fa-sign-out-alt me-2"></i>Sair</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Registrar</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            @auth
            @if(request()->routeIs('settings.index'))
                {{-- Removido o botão centralizado do topo: agora só aparece o botão ao lado do título em settings/index.blade.php --}}
                <div class="col-12">
                    @yield('content')
                </div>
            @else
                <!-- Sidebar sempre visível -->
                <aside class="col-lg-2 col-md-3 sidebar p-0">
                    <div class="py-4">
                        <div class="sidebar-header">
                            <i class="fas fa-dice fa-lg me-2"></i>Menu
                        </div>
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="{{ route('groups.index') }}" class="sidebar-link {{ request()->routeIs('groups.index') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Meus Grupos
                        </a>
                        <a href="{{ route('betting-slips.index') }}" class="sidebar-link {{ request()->routeIs('betting-slips.*') ? 'active' : '' }}">
                            <i class="fas fa-gamepad"></i> Jogos Disponíveis
                        </a>
                        <a href="{{ route('results.index') }}" class="sidebar-link {{ request()->routeIs('results.*') ? 'active' : '' }}">
                            <i class="fas fa-trophy"></i> Resultados
                        </a>
                        <a href="{{ route('games.upcoming-draws') }}" class="sidebar-link {{ request()->routeIs('games.upcoming-draws') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i> Próximos Sorteios
                        </a>
                        <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                            <i class="fas fa-bell"></i> Notificações
                        </a>
                        <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i> Configurações
                        </a>
                        @if(Auth::user() && Auth::user()->is_admin)
                            <hr>
                            <a href="{{ route('games.index') }}" class="sidebar-link {{ request()->routeIs('games.index') ? 'active' : '' }}">
                                <i class="fas fa-gamepad"></i> Gerenciar Jogos
                            </a>
                            <a href="{{ route('games.create') }}" class="sidebar-link {{ request()->routeIs('games.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle"></i> Criar Jogo
                            </a>
                            <a href="{{ route('draws.create') }}" class="sidebar-link {{ request()->routeIs('draws.create') ? 'active' : '' }}">
                                <i class="fas fa-calendar-plus"></i> Criar Sorteio
                            </a>
                            <a href="{{ route('draws.index') }}" class="sidebar-link {{ request()->routeIs('draws.index') ? 'active' : '' }}">
                                <i class="fas fa-list"></i> Gerenciar Sorteios
                            </a>
                        @endif
                    </div>
                </aside>
                <div class="col-lg-10 col-md-9 py-4">
                    @yield('content')
                </div>
            @endif
            @endauth
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container py-3">
            <div class="row align-items-center gy-4">
                <div class="col-lg-4 col-md-6 text-lg-start text-center">
                    <h5 class="footer-title mb-2">Team<span class="text-primary">Apostas</span></h5>
                    <p class="text-muted small mb-3">A melhor plataforma para gerenciar grupos de apostadores em Portugal.</p>
                    <div class="d-flex justify-content-lg-start justify-content-center gap-3 social-links mb-2">
                        <a href="https://www.facebook.com/profile.php?id=61575137439233" target="_blank" title="Facebook"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 text-lg-start text-center">
                    <h6 class="footer-title mb-2">Links</h6>
                    <ul class="list-unstyled mb-0">
                        <li><a href="{{ url('/') }}" class="footer-link">Início</a></li>
                        <li><a href="{{ route('games.index') }}" class="footer-link">Jogos</a></li>
                        <li><a href="{{ route('groups.index') }}" class="footer-link">Grupos</a></li>
                        <li><a href="#" class="footer-link">Sobre Nós</a></li>
                        <li><a href="#" class="footer-link">Contato</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 text-lg-start text-center">
                    <h6 class="footer-title mb-2">Jogos</h6>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#" class="footer-link">Euromilhões</a></li>
                        <li><a href="#" class="footer-link">Totoloto</a></li>
                        <li><a href="#" class="footer-link">Totobola</a></li>
                        <li><a href="#" class="footer-link">Placard</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 text-lg-start text-center">
                    <h6 class="footer-title mb-2">Contato</h6>
                    <ul class="list-unstyled mb-0 footer-links">
                        <li class="mb-1"><a href="mailto:info@teamapostas.pt" class="footer-link"><i class="fas fa-envelope me-2"></i>info@teamapostas.pt</a></li>
                        <li class="mb-1"><a href="tel:+351912345678" class="footer-link"><i class="fas fa-phone me-2"></i>+351 912 345 678</a></li>
                        <li><a href="#" class="footer-link"><i class="fas fa-map-marker-alt me-2"></i>Lisboa, Portugal</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col text-center">
                    <small class="text-muted">&copy; {{ date('Y') }} TeamApostas. Todos os direitos reservados.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para atualizar notificações a cada 15 minutos -->
    <script>
        // Função para atualizar o dropdown de notificações
        function updateNotifications() {
            // Fazer uma requisição AJAX para buscar novas notificações
            fetch('/api/notifications/check-new')
                .then(response => response.json())
                .then(data => {
                    // Atualizar o contador de notificações
                    const badgeElement = document.querySelector('#navbarDropdown .badge');
                    
                    if (data.count > 0) {
                        // Se houver notificações não lidas, mostrar o badge
                        if (badgeElement) {
                            badgeElement.textContent = data.count;
                        } else {
                            // Criar o badge se não existir
                            const newBadge = document.createElement('span');
                            newBadge.className = 'badge bg-danger rounded-pill';
                            newBadge.textContent = data.count;
                            document.querySelector('#navbarDropdown').appendChild(newBadge);
                        }
                        
                        // Tocar um som de notificação (opcional)
                        // const notificationSound = new Audio('/sounds/notification.mp3');
                        // notificationSound.play();
                        
                        // Mostrar uma notificação de desktop (opcional, com permissão do usuário)
                        if (Notification.permission === 'granted' && data.latest) {
                            new Notification('TeamApostas', {
                                body: data.latest.title + '\n' + data.latest.message,
                                icon: '/favicon.ico'
                            });
                        }
                    } else if (badgeElement) {
                        // Se não houver notificações, remover o badge
                        badgeElement.remove();
                    }
                })
                .catch(error => console.error('Erro ao buscar notificações:', error));
        }
        
        // Solicitar permissão para notificações de desktop
        if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
        
        // Atualizar notificações a cada 15 minutos (900000 ms)
        setInterval(updateNotifications, 900000);
        
        // Também atualizar quando a página for carregada
        document.addEventListener('DOMContentLoaded', function() {
            // Esperar 10 segundos antes da primeira verificação para dar tempo do usuário ver as notificações atuais
            setTimeout(updateNotifications, 10000);
        });
    </script>
</body>
</html>
