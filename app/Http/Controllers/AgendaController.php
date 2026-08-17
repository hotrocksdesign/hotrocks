<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        $applyFilters = function (Builder $query) use ($request) {
            if ($request->filled('band')) {
                $query->whereHas('bands', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->get('band') . '%');
                });
            }

            if ($request->filled('venue')) {
                $query->where('venue', 'like', '%' . $request->get('venue') . '%');
            }

            if ($request->filled('city')) {
                $query->where('city', $request->get('city'));
            }
        };

        $upcomingShows = Show::where('status', 'approved')
            ->where('date', '>=', now())
            ->tap($applyFilters)
            ->orderBy('date', 'asc')
            ->paginate(12, ['*'], 'page')
            ->withQueryString();

        $pastShows = Show::where('status', 'approved')
            ->where('date', '<', now())
            ->tap($applyFilters)
            ->orderBy('date', 'desc')
            ->paginate(12, ['*'], 'past_page')
            ->withQueryString();

        return view('agenda.index', [
            'upcomingShows' => $upcomingShows,
            'pastShows' => $pastShows,
        ]);
    }

    public function search(Request $request): View
    {
        return $this->index($request);
    }
}
