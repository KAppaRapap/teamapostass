@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">Depositar na Carteira Virtual</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('wallet.deposit.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Valor do Depósito</label>
                            <input type="number" min="1" step="0.01" class="form-control" id="amount" name="amount" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Depositar</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-link w-100 mt-2">Voltar ao Dashboard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
