@extends('admin.base')

@section('admin-content')
<h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem; margin: 30px 0 24px;">Editar Banda</h3>

<form method="POST" action="{{ route('admin.bands.update', $band) }}" enctype="multipart/form-data" class="card" style="max-width: 760px; padding: 32px;">
    @csrf
    @method('PUT')

    <div class="field">
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" required value="{{ $band->name }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="genre">Género <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="text" id="genre" name="genre" value="{{ $band->genre }}">
        @error('genre') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="biography">Historia <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <textarea id="biography" name="biography" rows="6">{{ $band->biography }}</textarea>
        @error('biography') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="instagram_url">Instagram <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="url" id="instagram_url" name="instagram_url" value="{{ $band->instagram_url }}">
        @error('instagram_url') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="spotify_url">Spotify <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="url" id="spotify_url" name="spotify_url" value="{{ $band->spotify_url }}">
        @error('spotify_url') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="youtube_url">YouTube <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="url" id="youtube_url" name="youtube_url" value="{{ $band->youtube_url }}">
        @error('youtube_url') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="logo">Logo <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        @if($band->photo_url)
            <img src="{{ asset('storage/' . $band->photo_url) }}" alt="" class="current-image">
        @endif
        <input type="file" id="logo" name="logo" accept="image/*">
        <p class="field-hint">Dejalo vacío para mantener el logo actual.</p>
        @error('logo') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="photos">Agregar más fotos a la galería</label>
        <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
        @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field-checkbox">
        <input type="checkbox" id="is_approved" name="is_approved" value="1" {{ $band->is_approved ? 'checked' : '' }}>
        <label for="is_approved">Publicada (visible en /bandas)</label>
    </div>

    <div style="display:flex; gap:12px;">
        <button type="submit" class="btn btn-accent">Guardar Cambios</button>
        <a href="{{ route('admin.bands.index') }}" class="btn btn-ghost">Cancelar</a>
    </div>
</form>

@if($band->photos->count())
    {{-- Fuera del form de arriba a propósito: un <form> no puede ir anidado
         dentro de otro <form> (HTML inválido). Antes estaba adentro y el
         navegador mezclaba este _method=DELETE con el del form principal,
         así que "Guardar Cambios" terminaba borrando la banda entera. --}}
    <div class="card" style="max-width: 760px; padding: 32px; margin-top: 24px;">
        <label>Fotos actuales <span style="color:var(--ink-faint); font-weight:400;">(click en la X para quitar una)</span></label>
        <div class="gallery-manage">
            @foreach($band->photos as $photo)
                <div class="gallery-manage-item">
                    <img src="{{ asset('storage/' . $photo->photo_url) }}" alt="">
                    <form method="POST" action="{{ route('admin.bands.photos.destroy', [$band, $photo]) }}" onsubmit="return confirm('¿Quitar esta foto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" aria-label="Quitar foto">&times;</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
