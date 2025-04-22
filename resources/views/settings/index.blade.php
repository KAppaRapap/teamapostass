@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Configurações</h2>
        <button onclick="window.location='{{ route('dashboard') }}'" class="btn btn-light border-0 px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" style="border-radius:16px; font-size:1.08rem; color:#1976d2; background: #f4f8fb; transition: all 0.18s; box-shadow:0 2px 8px rgba(25,118,210,0.07);">
            <span class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle me-2" style="width:32px; height:32px; font-size:1.15rem;">
                <i class="fas fa-arrow-left"></i>
            </span>
            Voltar ao Dashboard
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="true">
                            <i class="fas fa-user me-2"></i> Perfil
                        </button>
                        <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button" role="tab" aria-controls="v-pills-password" aria-selected="false">
                            <i class="fas fa-lock me-2"></i> Senha
                        </button>
                        <button class="nav-link" id="v-pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#v-pills-notifications" type="button" role="tab" aria-controls="v-pills-notifications" aria-selected="false">
                            <i class="fas fa-bell me-2"></i> Notificações
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <div class="tab-content" id="v-pills-tabContent">
                <!-- Perfil -->
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Informações do Perfil</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update-profile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="city" class="form-label">Cidade</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $user->city) }}">
                                    @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Foto de Perfil -->
                                <div class="mb-3 text-center">
                                    <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('img/default-avatar.png') }}" class="rounded-circle mb-2" width="100" height="100" alt="Foto de Perfil">
                                    <input type="file" class="form-control mt-2 @error('profile_photo') is-invalid @enderror" name="profile_photo" accept="image/*">
                                    @error('profile_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Atualizar Perfil
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Senha -->
                <div class="tab-pane fade" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Alterar Senha</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update-password') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Senha Atual</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                    @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nova Senha</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-lock me-1"></i> Atualizar Senha
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Notificações -->
                <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Preferências de Notificação</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update-notifications') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notify_new_draws" name="notify_new_draws" value="1" checked>
                                        <label class="form-check-label" for="notify_new_draws">Novos Sorteios</label>
                                        <div class="form-text">Receber notificações sobre novos sorteios dos jogos que você participa.</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notify_results" name="notify_results" value="1" checked>
                                        <label class="form-check-label" for="notify_results">Resultados</label>
                                        <div class="form-text">Receber notificações sobre resultados dos sorteios.</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notify_group_activities" name="notify_group_activities" value="1" checked>
                                        <label class="form-check-label" for="notify_group_activities">Atividades de Grupo</label>
                                        <div class="form-text">Receber notificações sobre atividades nos grupos que você participa.</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" checked>
                                        <label class="form-check-label" for="email_notifications">Notificações por Email</label>
                                        <div class="form-text">Além das notificações no site, receber também por email.</div>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Salvar Preferências
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
