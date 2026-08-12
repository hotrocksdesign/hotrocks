{{-- Expects $bands (for the datalist suggestions). Field name: band_names[] --}}
<div class="field">
    <label>Banda(s) *</label>
    <div id="bandNameRows">
        @php $oldNames = old('band_names', ['']); @endphp
        @foreach($oldNames as $i => $name)
            <div class="band-name-row">
                <input type="text" name="band_names[]" list="existing-bands-list" value="{{ $name }}" placeholder="Escribí el nombre de la banda..." {{ $i === 0 ? 'required' : '' }}>
                @if($i > 0)
                    <button type="button" class="btn-remove-row" aria-label="Quitar">&times;</button>
                @endif
            </div>
        @endforeach
    </div>
    <button type="button" id="addBandRow" class="btn btn-sm btn-ghost" style="margin-top:8px;">+ Agregar otra banda</button>
    <datalist id="existing-bands-list">
        @foreach($bands as $band)
            <option value="{{ $band->name }}">
        @endforeach
    </datalist>
    <p class="field-hint">Si escribís un nombre que ya existe, se selecciona esa banda. Si no existe, se crea una nueva y queda pendiente de aprobación junto con la fecha.</p>
    @error('band_names') <span class="field-error">{{ $message }}</span> @enderror
</div>

<style>
    .band-name-row { display: flex; gap: 8px; margin-bottom: 8px; }
    .band-name-row input { flex-grow: 1; }
    .btn-remove-row {
        flex-shrink: 0;
        width: 44px;
        border: 1px solid var(--line);
        background: var(--surface);
        border-radius: var(--radius-sm);
        color: var(--ink-soft);
        font-size: 1.2rem;
        cursor: pointer;
    }
    .btn-remove-row:hover { border-color: var(--accent); color: var(--accent); }
</style>

<script>
    (function () {
        var rows = document.getElementById('bandNameRows');
        var addBtn = document.getElementById('addBandRow');

        function makeRow() {
            var row = document.createElement('div');
            row.className = 'band-name-row';
            row.innerHTML = '<input type="text" name="band_names[]" list="existing-bands-list" placeholder="Escribí el nombre de la banda...">' +
                '<button type="button" class="btn-remove-row" aria-label="Quitar">&times;</button>';
            row.querySelector('.btn-remove-row').addEventListener('click', function () {
                row.remove();
            });
            return row;
        }

        addBtn.addEventListener('click', function () {
            rows.appendChild(makeRow());
        });

        rows.querySelectorAll('.btn-remove-row').forEach(function (btn) {
            btn.addEventListener('click', function () {
                btn.closest('.band-name-row').remove();
            });
        });
    })();
</script>
