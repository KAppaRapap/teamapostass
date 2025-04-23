@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Adicionar Jogos ao Sistema</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p class="alert alert-info">
                Esta página permite adicionar os jogos padrão ao sistema. Clique no botão abaixo para adicionar Euromilhões, Totoloto e Placard. <b>Totobola não pode ser criado manualmente.</b>
            </p>
            
            <form action="{{ route('admin.create-games') }}" method="POST">
                @csrf
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-1"></i> Adicionar Jogos Padrão
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
