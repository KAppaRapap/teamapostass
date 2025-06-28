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
    <link rel="stylesheet" href="{{ asset('css/cyber-pagination.css') }}">

    @stack('styles')

    <script>
        tailwind = {};
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #121212;
            color: #ffffff;
            font-size: 14px; /* Reduzir tamanho base da fonte */
            line-height: 1.4; /* Reduzir altura da linha */
        }
        
        /* Header Fixo */
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            backdrop-filter: blur(20px);
            background: rgba(18, 18, 18, 0.98);
            border-bottom: 1px solid rgba(0, 255, 178, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Hero Section */
        .hero-bg {
            background: radial-gradient(ellipse at 70% 30%, rgba(0, 255, 178, 0.15) 0%, rgba(255, 0, 92, 0.08) 40%, #121212 70%);
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
            padding: 14px 36px;
            border-radius: 50px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 255, 178, 0.5);
            background: linear-gradient(135deg, #00CC8F 0%, #00FFB2 100%);
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
        
        /* Espaçamento Global */
        .max-w-8xl {
            max-width: 90rem;
        }

        /* Melhorias no Menu */
        nav a {
            position: relative;
            overflow: hidden;
        }

        nav a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #00FFB2, #00CC8F);
            transition: left 0.3s ease;
        }

        nav a:hover::before,
        nav a.text-neon-green::before {
            left: 0;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 3rem !important;
            }

            .hero-subtitle {
                font-size: 1.25rem !important;
            }

            .header-fixed {
                padding: 1rem;
            }

            .max-w-8xl {
                padding-left: 1rem;
                padding-right: 1rem;
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
    <header class="header-fixed py-3 px-6 lg:px-8">
        <div class="max-w-8xl mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('img/logo1111.png') }}" alt="TeamApostas" class="h-10 w-auto transition-transform group-hover:scale-110">
                <span class="text-lg font-orbitron font-bold text-white tracking-wider">TeamApostas</span>
            </a>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center gap-4 ml-12">
                <a href="/" class="text-white hover:text-neon-green transition-colors duration-300 font-medium text-sm px-2 py-1.5 rounded-lg hover:bg-white hover:bg-opacity-5 {{ request()->is('/') ? 'text-neon-green bg-neon-green bg-opacity-10' : '' }}">Início</a>
                <a href="{{ route('games.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 font-medium text-sm px-2 py-1.5 rounded-lg hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('games.*') ? 'text-neon-green bg-neon-green bg-opacity-10' : '' }}">Jogos</a>
                <a href="{{ route('groups.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 font-medium text-sm px-2 py-1.5 rounded-lg hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('groups.*') ? 'text-neon-green bg-neon-green bg-opacity-10' : '' }}">Comunidade</a>
                <a href="{{ route('wallet.index') }}" class="text-white hover:text-neon-green transition-colors duration-300 font-medium text-sm px-2 py-1.5 rounded-lg hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('wallet.*') ? 'text-neon-green bg-neon-green bg-opacity-10' : '' }}">Carteira</a>

                <!-- Auth Buttons -->
                @auth
                    <div class="flex items-center gap-4 ml-6">
                        <a href="{{ url('/dashboard') }}" class="btn-primary text-sm px-4 py-2">Painel</a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="btn-outline text-sm px-4 py-2 border-red-500 text-red-400 hover:bg-red-500 hover:text-white">
                                <i class="fas fa-crown mr-1"></i>Admin
                            </a>
                        @endif
                        <div class="bg-neon-green text-dark-bg font-bold text-sm px-4 py-2 rounded-full shadow-lg flex items-center gap-2 hover:shadow-xl transition-shadow">
                            <i class="fas fa-wallet"></i>
                            <span class="balance-display">€{{ number_format(auth()->user()->virtual_balance, 2) }}</span>
                        </div>
                        <div class="relative">
                            <button id="user-menu-btn" class="flex items-center gap-3 text-white hover:text-neon-green transition-colors duration-300 focus:outline-none p-2 rounded-lg hover:bg-white hover:bg-opacity-5">
                                <img src="{{ auth()->user()->profile_photo_url ?? asset('img/default-avatar.png') }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-10 h-10 rounded-full border-2 border-neon-green">
                                <span class="hidden lg:block font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            <div id="user-menu-dropdown" class="absolute right-0 mt-3 w-56 bg-dark-card border border-dark-border rounded-xl shadow-2xl opacity-0 invisible transition-all duration-300 z-50">
                                <div class="py-3">
                                    <a href="{{ route('profile.edit') }}" class="block px-6 py-3 text-white hover:bg-neon-green hover:text-dark-bg transition-colors rounded-lg mx-2">
                                        <i class="fas fa-user mr-3"></i>Perfil
                                    </a>
                                    <a href="{{ route('notifications.index') }}" class="block px-6 py-3 text-white hover:bg-neon-green hover:text-dark-bg transition-colors rounded-lg mx-2">
                                        <i class="fas fa-bell mr-3"></i>Notificações
                                    </a>
                                    <hr class="border-dark-border my-3 mx-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-6 py-3 text-white hover:bg-red-600 transition-colors rounded-lg mx-2">
                                            <i class="fas fa-sign-out-alt mr-3"></i>Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4 ml-6">
                        <a href="{{ route('login') }}" class="text-white hover:text-neon-green transition-colors duration-300 font-medium text-sm px-3 py-1.5 rounded-lg hover:bg-white hover:bg-opacity-5">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary text-sm px-4 py-2">Registo</a>
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
    <main class="pt-16">
        <div class="px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark-card border-t border-dark-border py-12 px-6 lg:px-8 mt-16">
        <div class="max-w-8xl mx-auto">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <img src="{{ asset('img/logo1111.png') }}" alt="TeamApostas" class="h-10 w-auto">
                        <span class="font-orbitron font-bold text-2xl">TeamApostas</span>
                    </div>
                    <p class="text-gray-400 text-lg leading-relaxed">
                        O casino da nova geração. Aposta, vence e partilha com a tua equipa.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-white mb-6 text-xl">Jogos</h4>
                    <ul class="space-y-4 text-lg text-gray-400">
                        <li><a href="{{ route('games.dice') }}" class="hover:text-neon-green transition-colors">Dice</a></li>
                        <li><a href="{{ route('games.bombmine') }}" class="hover:text-neon-green transition-colors">Bomb Mine</a></li>
                        <li><a href="{{ route('games.crash') }}" class="hover:text-neon-green transition-colors">Crash</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-6 text-xl">Comunidade</h4>
                    <ul class="space-y-4 text-lg text-gray-400">
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
            
            <div class="border-t border-dark-border pt-12">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <p class="text-gray-400 text-lg">
                            &copy; {{ date('Y') }} TeamApostas. Todos os direitos reservados.
                        </p>
                        <p class="text-neon-green text-lg font-semibold mt-2">
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

        // Sistema de Atualização Automática do Saldo
        class BalanceUpdater {
            constructor() {
                this.isUpdating = false;
                this.updateInterval = null;
                this.lastBalance = window.userData.balance;
                this.init();
            }

            init() {
                // Atualizar saldo a cada 1 segundo (conforme solicitado)
                this.startAutoUpdate();

                // Escutar eventos personalizados para atualização imediata
                document.addEventListener('balanceChanged', () => {
                    this.updateBalance();
                });
            }

            startAutoUpdate() {
                this.updateInterval = setInterval(() => {
                    this.updateBalance();
                }, 1000); // 1 segundo
            }

            stopAutoUpdate() {
                if (this.updateInterval) {
                    clearInterval(this.updateInterval);
                    this.updateInterval = null;
                }
            }

            async updateBalance() {
                if (this.isUpdating) return;

                this.isUpdating = true;

                try {
                    const response = await fetch('/api/user/balance', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.userData.csrfToken,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Só atualizar se o saldo mudou
                        if (data.balance !== this.lastBalance) {
                            this.lastBalance = data.balance;
                            window.userData.balance = data.balance;
                            this.updateBalanceDisplay(data.formatted);

                            // Disparar evento para outros componentes
                            document.dispatchEvent(new CustomEvent('balanceUpdated', {
                                detail: { balance: data.balance, formatted: data.formatted }
                            }));
                        }
                    }
                } catch (error) {
                    console.error('Erro ao atualizar saldo:', error);
                } finally {
                    this.isUpdating = false;
                }
            }

            updateBalanceDisplay(formattedBalance) {
                // Atualizar todos os elementos que mostram o saldo
                const balanceElements = document.querySelectorAll('.balance-display, .virtual-wallet-balance');

                balanceElements.forEach(element => {
                    if (element.textContent.includes('€')) {
                        element.textContent = formattedBalance;

                        // Adicionar animação de destaque
                        element.classList.add('balance-updated');
                        setTimeout(() => {
                            element.classList.remove('balance-updated');
                        }, 1000);
                    }
                });

                // Atualizar saldo no header especificamente
                const headerBalance = document.querySelector('.bg-neon-green span');
                if (headerBalance) {
                    headerBalance.textContent = formattedBalance;
                    headerBalance.parentElement.classList.add('balance-updated');
                    setTimeout(() => {
                        headerBalance.parentElement.classList.remove('balance-updated');
                    }, 1000);
                }
            }

            // Método para forçar atualização imediata (para usar após apostas)
            forceUpdate() {
                this.updateBalance();
            }
        }

        // Inicializar o sistema quando a página carregar
        document.addEventListener('DOMContentLoaded', () => {
            window.balanceUpdater = new BalanceUpdater();
        });

        // Função global para atualizar saldo após apostas
        window.updateBalanceAfterBet = function() {
            if (window.balanceUpdater) {
                window.balanceUpdater.forceUpdate();
            }
        };
    </script>

    <!-- Estilos para animação de atualização do saldo -->
    <style>
        .balance-updated {
            animation: balanceGlow 1s ease-in-out;
        }

        @keyframes balanceGlow {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 rgba(0, 255, 178, 0);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 20px rgba(0, 255, 178, 0.5);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 rgba(0, 255, 178, 0);
            }
        }
    </style>
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
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
