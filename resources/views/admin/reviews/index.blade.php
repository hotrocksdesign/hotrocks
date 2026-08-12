@extends('admin.base')

@section('admin-content')
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; margin: 30px 0 20px;">
    <h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem;">Mis Reseñas</h3>
    <a href="{{ route('admin.reviews.create') }}" class="btn btn-accent">+ Nueva Reseña</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Banda</th>
                <th>Fecha del Show</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->title }}</td>
                    <td>
                        @if($review->band)
                            <a href="{{ route('bands.show', $review->band) }}" style="font-weight:700;">{{ $review->band->name }}</a>
                        @else
                            <span style="color:var(--ink-faint);">Sin banda</span>
                        @endif
                    </td>
                    <td>{{ $review->show_date->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $review->published_at ? 'badge-approved' : 'badge-pending' }}">
                            {{ $review->published_at ? 'Publicada' : 'Borrador' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-outline">Editar</a>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('¿Estás seguro?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Sin reseñas. <a href="{{ route('admin.reviews.create') }}" style="font-weight:700;">Crear una</a></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $reviews->links() }}
</div>
@endsection
