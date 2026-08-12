<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\View\View;

class BandController extends Controller
{
    public function index(): View
    {
        $bands = Band::where('is_approved', true)
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('bands.index', ['bands' => $bands]);
    }

    public function show(Band $band): View
    {
        $reviews = $band->reviews()
            ->where('published_at', '!=', null)
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        $shows = $band->shows()
            ->where('status', 'approved')
            ->orderBy('date', 'desc')
            ->paginate(6);

        return view('bands.show', [
            'band' => $band,
            'reviews' => $reviews,
            'shows' => $shows,
        ]);
    }
}
