<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NewsAdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            function ($request, $next) {
                if (!auth()->user()->isAdmin() && !auth()->user()->isEditor()) {
                    abort(403);
                }
                return $next($request);
            },
        ];
    }

    public function index(): View
    {
        $newsItems = News::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.news.index', ['newsItems' => $newsItems]);
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:4096',
            'published' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['published_at'] = $request->boolean('published') ? now() : null;
        unset($validated['published']);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('news', 'public');
        }

        News::create($validated);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Noticia creada');
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', ['news' => $news]);
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:4096',
            'published' => 'nullable|boolean',
        ]);

        $validated['published_at'] = $request->boolean('published') ? ($news->published_at ?? now()) : null;
        unset($validated['published']);

        if ($request->hasFile('featured_image')) {
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('news', 'public');
        }

        $news->update($validated);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Noticia actualizada');
    }

    public function destroy(News $news)
    {
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Noticia eliminada');
    }
}
