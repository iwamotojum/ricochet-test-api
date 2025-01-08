@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Redefinir Senha</h2>

        <form method="POST" action="{{ route('password.reset.post') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="email" name="email" value="{{ $email }}" required>
            <div>
                <label for="password">Nova Senha</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>
            <div>
                <button type="submit">Redefinir Senha</button>
            </div>
        </form>
    </div>
@endsection
