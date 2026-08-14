@extends('layout')

@section('title', 'Mi Banda')

@section('extra-styles')
<style>
    .profile-wrap { display: flex; justify-content: center; }
    .profile-card { width: 100%; max-width: 640px; padding: 40px; }
    .profile-card h1 { font-size: 2.4rem; text-transform: uppercase; margin-bottom: 8px; }
    .profile-card .sub { color: var(--ink-soft); font-size: .92rem; margin-bottom: 24px; }
</style>
@endsection

@section('content')
<div class="profile-wrap">
    <div class="card profile-card reveal">
        <span class="kicker">{{ $band ? 'Tu ficha' : 'Ficha nueva' }}</span>
        <h1>{{ $band ? 'Editar tu Banda' : 'Crear tu Banda' }}</h1>

        @if($band)
            <p class="sub">
                Estado actual:
                <span class="badge {{ $band->is_approved ? 'badge-approved' : 'badge-pending' }}">
                    {{ $band->is_approved ? 'Publicada' : 'Pendiente de aprobación' }}
                </span>
            </p>
        @else
            <p class="sub">Completá los datos de tu banda. Un admin va a revisar y aprobar la ficha antes de que aparezca en la enciclopedia.</p>
        @endif

        <form method="POST" action="{{ route('band.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" required value="{{ old('name', $band->name ?? '') }}">
                @error('name') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="genre">Género <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                <input type="text" id="genre" name="genre" value="{{ old('genre', $band->genre ?? '') }}" placeholder="Rock, Punk, Metal...">
                @error('genre') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="biography">Historia <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                <textarea id="biography" name="biography" rows="6">{{ old('biography', $band->biography ?? '') }}</textarea>
                @error('biography') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="instagram_url">Instagram <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $band->instagram_url ?? '') }}" placeholder="https://instagram.com/...">
                @error('instagram_url') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="spotify_url">Spotify <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                <input type="url" id="spotify_url" name="spotify_url" value="{{ old('spotify_url', $band->spotify_url ?? '') }}" placeholder="https://open.spotify.com/...">
                @error('spotify_url') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="youtube_url">YouTube <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                <input type="url" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $band->youtube_url ?? '') }}" placeholder="https://youtube.com/...">
                @error('youtube_url') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="logo">Logo <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                @if(($band->photo_url ?? null))
                    <img src="{{ asset('storage/' . $band->photo_url) }}" alt="" class="current-image">
                @endif
                <input type="file" id="logo" name="logo" accept="image/*">
                @error('logo') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="photos">Agregar fotos a la galería</label>
                <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
                @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-accent btn-block">{{ $band ? 'Guardar cambios' : 'Crear perfil' }}</button>
        </form>

        @if($band && $band->photos->count())
            {{-- Fuera del form de arriba a propósito: un <form> no puede ir
                 anidado dentro de otro <form> (HTML inválido). Antes estaba
                 adentro y el navegador mezclaba este _method=DELETE con el
                 submit de "Guardar cambios", rompiendo el guardado. --}}
            <div class="field" style="margin-top: 24px;">
                <label>Fotos actuales <span style="color:var(--ink-faint); font-weight:400;">(click en la X para quitar una)</span></label>
                <div class="gallery-manage">
                    @foreach($band->photos as $photo)
                        <div class="gallery-manage-item">
                            <img src="{{ asset('storage/' . $photo->photo_url) }}" alt="">
                            <form method="POST" action="{{ route('band.profile.photos.destroy', $photo) }}" onsubmit="return confirm('¿Quitar esta foto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Quitar foto">&times;</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
