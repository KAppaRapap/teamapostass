@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">⚙️ Configuração Admin Geral</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>🎰 Configurações dos Jogos</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.config.update') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Limite de Aposta (€)</label>
                                            <input type="number" class="form-control" name="bet_limit" value="{{ old('bet_limit', $settings->get('bet_limit', 1000)) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">House Edge (%)</label>
                                            <input type="number" class="form-control" name="house_edge" value="{{ old('house_edge', $settings->get('house_edge', 1)) }}" step="0.1">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Manutenção</label>
                                            <div class="form-check">
                                                <input type="hidden" name="maintenance_mode" value="0">
                                                <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance" value="1" @if(old('maintenance_mode', $settings->get('maintenance_mode', 0))) checked @endif>
                                                <label class="form-check-label" for="maintenance">
                                                    Modo Manutenção
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>👥 Configurações de Usuários</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.config.update') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Limite de Usuários por Grupo</label>
                                            <input type="number" class="form-control" name="group_user_limit" value="{{ old('group_user_limit', $settings->get('group_user_limit', 50)) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Idade Mínima</label>
                                            <input type="number" class="form-control" name="minimum_age" value="{{ old('minimum_age', $settings->get('minimum_age', 18)) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Verificação de Email</label>
                                            <div class="form-check">
                                                <input type="hidden" name="require_email_verification" value="0">
                                                <input class="form-check-input" type="checkbox" name="require_email_verification" id="emailVerification" value="1" @if(old('require_email_verification', $settings->get('require_email_verification', 1))) checked @endif>
                                                <label class="form-check-label" for="emailVerification">
                                                    Obrigatória
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>💰 Configurações Financeiras</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.config.update') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Saldo Inicial (€)</label>
                                            <input type="number" class="form-control" name="initial_balance" value="{{ old('initial_balance', $settings->get('initial_balance', 1000)) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Taxa de Depósito (%)</label>
                                            <input type="number" class="form-control" name="deposit_fee_percentage" value="{{ old('deposit_fee_percentage', $settings->get('deposit_fee_percentage', 0)) }}" step="0.1">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Taxa de Saque (%)</label>
                                            <input type="number" class="form-control" name="withdrawal_fee_percentage" value="{{ old('withdrawal_fee_percentage', $settings->get('withdrawal_fee_percentage', 2.5)) }}" step="0.1">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>🔔 Configurações de Notificações</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.config.update') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Notificações Push</label>
                                            <div class="form-check">
                                                <input type="hidden" name="push_notifications_enabled" value="0">
                                                <input class="form-check-input" type="checkbox" name="push_notifications_enabled" id="pushNotifications" value="1" @if(old('push_notifications_enabled', $settings->get('push_notifications_enabled', 1))) checked @endif>
                                                <label class="form-check-label" for="pushNotifications">
                                                    Ativadas
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Notificações por Email</label>
                                            <div class="form-check">
                                                <input type="hidden" name="email_notifications_enabled" value="0">
                                                <input class="form-check-input" type="checkbox" name="email_notifications_enabled" id="emailNotifications" value="1" @if(old('email_notifications_enabled', $settings->get('email_notifications_enabled', 1))) checked @endif>
                                                <label class="form-check-label" for="emailNotifications">
                                                    Ativadas
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Notificações SMS</label>
                                            <div class="form-check">
                                                <input type="hidden" name="sms_notifications_enabled" value="0">
                                                <input class="form-check-input" type="checkbox" name="sms_notifications_enabled" id="smsNotifications" value="1" @if(old('sms_notifications_enabled', $settings->get('sms_notifications_enabled', 0))) checked @endif>
                                                <label class="form-check-label" for="smsNotifications">
                                                    Ativadas
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 