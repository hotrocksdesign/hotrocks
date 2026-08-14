@extends('admin.base')

@section('admin-content')
<h3 style="font-family:'Inter',sans-serif; font-weight:800; font-size:1.3rem; margin: 30px 0 24px;">Editar Show</h3>

<form method="POST" action="{{ route('admin.shows.update', $show) }}" enctype="multipart/form-data" class="card" style="max-width: 760px; padding: 32px;">
    @csrf
    @method('PUT')

    @include('partials.band-name-inputs', ['bands' => $bands, 'defaultNames' => $defaultNames])

    <div class="field">
        <label for="date">Fecha y hora</label>
        <input type="datetime-local" id="date" name="date" required value="{{ old('date', $show->date->format('Y-m-d\TH:i')) }}">
        @error('date') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="venue">Venue/Lugar</label>
        <input type="text" id="venue" name="venue" required value="{{ old('venue', $show->venue) }}">
        @error('venue') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="city">Ciudad</label>
        <input type="text" id="city" name="city" required value="{{ old('city', $show->city) }}">
        @error('city') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="description">Descripción <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <textarea id="description" name="description" rows="5">{{ old('description', $show->description) }}</textarea>
        @error('description') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="ticket_url">Link de entradas <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        <input type="url" id="ticket_url" name="ticket_url" value="{{ old('ticket_url', $show->ticket_url) }}" placeholder="https://...">
        @error('ticket_url') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="flyer">Flyer <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
        @if($show->flyer_url)
            <img src="{{ asset('storage/' . $show->flyer_url) }}" alt="" class="current-image">
        @endif
        <input type="file" id="flyer" name="flyer" accept="image/*">
        <p class="field-hint">Dejalo vacío para mantener el flyer actual.</p>
        @error('flyer') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div style="display:flex; gap:12px;">
        <button type="submit" class="btn btn-accent">Guardar Cambios</button>
        <a href="{{ route('admin.shows.index') }}" class="btn btn-ghost">Cancelar</a>
    </div>
</form>
@endsection
