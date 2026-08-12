<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Band;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShowAdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            function ($request, $next) {
                if (!auth()->user()->isAdmin()) {
                    abort(403);
                }
                return $next($request);
            },
        ];
    }

    public function create(): View
    {
        $bands = Band::orderBy('name', 'asc')->get();

        return view('admin.shows.create', ['bands' => $bands]);
    }

    public function store(Request $request)
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

        $bandIds = Band::resolveOrCreateMany($validated['band_names'], approved: true);

        if (empty($bandIds)) {
            return back()->withInput()->withErrors(['band_names' => 'Cargá al menos una banda.']);
        }

        unset($validated['band_names']);

        if ($request->hasFile('flyer')) {
            $validated['flyer_url'] = $request->file('flyer')->store('shows', 'public');
        }
        unset($validated['flyer']);

        $validated['user_id'] = auth()->id();
        $validated['status'] = Show::STATUS_APPROVED;

        $show = Show::create($validated);
        $show->bands()->sync($bandIds);

        return redirect()
            ->route('admin.shows.pending')
            ->with('success', 'Show creado y aprobado. Ya está visible en la agenda.');
    }

    public function pending(): View
    {
        $shows = Show::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.shows.pending', ['shows' => $shows]);
    }

    public function approve(Show $show)
    {
        $show->update(['status' => Show::STATUS_APPROVED]);
        $show->bands()->where('is_approved', false)->update(['is_approved' => true]);

        return back()->with('success', 'Show y bandas nuevas aprobados');
    }

    public function reject(Request $request, Show $show)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $show->update([
            'status' => Show::STATUS_REJECTED,
            'rejection_reason' => $request->get('rejection_reason'),
        ]);

        return back()->with('success', 'Show rejected');
    }

    public function destroy(Show $show)
    {
        $show->delete();
        return back()->with('success', 'Show deleted');
    }
}
