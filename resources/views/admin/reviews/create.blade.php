@extends('admin.base')

@section('admin-content')
<h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem; margin: 30px 0 24px;">Crear Nueva Reseña</h3>

<form method="POST" action="{{ route('admin.reviews.store') }}" enctype="multipart/form-data" class="card" style="max-width: 760px; padding: 32px;">
    @csrf

    <div class="field">
        <label for="title">Título</label>
        <input type="text" id="title" name="title" required value="{{ old('title') }}">
        @error('title') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="band_id">Banda <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <select id="band_id" name="band_id">
            <option value="">Sin banda / lineup variado</option>
            @foreach($bands as $band)
                <option value="{{ $band->id }}" {{ old('band_id') == $band->id ? 'selected' : '' }}>{{ $band->name }}</option>
            @endforeach
        </select>
        @error('band_id') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="venue">Venue/Lugar</label>
        <input type="text" id="venue" name="venue" required value="{{ old('venue') }}">
        @error('venue') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="show_date">Fecha del Show</label>
        <input type="datetime-local" id="show_date" name="show_date" required value="{{ old('show_date') }}">
        @error('show_date') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="content">Contenido de la Reseña</label>
        <textarea id="content" name="content" required rows="10">{{ old('content') }}</textarea>
        @error('content') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="video_url">URL de Video (YouTube o Reel/Post de Instagram)</label>
        <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=... o https://www.instagram.com/reel/...">
        @error('video_url') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="featured_image">Imagen del show</label>
        <input type="file" id="featured_image" name="featured_image" accept="image/*">
        <p class="field-hint">Se usa en la card de la reseña y, si la marcás como principal, en la portada del home.</p>
        @error('featured_image') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="photo_credit">Crédito de la foto <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="text" id="photo_credit" name="photo_credit" value="{{ old('photo_credit') }}" placeholder="Foto: Juan Pérez">
        <p class="field-hint">Se muestra chiquito debajo de la imagen principal cuando la reseña es la destacada del home.</p>
        @error('photo_credit') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="photos">Galería de fotos <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
        <p class="field-hint">Podés seleccionar varias fotos a la vez (Cmd/Ctrl + click). Se muestran como galería en la reseña.</p>
        @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field-checkbox">
        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
        <label for="is_featured">Marcar como reseña principal (aparece en el home)</label>
    </div>

    <button type="submit" class="btn btn-accent">Crear Reseña</button>
</form>
@endsection
