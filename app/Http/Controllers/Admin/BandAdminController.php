<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Band;
use App\Models\BandPhoto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BandAdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            function ($request, $next) {
                if (!auth()->user()->isAdmin() && !auth()->user()->isEditor()) {
                    abort(403);
                }
                return $next($request);
            },
        ];
    }

    public function index(): View
    {
        $bands = Band::orderBy('name', 'asc')->paginate(15);

        return view('admin.bands.index', ['bands' => $bands]);
    }

    public function create(): View
    {
        return view('admin.bands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:bands,name',
            'genre' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'instagram_url' => 'nullable|url',
            'spotify_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'logo' => 'nullable|image|max:4096',
            'photos.*' => 'nullable|image|max:4096',
            'is_approved' => 'nullable|boolean',
        ]);

        $validated['slug'] = str($validated['name'])->slug();
        $validated['is_approved'] = $request->boolean('is_approved');

        if ($request->hasFile('logo')) {
            $validated['photo_url'] = $request->file('logo')->store('bands', 'public');
        }
        unset($validated['logo'], $validated['photos']);

        $band = Band::create($validated);

        $this->storePhotos($band, $request);

        return redirect()
            ->route('admin.bands.index')
            ->with('success', 'Banda creada correctamente');
    }

    public function edit(Band $band): View
    {
        return view('admin.bands.edit', ['band' => $band]);
    }

    public function update(Request $request, Band $band)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:bands,name,' . $band->id,
            'genre' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'instagram_url' => 'nullable|url',
            'spotify_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'logo' => 'nullable|image|max:4096',
            'photos.*' => 'nullable|image|max:4096',
            'is_approved' => 'nullable|boolean',
        ]);

        $validated['is_approved'] = $request->boolean('is_approved');

        if ($request->hasFile('logo')) {
            if ($band->photo_url) {
                Storage::disk('public')->delete($band->photo_url);
            }
            $validated['photo_url'] = $request->file('logo')->store('bands', 'public');
        }
        unset($validated['logo'], $validated['photos']);

        $band->update($validated);

        $this->storePhotos($band, $request);

        return redirect()
            ->route('admin.bands.index')
            ->with('success', 'Banda actualizada correctamente');
    }

    public function destroy(Band $band)
    {
        if ($band->photo_url) {
            Storage::disk('public')->delete($band->photo_url);
        }
        foreach ($band->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_url);
        }

        $band->delete();

        return redirect()
            ->route('admin.bands.index')
            ->with('success', 'Banda eliminada');
    }

    public function destroyPhoto(Band $band, BandPhoto $photo)
    {
        abort_unless($photo->band_id === $band->id, 404);

        Storage::disk('public')->delete($photo->photo_url);
        $photo->delete();

        return back()->with('success', 'Foto eliminada');
    }

    private function storePhotos(Band $band, Request $request): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $nextOrder = $band->photos()->max('order') + 1;

        foreach ($request->file('photos') as $file) {
            if (! $file) {
                continue;
            }

            BandPhoto::create([
                'band_id' => $band->id,
                'photo_url' => $file->store('bands', 'public'),
                'order' => $nextOrder++,
            ]);
        }
    }
}
