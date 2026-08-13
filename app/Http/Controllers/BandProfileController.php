<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\BandPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BandProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            function ($request, $next) {
                if (!auth()->user()->isBand()) {
                    abort(403);
                }
                return $next($request);
            },
        ];
    }

    public function edit(): View
    {
        return view('bands.profile', ['band' => auth()->user()->band]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $band = $user->band;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('bands', 'name')->ignore($band?->id)],
            'genre' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'instagram_url' => 'nullable|url',
            'spotify_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'logo' => 'nullable|image|max:4096',
            'photos.*' => 'nullable|image|max:4096',
        ]);

        $validated['is_approved'] = false;

        if ($request->hasFile('logo')) {
            if ($band?->photo_url) {
                Storage::disk('public')->delete($band->photo_url);
            }
            $validated['photo_url'] = $request->file('logo')->store('bands', 'public');
        }
        unset($validated['logo'], $validated['photos']);

        if ($band) {
            $band->update($validated);
        } else {
            $validated['slug'] = str($validated['name'])->slug();
            $band = Band::create($validated);
            $user->update(['band_id' => $band->id]);
        }

        if ($request->hasFile('photos')) {
            $nextOrder = $band->photos()->max('order') + 1;

            foreach ($request->file('photos') as $file) {
                if (!$file) {
                    continue;
                }

                BandPhoto::create([
                    'band_id' => $band->id,
                    'photo_url' => $file->store('bands', 'public'),
                    'order' => $nextOrder++,
                ]);
            }
        }

        return redirect()
            ->route('band.profile.edit')
            ->with('success', $band->wasRecentlyCreated
                ? 'Perfil de banda creado. Queda pendiente de aprobación.'
                : 'Cambios guardados. Quedan pendientes de aprobación.');
    }

    public function destroyPhoto(BandPhoto $photo): RedirectResponse
    {
        abort_unless(auth()->user()->band_id === $photo->band_id, 403);

        Storage::disk('public')->delete($photo->photo_url);
        $photo->delete();

        return back()->with('success', 'Foto eliminada');
    }
}
