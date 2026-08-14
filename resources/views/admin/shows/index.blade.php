@extends('admin.base')

@section('admin-content')
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; margin: 30px 0 20px;">
    <h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem;">Shows</h3>
    <a href="{{ route('admin.shows.create') }}" class="btn btn-accent">+ Cargar Show</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Banda(s)</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shows as $show)
                <tr>
                    <td>{{ $show->bands->pluck('name')->join(', ') }}</td>
                    <td>{{ $show->date->format('d/m/Y H:i') }}</td>
                    <td>{{ $show->venue }} ({{ $show->city }})</td>
                    <td>
                        <span class="badge {{ $show->status === 'approved' ? 'badge-approved' : ($show->status === 'rejected' ? 'badge-rejected' : 'badge-pending') }}">
                            {{ ucfirst($show->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.shows.edit', $show) }}" class="btn btn-sm btn-outline">Editar</a>
                            <form method="POST" action="{{ route('admin.shows.destroy', $show) }}" onsubmit="return confirm('¿Eliminar este show?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Sin shows cargados. <a href="{{ route('admin.shows.create') }}" style="font-weight:700;">Cargar uno</a></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $shows->links() }}
</div>
@endsection
