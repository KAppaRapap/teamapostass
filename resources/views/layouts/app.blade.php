<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'TeamApostas')) - O Casino da Nova Geração</title>
    <meta name="description" content="@yield('description', 'O casino da nova geração. Aposta, vence e partilha com a tua equipa.')">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <script>
        tailwind = {};
    </script>
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #121212;
            color: #ffffff;
        }
        
        /* Header Fixo */
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
            background: rgba(18, 18, 18, 0.95);
            border-bottom: 1px solid rgba(0, 255, 178, 0.2);
        }
        
        /* Hero Section */
        .hero-bg {
            background: radial-gradient(ellipse at center, rgba(0, 255, 178, 0.1) 0%, rgba(255, 0, 92, 0.05) 50%, #121212 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .particles-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        /* Cards de Jogos */
        .game-card {
            background: linear-gradient(135deg, #1E1E1E 0%, #2A2A2A 100%);
            border: 1px solid rgba(0, 255, 178, 0.2);
            border-radius: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .game-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 178, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .game-card:hover::before {
            left: 100%;
        }
        
        .game-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: #00FFB2;
            box-shadow: 0 20px 40px rgba(0, 255, 178, 0.2);
        }
        
        /* Botões */
        .btn-primary {
            background: linear-gradient(135deg, #00FFB2 0%, #00CC8F 100%);
            color: #121212;
            font-weight: 700;
            padding: 12px 32px;
            border-radius: 50px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 255, 178, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #FF005C 0%, #CC0047 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 12px 32px;
            border-radius: 50px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 0, 92, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            color: #00FFB2;
            font-weight: 700;
            padding: 12px 32px;
            border-radius: 50px;
            border: 2px solid #00FFB2;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-outline:hover {
            background: #00FFB2;
            color: #121212;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 255, 178, 0.4);
        }
        
        /* Cards Gerais */
        .content-card {
            background: linear-gradient(135deg, #1E1E1E 0%, #2A2A2A 100%);
            border: 1px solid rgba(0, 255, 178, 0.2);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
        }
        
        .content-card:hover {
            border-color: #00FFB2;
            box-shadow: 0 10px 30px rgba(0, 255, 178, 0.1);
        }
        
        /* Ranking */
        .ranking-card {
            background: linear-gradient(135deg, #1E1E1E 0%, #2A2A2A 100%);
            border: 1px solid rgba(0, 255, 178, 0.2);
            border-radius: 16px;
            padding: 24px;
        }
        
        .progress-bar {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
        }
        
        .progress-fill {
            background: linear-gradient(90deg, #00FFB2, #00CC8F);
            height: 100%;
            border-radius: 10px;
            transition: width 1s ease;
        }
        
        /* Chat Mockup */
        .chat-mockup {
            background: linear-gradient(135deg, #1E1E1E 0%, #2A2A2A 100%);
            border: 1px solid rgba(0, 255, 178, 0.2);
            border-radius: 16px;
            padding: 20px;
        }
        
        .chat-bubble {
            background: rgba(0, 255, 178, 0.1);
            border: 1px solid rgba(0, 255, 178, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 12px;
        }
        
        /* Formulários */
        .form-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 178, 0.2);
            border-radius: 8px;
            padding: 12px 16px;
            color: #ffffff;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #00FFB2;
            box-shadow: 0 0 0 3px rgba(0, 255, 178, 0.1);
        }
        
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Tabelas */
        .table-modern {
            background: linear-gradient(135deg, #1E1E1E 0%, #2A2A2A 100%);
            border: 1px solid rgba(0, 255, 178, 0.2);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table-modern th {
            background: rgba(0, 255, 178, 0.1);
            color: #00FFB2;
            font-weight: 600;
            padding: 16px;
            text-align: left;
        }
        
        .table-modern td {
            padding: 16px;
            border-bottom: 1px solid rgba(0, 255, 178, 0.1);
        }
        
        .table-modern tr:hover {
            background: rgba(0, 255, 178, 0.05);
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem !important;
            }
            
            .hero-subtitle {
                font-size: 1.1rem !important;
            }
        }
        
        /* Animações */
        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Light Mode Styles */
        .light body {
            background-color: #f8fafc;
            color: #1e293b;
        }
        
        .light .header-fixed {
            background: rgba(248, 250, 252, 0.95);
            border-bottom: 1px solid rgba(0, 255, 178, 0.3);
        }
        
        .light .content-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border: 1px solid rgba(0, 255, 178, 0.3);
            color: #1e293b;
        }
        
        .light .form-input {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 255, 178, 0.3);
            color: #1e293b;
        }
        
        .light .form-input::placeholder {
            color: rgba(30, 41, 59, 0.5);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-dark-bg text-white">
    <!-- Header Fixo -->
    <header class="header-fixed py-4 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('img/logo1111.png') }}" alt="TeamApostas" class="h-12 w-auto transition-transform group-hover:scale-110">
                <span class="text-2xl font-orbitron font-bold text-white tracking-wider">TeamApostas</span>
            </a>
            
            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="/" class="text-white hover:text-neon-green transition-colors duration-300 font-medium {{ request()->is('/') ? 'text-neon-green' : '' }}">Início</a>
                <a href="{{ route('games.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 font-medium {{ request()->routeIs('games.*') ? 'text-neon-green' : '' }}">Jogos</a>
                <a href="{{ route('groups.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 font-medium {{ request()->routeIs('groups.*') ? 'text-neon-green' : '' }}">Comunidade</a>
                <a href="{{ route('wallet.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 font-medium {{ request()->routeIs('wallet.*') ? 'text-neon-green' : '' }}">Carteira</a>
                
                <!-- Auth Buttons -->
                @auth
                    <div class="flex items-center gap-4">
                        <a href="{{ url('/dashboard') }}" class="btn-primary">Painel</a>
                        <div style="background: #00ff99; color: #222; font-weight: bold; font-size: 1.1rem; padding: 8px 20px; border-radius: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-wallet"></i>
                            Saldo na Carteira: <span style="margin-left: 4px;">€{{ number_format(auth()->user()->virtual_balance, 2) }}</span>
                        </div>
                        <div class="relative">
                            <button id="user-menu-btn" class="flex items-center gap-2 text-white hover:text-neon-green transition-colors duration-300 focus:outline-none">
                                <img src="{{ auth()->user()->profile_photo_url ?? asset('img/default-avatar.png') }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="w-8 h-8 rounded-full">
                                <span class="hidden lg:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="user-menu-dropdown" class="absolute right-0 mt-2 w-48 bg-gray-900 border border-dark-border rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 z-50">
                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-white hover:bg-neon-green hover:text-dark-bg transition-colors">
                                        <i class="fas fa-user mr-2"></i>Perfil
                                    </a>
                                    <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-white hover:bg-neon-green hover:text-dark-bg transition-colors">
                                        <i class="fas fa-bell mr-2"></i>Notificações
                                    </a>
                                    <hr class="border-dark-border my-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-white hover:bg-red-600 transition-colors">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-white hover:text-neon-green transition-colors duration-300 font-medium">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Registo</a>
                @endif
                    </div>
                @endauth
            </nav>
            
            <!-- Menu Mobile -->
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg bg-dark-card border border-dark-border">
                <i class="fas fa-bars text-white"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-t border-dark-border">
            <nav class="flex flex-col gap-4 pt-4">
                <a href="/" class="text-white hover:text-neon-green transition-colors duration-300 {{ request()->is('/') ? 'text-neon-green' : '' }}">Início</a>
                <a href="{{ route('games.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 {{ request()->routeIs('games.*') ? 'text-neon-green' : '' }}">Jogos</a>
                <a href="{{ route('groups.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 {{ request()->routeIs('groups.*') ? 'text-neon-green' : '' }}">Comunidade</a>
                <a href="{{ route('wallet.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 {{ request()->routeIs('wallet.*') ? 'text-neon-green' : '' }}">Carteira</a>
                
                @auth
                    <hr class="border-dark-border">
                    <a href="{{ url('/dashboard') }}" class="btn-primary text-center">Painel</a>
                    <a href="{{ route('profile.edit') }}" class="text-white hover:text-neon-green transition-colors duration-300">
                        <i class="fas fa-user mr-2"></i>Perfil
                    </a>
                    <a href="{{ route('notifications.index') }}" class="text-white hover:text-neon-green transition-colors duration-300">
                        <i class="fas fa-bell mr-2"></i>Notificações
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-white hover:text-red-400 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Sair
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-neon-green transition-colors duration-300">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary text-center">Registo</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="pt-20">
                    @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark-card border-t border-dark-border py-12 px-6 mt-20">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('img/logo1111.png') }}" alt="TeamApostas" class="h-8 w-auto">
                        <span class="font-orbitron font-bold text-xl">TeamApostas</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        O casino da nova geração. Aposta, vence e partilha com a tua equipa.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-white mb-4">Jogos</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('games.dice') }}" class="hover:text-neon-green transition-colors">Dice</a></li>
                        <li><a href="{{ route('games.bombmine') }}" class="hover:text-neon-green transition-colors">Bomb Mine</a></li>
                        <li><a href="{{ route('games.crash') }}" class="hover:text-neon-green transition-colors">Crash</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-white mb-4">Comunidade</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('groups.index') }}" class="hover:text-neon-green transition-colors">Grupos</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-neon-green transition-colors">Dashboard</a></li>
                        <li><a href="{{ route('wallet.index') }}" class="hover:text-neon-green transition-colors">Carteira</a></li>
                    </ul>
                </div>
                <!--
                <div>
                    <h4 class="font-semibold text-white mb-4">Suporte</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-neon-green transition-colors">Contacto</a></li>
                        <li><a href="#" class="hover:text-neon-green transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-neon-green transition-colors">Ajuda</a></li>
                    </ul>
                </div>
                -->
            </div>
            
            <div class="border-t border-dark-border pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-gray-400 text-sm">
                            &copy; {{ date('Y') }} TeamApostas. Todos os direitos reservados.
                        </p>
                        <p class="text-neon-green text-sm font-semibold mt-1">
                            Joga com moderação. Proibido a menores de 18 anos.
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-4">
                     
                     
                     
        </div>
    </div>
                
                
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
        
        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-slide-up');
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.game-card, .content-card, .ranking-card, .chat-mockup').forEach(el => {
                observer.observe(el);
            });
        });
        
        // Flash messages auto-hide
        document.addEventListener('DOMContentLoaded', () => {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(message => {
                setTimeout(() => {
                    message.style.opacity = '0';
                    setTimeout(() => {
                        message.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
    <script>
        window.userData = {
            id: {{ auth()->id() }},
            name: "{{ auth()->check() ? auth()->user()->name : '' }}",
            balance: {{ auth()->user()->virtual_balance ?? 0 }},
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        // Dropdown do usuário
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('user-menu-btn');
            const dropdown = document.getElementById('user-menu-dropdown');
            let open = false;
            // Garante que o menu começa invisível
            dropdown.classList.add('opacity-0', 'invisible');
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                open = !open;
                if (open) {
                    dropdown.classList.remove('opacity-0', 'invisible');
                    dropdown.classList.add('opacity-100', 'visible');
                } else {
                    dropdown.classList.add('opacity-0', 'invisible');
                    dropdown.classList.remove('opacity-100', 'visible');
                }
            });
            document.addEventListener('click', function() {
                if (open) {
                    dropdown.classList.add('opacity-0', 'invisible');
                    dropdown.classList.remove('opacity-100', 'visible');
                    open = false;
                }
            });
        });
    </script>
</body>
</html>
