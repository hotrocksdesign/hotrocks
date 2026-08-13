@extends('layout')

@section('title', 'Panel de Administración')

@section('extra-styles')
<style>
    .admin-nav {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        padding: 18px;
        margin-bottom: 40px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
    }
    .admin-nav a {
        padding: 9px 16px;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: .85rem;
        color: var(--ink-soft);
        border: 1px solid transparent;
    }
    .admin-nav a:hover { color: var(--ink); background: var(--bg); }
    .admin-nav a.active { background: var(--accent); color: var(--on-accent); }

    .admin-table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: var(--radius); background: var(--surface); }
    .admin-table { width: 100%; border-collapse: collapse; }
    .admin-table th {
        background: var(--ink);
        color: #F7F4EE;
        padding: 14px 18px;
        text-align: left;
        font-size: .78rem;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-weight: 700;
    }
    .admin-table td { padding: 16px 18px; border-bottom: 1px solid var(--line); font-size: .92rem; }
    .admin-table tr:last-child td { border-bottom: none; }
    .admin-table tr:hover td { background: var(--bg); }

    .action-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
    .action-buttons form { display: inline; }
</style>
@endsection

@section('content')
<div class="section-head reveal">
    <span class="kicker">Backstage</span>
    <h2>Panel de Administración</h2>
</div>

<div class="admin-nav reveal">
    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
        <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}">Mis Reseñas</a>
        <a href="{{ route('admin.reviews.create') }}" class="{{ request()->routeIs('admin.reviews.create') ? 'active' : '' }}">+ Crear Reseña</a>
        <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.index') ? 'active' : '' }}">Noticias</a>
        <a href="{{ route('admin.news.create') }}" class="{{ request()->routeIs('admin.news.create') ? 'active' : '' }}">+ Crear Noticia</a>
        <a href="{{ route('admin.bands.index') }}" class="{{ request()->routeIs('admin.bands.*') ? 'active' : '' }}">Bandas</a>
        <a href="{{ route('admin.bands.create') }}" class="{{ request()->routeIs('admin.bands.create') ? 'active' : '' }}">+ Nueva Banda</a>
    @endif
    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.shows.create') }}" class="{{ request()->routeIs('admin.shows.create') ? 'active' : '' }}">+ Cargar Show</a>
        <a href="{{ route('admin.shows.pending') }}" class="{{ request()->routeIs('admin.shows.pending') ? 'active' : '' }}">Aprobar Shows</a>
    @endif
</div>

@yield('admin-content')

@endsection
