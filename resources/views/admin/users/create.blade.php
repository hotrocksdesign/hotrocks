@extends('admin.base')

@section('admin-content')
<h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem; margin: 30px 0 24px;">Crear Nuevo Usuario</h3>

<form method="POST" action="{{ route('admin.users.store') }}" class="card" style="max-width: 560px; padding: 32px;">
    @csrf

    <div class="field">
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" required value="{{ old('name') }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="{{ old('email') }}">
        @error('email') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>
        <p class="field-hint">Mínimo 8 caracteres.</p>
        @error('password') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="role">Rol</label>
        <select id="role" name="role" required>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor</option>
            <option value="band" {{ old('role', 'band') === 'band' ? 'selected' : '' }}>Band</option>
        </select>
        @error('role') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn btn-accent">Crear Usuario</button>
</form>
@endsection
