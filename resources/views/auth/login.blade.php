@extends('layout')

@section('title', 'Ingresar')

@section('extra-styles')
<style>
    .auth-wrap { display: flex; justify-content: center; padding: 30px 0 60px; }
    .auth-card { width: 100%; max-width: 420px; padding: 40px; }
    .auth-card h1 { font-size: 2.4rem; text-transform: uppercase; margin-bottom: 8px; }
    .auth-card .sub { color: var(--ink-soft); font-size: .92rem; margin-bottom: 30px; }
    .auth-foot { margin-top: 24px; text-align: center; font-size: .88rem; color: var(--ink-soft); }
    .auth-foot a { font-weight: 700; color: var(--ink); }
    .auth-foot a:hover { color: var(--accent); }
    .checkbox-row { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; font-size: .88rem; font-weight: 600; }
    .checkbox-row input { width: auto; }
</style>
@endsection

@section('content')
<div class="auth-wrap">
    <div class="card auth-card reveal">
        <span class="kicker">Backstage</span>
        <h1>Ingresar</h1>
        <p class="sub">Accedé al panel para gestionar reseñas y shows.</p>

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus value="{{ old('email') }}">
            </div>

            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="checkbox-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin:0;">Recordarme</label>
            </div>

            <button type="submit" class="btn btn-accent btn-block">Ingresar</button>
        </form>

        <p class="auth-foot">¿Sos una banda y todavía no tenés cuenta? <a href="{{ route('register') }}">Registrate</a></p>
    </div>
</div>
@endsection
