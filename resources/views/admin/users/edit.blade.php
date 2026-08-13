@extends('admin.base')

@section('admin-content')
<h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem; margin: 30px 0 24px;">Editar Usuario</h3>

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="card" style="max-width: 560px; padding: 32px;">
    @csrf
    @method('PUT')

    <div class="field">
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" required value="{{ old('name', $user->name) }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="{{ old('email', $user->email) }}">
        @error('email') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="password">Nueva contraseña <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="password" id="password" name="password">
        <p class="field-hint">Dejar en blanco para mantener la actual. Si la completás, mínimo 8 caracteres.</p>
        @error('password') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="role">Rol</label>
        <select id="role" name="role" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
            <option value="band" {{ old('role', $user->role) === 'band' ? 'selected' : '' }}>Band</option>
        </select>
        @if($user->id === auth()->id())
            <input type="hidden" name="role" value="{{ $user->role }}">
            <p class="field-hint">No podés cambiar tu propio rol.</p>
        @endif
        @error('role') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn btn-accent">Guardar Cambios</button>
</form>

<a href="{{ route('admin.users.index') }}" class="btn btn-ghost" style="margin-top: 18px; display:inline-block;">Cancelar</a>
@endsection
