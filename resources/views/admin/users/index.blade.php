@extends('admin.base')

@section('admin-content')
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; margin: 30px 0 20px;">
    <h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem;">Usuarios</h3>
    <a href="{{ route('admin.users.create') }}" class="btn btn-accent">+ Nuevo Usuario</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Banda</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td style="text-transform:capitalize;">{{ $user->role }}</td>
                    <td>
                        @if($user->band)
                            <a href="{{ route('bands.show', $user->band) }}" style="font-weight:700;">{{ $user->band->name }}</a>
                        @else
                            <span style="color:var(--ink-faint);">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline">Editar</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('¿Eliminar este usuario? También se borran sus reseñas, noticias y shows cargados.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-ghost">Eliminar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Sin usuarios.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $users->links() }}
</div>
@endsection
