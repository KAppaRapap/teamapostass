<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TeamApostas') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/light-theme.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="{{ url('/') }}" class="app-name-link">
                    <img src="{{ asset('img/logo1111.png') }}" alt="Logo" class="logo d-inline-block align-text-top" style="height: 70px; width: auto;">
                </a>
            </div>
            <h6 class="sidebar-menu-title">Menu</h6>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('groups.index') ? 'active' : '' }}" href="{{ route('groups.index') }}">
                        <i class="fas fa-users"></i> Meus Grupos
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('games.index') ? 'active' : '' }}" href="{{ route('games.index') }}">
                        <i class="fas fa-gamepad"></i> Jogos Disponíveis
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('results.index') ? 'active' : '' }}" href="{{ route('results.index') }}">
                        <i class="fas fa-trophy"></i> Resultados
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('draws.upcoming') ? 'active' : '' }}" href="#">
                        <i class="fas fa-calendar-alt"></i> Próximos Sorteios
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell"></i> Notificações
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="fas fa-cog"></i> Configurações
                    </a>
                </li>
                @if(Auth::user()->is_admin)
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users-cog"></i> Gerenciar Usuários (Admin)
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('draws.create') ? 'active' : '' }}" href="{{ route('draws.create') }}">
                        <i class="fas fa-plus-circle"></i> Criar Sorteio (Admin)
                    </a>
                </li>
                @endif
            </ul>
            <div class="sidebar-footer-profile">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                <div>
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="email">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>

        <div class="main-content-wrapper">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand-custom d-md-none" href="{{ url('/') }}">Menu</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('games.index') }}">Jogos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('groups.index') }}">Grupos</a>
                            </li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto align-items-center">
                            <!-- Notification Icon -->
                            <li class="nav-item me-3 notification-icon position-relative">
                                <a class="nav-link" href="{{ route('notifications.index') }}">
                                    <i class="fas fa-bell"></i>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            <!-- User Dropdown -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('settings.index') }}">
                                        <i class="fas fa-user-cog me-2"></i> Perfil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>

            <footer class="footer mt-auto py-3">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="footer-title">{{ config('app.name') }}</h5>
                            <p class="text-secondary-text-color">Sua plataforma de apostas online.</p>
                        </div>
                        <div class="col-md-4">
                            <h5 class="footer-title">Links Úteis</h5>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a></li>
                                <li><a href="{{ route('games.index') }}" class="footer-link">Jogos</a></li>
                                <li><a href="{{ route('groups.index') }}" class="footer-link">Grupos</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h5 class="footer-title">Siga-nos</h5>
                            <div class="social-links">
                                <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <p class="text-center text-secondary-text-color mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
                </div>
            </footer>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add active class to sidebar links based on current URL
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            sidebarLinks.forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (currentPath === linkPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
