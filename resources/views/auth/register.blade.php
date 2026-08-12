@extends('layout')

@section('title', 'Registro')

@section('extra-styles')
<style>
    .auth-wrap { display: flex; justify-content: center; padding: 30px 0 60px; }
    .auth-card { width: 100%; max-width: 420px; padding: 40px; }
    .auth-card h1 { font-size: 2.4rem; text-transform: uppercase; margin-bottom: 8px; }
    .auth-card .sub { color: var(--ink-soft); font-size: .92rem; margin-bottom: 30px; }
    .auth-foot { margin-top: 24px; text-align: center; font-size: .88rem; color: var(--ink-soft); }
    .auth-foot a { font-weight: 700; color: var(--ink); }
    .auth-foot a:hover { color: var(--accent); }
</style>
@endsection

@section('content')
<div class="auth-wrap">
    <div class="card auth-card reveal">
        <span class="kicker">Sumate</span>
        <h1>Crear cuenta</h1>
        <p class="sub">Para bandas que quieren sumar sus shows a la agenda.</p>

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" required autofocus value="{{ old('name') }}">
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}">
            </div>

            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-accent btn-block">Crear cuenta</button>
        </form>

        <p class="auth-foot">¿Ya tenés cuenta? <a href="{{ route('login') }}">Ingresá</a></p>
    </div>
</div>
@endsection
