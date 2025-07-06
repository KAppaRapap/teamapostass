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
                <i class="fas fa-home"></i> Painel de Controlo
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('groups.index') ? 'active' : '' }}" href="{{ route('groups.index') }}">
                <i class="fas fa-users"></i> Meus Grupos
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('groups.chat') ? 'active' : '' }}" href="{{ route('groups.chat', ['group' => 1]) }}">
                <i class="fas fa-comments"></i> Chat Geral
            </a>
        </li>
        <li class="sidebar-section-title mt-3 mb-1 text-uppercase small text-muted ps-3">Jogos de Casino</li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('games.crash') ? 'active' : '' }}" href="{{ route('games.crash') }}">
                <i class="fas fa-rocket"></i> Crash
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('games.dice') ? 'active' : '' }}" href="{{ route('games.dice') }}">
                <i class="fas fa-dice"></i> Dice
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('games.bombmine') ? 'active' : '' }}" href="{{ route('games.bombmine') }}">
                <i class="fas fa-bomb"></i> BombMine
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('results.index') ? 'active' : '' }}" href="{{ route('results.index') }}">
                <i class="fas fa-trophy"></i> Resultados
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                <i class="fas fa-bell"></i> Notificações
            </a>
        </li>
        @if(Auth::user()->is_admin)
        <li class="sidebar-section-title mt-3 mb-1 text-uppercase small text-muted ps-3">Admin</li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-user-cog"></i> Gerenciar Usuários
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