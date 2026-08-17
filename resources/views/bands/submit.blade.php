@extends('layout')

@section('title', 'Sumá tu Banda')

@section('extra-styles')
<style>
    .submit-wrap { display: flex; justify-content: center; }
    .submit-card { width: 100%; max-width: 620px; padding: 40px; }
    .submit-card h1 { font-size: 2.4rem; text-transform: uppercase; margin-bottom: 8px; }
    .submit-card .sub { color: var(--ink-soft); font-size: .92rem; margin-bottom: 30px; }
</style>
@endsection

@section('content')
<div class="submit-wrap">
    <div class="card submit-card reveal">
        <span class="kicker">Sumá tu banda</span>
        <h1>Sumá tu Banda</h1>
        <p class="sub">Contanos sobre ustedes: subí toda la info que puedas (género, historia, redes, logo y fotos). No hace falta crear una cuenta — apenas la chequeemos, la banda se suma a la enciclopedia.</p>

        <form method="POST" action="{{ route('bands.submit.store') }}" enctype="multipart/form-data">
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
                @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-accent btn-block">Enviar para aprobación</button>
        </form>
    </div>
</div>
@endsection
