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
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ticket_url' => 'nullable|url',
            'flyer' => 'nullable|image|max:4096',
        ]);

        $bandIds = Band::resolveOrCreateMany($validated['band_names'], approved: false);

        if (empty($bandIds)) {
            return back()->withInput()->withErrors(['band_names' => 'Cargá al menos una banda.']);
        }

        unset($validated['band_names']);

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
