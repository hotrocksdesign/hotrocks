<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewPhoto;
use App\Models\Band;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReviewAdminController extends Controller implements HasMiddleware
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
        $reviews = Review::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reviews.index', ['reviews' => $reviews]);
    }

    public function create(): View
    {
        $bands = Band::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('admin.reviews.create', [
            'bands' => $bands,
            'tags' => $tags,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'band_id' => 'nullable|exists:bands,id',
            'venue' => 'required|string|max:255',
            'show_date' => 'required|date',
            'video_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'featured_image' => 'nullable|image|max:4096',
            'photo_credit' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|max:4096',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['published_at'] = now();
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('reviews', 'public');
        }
        unset($validated['photos']);

        $validated['featured_at'] = $validated['is_featured'] ? now() : null;

        $review = Review::create($validated);

        if ($validated['is_featured']) {
            Review::enforceFeaturedCap();
        }

        if ($request->has('tags')) {
            $review->tags()->attach($request->get('tags'));
        }

        $this->storePhotos($review, $request);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review created successfully');
    }

    public function edit(Review $review): View
    {
        $bands = Band::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('admin.reviews.edit', [
            'review' => $review,
            'bands' => $bands,
            'tags' => $tags,
        ]);
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'band_id' => 'nullable|exists:bands,id',
            'venue' => 'required|string|max:255',
            'show_date' => 'required|date',
            'video_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'featured_image' => 'nullable|image|max:4096',
            'photo_credit' => 'nullable|string|max:255',
            'photos.*' => 'nullable|image|max:4096',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            if ($review->featured_image) {
                Storage::disk('public')->delete($review->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('reviews', 'public');
        }
        unset($validated['photos']);

        if ($validated['is_featured'] && ! $review->is_featured) {
            $validated['featured_at'] = now();
        } elseif (! $validated['is_featured']) {
            $validated['featured_at'] = null;
        }

        $review->update($validated);

        if ($validated['is_featured']) {
            Review::enforceFeaturedCap();
        }

        if ($request->has('tags')) {
            $review->tags()->sync($request->get('tags'));
        }

        $this->storePhotos($review, $request);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        if ($review->featured_image) {
            Storage::disk('public')->delete($review->featured_image);
        }
        foreach ($review->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_url);
        }

        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully');
    }

    public function destroyPhoto(Review $review, ReviewPhoto $photo)
    {
        abort_unless($photo->review_id === $review->id, 404);

        Storage::disk('public')->delete($photo->photo_url);
        $photo->delete();

        return back()->with('success', 'Foto eliminada');
    }

    private function storePhotos(Review $review, Request $request): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $nextOrder = $review->photos()->max('order') + 1;

        foreach ($request->file('photos') as $file) {
            if (! $file) {
                continue;
            }

            ReviewPhoto::create([
                'review_id' => $review->id,
                'photo_url' => $file->store('reviews', 'public'),
                'order' => $nextOrder++,
            ]);
        }
    }
}
