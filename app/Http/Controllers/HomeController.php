<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\News;
use App\Models\Show;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredReview = Review::where('published_at', '!=', null)
            ->where('is_featured', true)
            ->first();

        $latestReviews = Review::where('published_at', '!=', null)
            ->when($featuredReview, fn ($query) => $query->where('id', '!=', $featuredReview->id))
            ->orderBy('show_date', 'desc')
            ->limit(6)
            ->get();

        $upcomingShows = Show::where('status', 'approved')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get();

        $latestNews = News::where('published_at', '!=', null)
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return view('home.index', [
            'featuredReview' => $featuredReview,
            'latestReviews' => $latestReviews,
            'upcomingShows' => $upcomingShows,
            'latestNews' => $latestNews,
        ]);
    }
}
