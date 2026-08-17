<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\BandPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BandSubmissionController extends Controller
{
    public function create(): View
    {
        return view('bands.submit');
    }

    public function store(Request $request): RedirectResponse
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
        ]);

        if ($request->hasFile('logo')) {
            $validated['photo_url'] = $request->file('logo')->store('bands', 'public');
        }
        unset($validated['logo'], $validated['photos']);

        $validated['slug'] = str($validated['name'])->slug();
        $validated['is_approved'] = false;

        $band = Band::create($validated);

        if ($request->hasFile('photos')) {
            $order = 0;
            foreach ($request->file('photos') as $file) {
                if (! $file) {
                    continue;
                }

                BandPhoto::create([
                    'band_id' => $band->id,
                    'photo_url' => $file->store('bands', 'public'),
                    'order' => $order++,
                ]);
            }
        }

        return redirect()
            ->route('home')
            ->with('success', 'Tu banda fue enviada. Quedará visible en la enciclopedia una vez que la aprobemos.');
    }
}
