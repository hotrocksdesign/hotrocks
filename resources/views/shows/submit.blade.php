@extends('layout')

@section('title', 'Cargar Show')

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
        <span class="kicker">Sumá tu show</span>
        <h1>Cargar Show</h1>
        <p class="sub">Contanos cuándo y dónde tocás. No hace falta crear una cuenta — tu show queda pendiente de aprobación y, una vez revisado, aparece en la agenda.</p>

        <form method="POST" action="{{ route('shows.submit.store') }}" enctype="multipart/form-data">
            @csrf

            @include('partials.band-name-inputs', ['bands' => $bands])

            <div class="field" id="newBandFields" style="display:none;">
                <label>Datos de tu banda <span style="color:var(--ink-faint); font-weight:400;">(solo si es nueva — si ya existe, no hace falta completar esto)</span></label>

                <div class="field" style="margin-top:10px;">
                    <label for="band_logo">Logo</label>
                    <input type="file" id="band_logo" name="band_logo" accept="image/*">
                    @error('band_logo') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="band_instagram_url">Instagram</label>
                    <input type="url" id="band_instagram_url" name="band_instagram_url" value="{{ old('band_instagram_url') }}" placeholder="https://instagram.com/...">
                    @error('band_instagram_url') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="band_spotify_url">Spotify</label>
                    <input type="url" id="band_spotify_url" name="band_spotify_url" value="{{ old('band_spotify_url') }}" placeholder="https://open.spotify.com/...">
                    @error('band_spotify_url') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="band_youtube_url">YouTube</label>
                    <input type="url" id="band_youtube_url" name="band_youtube_url" value="{{ old('band_youtube_url') }}" placeholder="https://youtube.com/...">
                    @error('band_youtube_url') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="field">
                <label for="date">Fecha y hora</label>
                <input type="datetime-local" id="date" name="date" required value="{{ old('date') }}">
                @error('date') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="venue">Venue/Lugar</label>
                <input type="text" id="venue" name="venue" required value="{{ old('venue') }}">
                @error('venue') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="city">Ciudad</label>
                <input type="text" id="city" name="city" required value="{{ old('city') }}">
                @error('city') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="description">Descripción <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="ticket_url">Link de entradas <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                <input type="url" id="ticket_url" name="ticket_url" value="{{ old('ticket_url') }}" placeholder="https://...">
                @error('ticket_url') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="flyer">Flyer <span style="color:var(--ink-faint); font-weight:400;">(opcional)</span></label>
                <input type="file" id="flyer" name="flyer" accept="image/*">
                @error('flyer') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-accent btn-block">Enviar para aprobación</button>
        </form>
    </div>
</div>

<script>
    (function () {
        var newBandFields = document.getElementById('newBandFields');
        var firstBandInput = document.querySelector('#bandNameRows .band-name-row:first-child input[name="band_names[]"]');
        if (!newBandFields || !firstBandInput) return;

        var existingNames = Array.from(document.querySelectorAll('#existing-bands-list option'))
            .map(function (o) { return o.value.trim().toLowerCase(); });

        function updateVisibility() {
            var typed = firstBandInput.value.trim().toLowerCase();
            var isExisting = typed !== '' && existingNames.indexOf(typed) !== -1;
            newBandFields.style.display = (typed !== '' && !isExisting) ? 'block' : 'none';
        }

        firstBandInput.addEventListener('input', updateVisibility);
        updateVisibility();
    })();
</script>
@endsection
