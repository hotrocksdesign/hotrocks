@extends('admin.base')

@section('admin-content')
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; margin: 30px 0 20px;">
    <h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem;">Bandas</h3>
    <a href="{{ route('admin.bands.create') }}" class="btn btn-accent">+ Nueva Banda</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Logo</th>
                <th>Nombre</th>
                <th>Género</th>
                <th>Fotos</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bands as $band)
                <tr>
                    <td>
                        @if($band->photo_url)
                            <img src="{{ asset('storage/' . $band->photo_url) }}" alt="" style="width:44px; height:44px; object-fit:cover; border-radius:8px; border:1px solid var(--line);">
                        @else
                            <span style="color:var(--ink-faint); font-size:.8rem;">—</span>
                        @endif
                    </td>
                    <td><a href="{{ route('bands.show', $band) }}" style="font-weight:700;">{{ $band->name }}</a></td>
                    <td>{{ $band->genre ?? '—' }}</td>
                    <td>{{ $band->photos->count() }}</td>
                    <td>
                        <span class="badge {{ $band->is_approved ? 'badge-approved' : 'badge-pending' }}">
                            {{ $band->is_approved ? 'Publicada' : 'Pendiente' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.bands.edit', $band) }}" class="btn btn-sm btn-outline">Editar</a>
                            <form method="POST" action="{{ route('admin.bands.destroy', $band) }}" onsubmit="return confirm('¿Eliminar esta banda? También se borran sus fotos.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Sin bandas. <a href="{{ route('admin.bands.create') }}" style="font-weight:700;">Crear una</a></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $bands->links() }}
</div>
@endsection
