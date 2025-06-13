<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TeamApostas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #475569;
            --success-color: #16a34a;
            --danger-color: #dc2626;
            --warning-color: #d97706;
            --info-color: #0891b2;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark-color);
        }

        .navbar {
            background: white;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
        }

        .nav-link {
            font-weight: 500;
            color: var(--secondary-color);
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            border-color: #e2e8f0;
            padding: 0.5rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgb(37 99 235 / 0.25);
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .alert-success {
            background-color: #dcfce7;
            color: var(--success-color);
        }

        .alert-danger {
            background-color: #fee2e2;
            color: var(--danger-color);
        }

        .alert-warning {
            background-color: #fef3c7;
            color: var(--warning-color);
        }

        .alert-info {
            background-color: #e0f2fe;
            color: var(--info-color);
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 0.375rem;
        }

        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border: none;
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 0.375rem;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            color: white;
        }

        .dropdown-menu {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            color: var(--secondary-color);
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        .modal-content {
            border: none;
            border-radius: 1rem;
        }

        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        .toast {
            background-color: white;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .toast-header {
            border-bottom: 1px solid #e2e8f0;
        }

        .progress {
            height: 0.5rem;
            border-radius: 1rem;
            background-color: #e2e8f0;
        }

        .progress-bar {
            background-color: var(--primary-color);
        }

        .tooltip {
            font-size: 0.875rem;
        }

        .tooltip-inner {
            background-color: var(--dark-color);
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }

        .popover {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .popover-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .popover-body {
            color: var(--dark-color);
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        .slide-in {
            animation: slideIn 0.3s ease-in-out;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .table-responsive {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'TeamApostas') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-home me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('groups.*') ? 'active' : '' }}" href="{{ route('groups.index') }}">
                                    <i class="fas fa-users me-1"></i> Grupos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('games.*') ? 'active' : '' }}" href="{{ route('games.index') }}">
                                    <i class="fas fa-gamepad me-1"></i> Jogos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('wallet.*') ? 'active' : '' }}" href="{{ route('wallet.index') }}">
                                    <i class="fas fa-wallet me-1"></i> Carteira
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i> Login
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i> Registrar
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-bell me-1"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-info-circle text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="mb-0">{{ $notification->data['message'] }}</p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="dropdown-item text-center text-muted">
                                            Nenhuma notificação
                                        </div>
                                    @endforelse
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-cog me-1"></i> Perfil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('settings.index') }}">
                                        <i class="fas fa-cog me-1"></i> Configurações
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-1"></i> Sair
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="bg-white py-4 mt-auto">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0 text-muted">
                            &copy; {{ date('Y') }} {{ config('app.name', 'TeamApostas') }}. Todos os direitos reservados.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <a href="#" class="text-muted text-decoration-none me-3">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-muted text-decoration-none me-3">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-muted text-decoration-none">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
