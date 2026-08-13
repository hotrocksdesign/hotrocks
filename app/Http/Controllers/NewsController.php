<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::where('published_at', '!=', null)
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('news.index', ['newsItems' => $news]);
    }

    public function show(News $news): View
    {
        abort_unless($news->published_at, 404);

        return view('news.show', ['news' => $news]);
    }
}
