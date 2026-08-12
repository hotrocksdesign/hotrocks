<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reviews = Review::where('published_at', '!=', null)
            ->orderBy('published_at', 'desc');

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $reviews->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('band', function ($bandQuery) use ($search) {
                      $bandQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by band
        if ($request->has('band_id')) {
            $reviews->where('band_id', $request->get('band_id'));
        }

        // Filter by tag
        if ($request->has('tag_id')) {
            $reviews->whereHas('tags', function ($q) {
                $q->where('id', request()->get('tag_id'));
            });
        }

        $reviews = $reviews->paginate(12);

        return view('reviews.index', ['reviews' => $reviews]);
    }

    public function show(Review $review): View
    {
        return view('reviews.show', ['review' => $review]);
    }
}
