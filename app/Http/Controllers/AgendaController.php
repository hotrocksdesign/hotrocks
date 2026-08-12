<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        $shows = Show::where('status', 'approved')
            ->orderBy('date', 'asc');

        // Search by band
        if ($request->has('band')) {
            $shows->whereHas('bands', function ($q) {
                $q->where('name', 'like', '%' . request()->get('band') . '%');
            });
        }

        // Search by venue
        if ($request->has('venue')) {
            $shows->where('venue', 'like', '%' . $request->get('venue') . '%');
        }

        // Filter by city
        if ($request->has('city')) {
            $shows->where('city', $request->get('city'));
        }

        $shows = $shows->paginate(12);

        return view('agenda.index', ['shows' => $shows]);
    }

    public function search(Request $request): View
    {
        return $this->index($request);
    }
}
