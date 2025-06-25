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
                    <li><a href="{{ route('dashboard') }}" class="footer-link">Painel de Controlo</a></li>
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
        <div class="row">
            <div class="col-md-6">
                <p class="text-secondary-text-color mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('legal.terms') }}" class="footer-link me-3">Termos de Uso</a>
                <a href="{{ route('legal.privacy') }}" class="footer-link">Política de Privacidade</a>
            </div>
        </div>
    </div>
</footer> 