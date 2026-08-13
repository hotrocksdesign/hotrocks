@extends('admin.base')

@section('admin-content')
<h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem; margin: 30px 0 24px;">Crear Nueva Noticia</h3>

<form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="card" style="max-width: 760px; padding: 32px;">
    @csrf

    <div class="field">
        <label for="title">Título</label>
        <input type="text" id="title" name="title" required value="{{ old('title') }}">
        @error('title') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="content">Contenido</label>
        <textarea id="content" name="content" required rows="10">{{ old('content') }}</textarea>
        @error('content') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="featured_image">Imagen <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="file" id="featured_image" name="featured_image" accept="image/*">
        @error('featured_image') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field-checkbox">
        <input type="checkbox" id="published" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
        <label for="published">Publicar ahora (si no, queda como borrador)</label>
    </div>

    <button type="submit" class="btn btn-accent">Crear Noticia</button>
</form>
@endsection
