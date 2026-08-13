@extends('admin.base')

@section('admin-content')
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; margin: 30px 0 20px;">
    <h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem;">Noticias</h3>
    <a href="{{ route('admin.news.create') }}" class="btn btn-accent">+ Nueva Noticia</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($newsItems as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>
                        <span class="badge {{ $item->published_at ? 'badge-approved' : 'badge-pending' }}">
                            {{ $item->published_at ? 'Publicada' : 'Borrador' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-outline">Editar</a>
                            <form method="POST" action="{{ route('admin.news.destroy', $item) }}" onsubmit="return confirm('¿Estás seguro?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Sin noticias. <a href="{{ route('admin.news.create') }}" style="font-weight:700;">Crear una</a></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $newsItems->links() }}
</div>
@endsection
