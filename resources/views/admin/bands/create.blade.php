@extends('admin.base')

@section('admin-content')
<h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem; margin: 30px 0 24px;">Nueva Banda</h3>

<form method="POST" action="{{ route('admin.bands.store') }}" enctype="multipart/form-data" class="card" style="max-width: 760px; padding: 32px;">
    @csrf

    <div class="field">
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" required value="{{ old('name') }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="genre">Género <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="text" id="genre" name="genre" value="{{ old('genre') }}" placeholder="Rock, Punk, Metal...">
        @error('genre') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="biography">Historia <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <textarea id="biography" name="biography" rows="6">{{ old('biography') }}</textarea>
        @error('biography') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="instagram_url">Instagram <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="https://instagram.com/...">
        @error('instagram_url') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="spotify_url">Spotify <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="url" id="spotify_url" name="spotify_url" value="{{ old('spotify_url') }}" placeholder="https://open.spotify.com/...">
        @error('spotify_url') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="youtube_url">YouTube <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="url" id="youtube_url" name="youtube_url" value="{{ old('youtube_url') }}" placeholder="https://youtube.com/...">
        @error('youtube_url') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="logo">Logo <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="file" id="logo" name="logo" accept="image/*">
        @error('logo') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="photos">Galería de fotos <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
        <p class="field-hint">Podés seleccionar varias fotos a la vez (Cmd/Ctrl + click). Se muestran como carrusel en la ficha de la banda.</p>
        @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field-checkbox">
        <input type="checkbox" id="is_approved" name="is_approved" value="1" {{ old('is_approved', true) ? 'checked' : '' }}>
        <label for="is_approved">Publicada (visible en /bandas)</label>
    </div>

    <button type="submit" class="btn btn-accent">Crear Banda</button>
</form>
@endsection
