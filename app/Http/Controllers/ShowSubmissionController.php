<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\Show;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowSubmissionController extends Controller
{
    public function create(): View
    {
        $bands = Band::orderBy('name', 'asc')->get();

        return view('shows.submit', ['bands' => $bands]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'band_names' => 'required|array|min:1',
            'band_names.*' => 'nullable|string|max:255',
            'band_logo' => 'nullable|image|max:4096',
            'band_instagram_url' => 'nullable|url',
            'band_spotify_url' => 'nullable|url',
            'band_youtube_url' => 'nullable|url',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ticket_url' => 'nullable|url',
            'flyer' => 'nullable|image|max:4096',
        ]);

        // Logo/social links only ever apply to the first band name typed,
        // and only if that band doesn't already exist — see
        // Band::findOrCreateWithDetails(). Extra bands on the bill (added
        // via "+ Agregar otra banda") are resolved by name only, same as
        // the admin form.
        $bandDetails = array_filter([
            'instagram_url' => $validated['band_instagram_url'] ?? null,
            'spotify_url' => $validated['band_spotify_url'] ?? null,
            'youtube_url' => $validated['band_youtube_url'] ?? null,
        ]);
        if ($request->hasFile('band_logo')) {
            $bandDetails['photo_url'] = $request->file('band_logo')->store('bands', 'public');
        }

        $bandIds = [];
        foreach ($validated['band_names'] as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }
            $band = Band::findOrCreateWithDetails($name, $bandIds ? [] : $bandDetails, approved: false);
            $bandIds[] = $band->id;
        }
        $bandIds = array_unique($bandIds);

        if (empty($bandIds)) {
            return back()->withInput()->withErrors(['band_names' => 'Cargá al menos una banda.']);
        }

        unset($validated['band_names'], $validated['band_logo'], $validated['band_instagram_url'], $validated['band_spotify_url'], $validated['band_youtube_url']);

        if ($request->hasFile('flyer')) {
            $validated['flyer_url'] = $request->file('flyer')->store('shows', 'public');
        }
        unset($validated['flyer']);

        $validated['user_id'] = auth()->id();
        $validated['status'] = Show::STATUS_PENDING;

        $show = Show::create($validated);
        $show->bands()->sync($bandIds);

        return redirect()
            ->route('home')
            ->with('success', 'Tu show fue enviado. Quedará visible en la agenda una vez que lo aprobemos.');
    }
}
