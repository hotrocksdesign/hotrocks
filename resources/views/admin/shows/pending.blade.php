@extends('admin.base')

@section('admin-content')
<h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem; margin: 30px 0 24px;">Cola de Aprobación de Shows</h3>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Banda(s)</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Cargado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shows as $show)
                <tr>
                    <td>
                        @foreach($show->bands as $band)
                            <a href="{{ route('bands.show', $band) }}" style="font-weight:700;">{{ $band->name }}</a>
                            @unless($band->is_approved)
                                <span class="badge badge-pending" style="font-size:.68rem; padding:2px 8px;">nueva</span>
                            @endunless
                            {{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </td>
                    <td>{{ $show->date->format('d/m/Y H:i') }}</td>
                    <td>{{ $show->venue }} ({{ $show->city }})</td>
                    <td>{{ $show->user->name }}</td>
                    <td>
                        <div class="action-buttons">
                            <form method="POST" action="{{ route('admin.shows.approve', $show) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-accent">Aprobar</button>
                            </form>
                            <button type="button" class="btn btn-sm btn-outline" onclick="document.getElementById('reject-{{ $show->id }}').style.display='block'">Rechazar</button>
                            <form method="POST" action="{{ route('admin.shows.destroy', $show) }}" onsubmit="return confirm('¿Eliminar este show?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost">Eliminar</button>
                            </form>
                        </div>

                        <div id="reject-{{ $show->id }}" style="display: none; margin-top: 14px; padding: 16px; background: var(--bg); border-radius: var(--radius-sm); min-width: 260px;">
                            <form method="POST" action="{{ route('admin.shows.reject', $show) }}">
                                @csrf
                                <label for="reason-{{ $show->id }}">Motivo del rechazo</label>
                                <textarea name="rejection_reason" id="reason-{{ $show->id }}" rows="3" required style="margin-bottom:10px;"></textarea>
                                <div style="display: flex; gap: 8px;">
                                    <button type="submit" class="btn btn-sm btn-accent">Rechazar</button>
                                    <button type="button" class="btn btn-sm btn-ghost" onclick="document.getElementById('reject-{{ $show->id }}').style.display='none'">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Sin shows pendientes de aprobación</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $shows->links() }}
</div>
@endsection
