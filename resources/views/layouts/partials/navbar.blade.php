<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm dark:bg-gray-800 dark:border-b dark:border-gray-700">
    <div class="container-fluid">
        <!-- Logo e TeamApostas -->
        <a class="navbar-brand d-flex align-items-center me-4" href="{{ route('dashboard') }}">
            <img src="{{ asset('img/logo1111.png') }}" alt="TeamApostas" style="height: 32px; width: 32px; object-fit: contain; margin-right: 8px;">
            <span class="fw-bold" style="font-size: 1.2rem;">TeamApostas</span>
        </a>
        <!-- Botão Toggler para mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Conteúdo da Navbar -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Lado Esquerdo -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('games.index') }}">Jogos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('groups.index') }}">Comunidade</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wallet.index') }}">Carteira</a>
                </li>
            </ul>

            <!-- Lado Direito -->
            <ul class="navbar-nav ms-auto align-items-center flex-row">
                <!-- Wallet Balance -->
                <li class="nav-item me-4">
                    <div style="background: #00ff99; color: #222; font-weight: bold; font-size: 1.1rem; padding: 8px 20px; border-radius: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-wallet"></i>
                        Saldo na Carteira: <span style="margin-left: 4px;">€{{ number_format(Auth::user()->virtual_balance, 2) }}</span>
                    </div>
                </li>
                <!-- Resumo de Progresso do Utilizador -->
                <li class="nav-item me-3">
                    <div id="user-progress-header"></div>
                </li>
                <!-- User Dropdown -->
                <li class="nav-item dropdown me-3">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                        <span class="d-none d-sm-inline-block text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-fw me-2"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </li>
                <!-- Theme Toggler -->
                <li class="nav-item me-3">
                    <button id="theme-toggle" type="button" class="btn btn-sm btn-outline-secondary">
                        <i id="theme-toggle-dark-icon" class="fas fa-moon"></i>
                        <i id="theme-toggle-light-icon" class="fas fa-sun"></i>
                    </button>
                </li>
                <!-- Notification Icon -->
                <li class="nav-item notification-icon position-relative">
                    <a class="nav-link" href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav> 