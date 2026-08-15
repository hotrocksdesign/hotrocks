<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BandController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Admin\BandAdminController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\Admin\ShowAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BandProfileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ShowSubmissionController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

Route::get('/noticias', [NewsController::class, 'index'])->name('news.index');
Route::get('/noticias/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('/bands', [BandController::class, 'index'])->name('bands.index');
Route::get('/bands/{band}', [BandController::class, 'show'])->name('bands.show');

Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::post('/agenda/search', [AgendaController::class, 'search'])->name('agenda.search');

// Bands submit their own shows without needing an account — goes to a
// pending queue for admin approval either way, account or not.
Route::get('/shows/submit', [ShowSubmissionController::class, 'create'])->name('shows.submit');
Route::post('/shows/submit', [ShowSubmissionController::class, 'store'])->name('shows.submit.store');

Route::middleware('auth')->group(function () {
    // Band self-service profile (create/edit own band, goes to pending queue)
    Route::get('/mi-banda', [BandProfileController::class, 'edit'])->name('band.profile.edit');
    Route::post('/mi-banda', [BandProfileController::class, 'update'])->name('band.profile.update');
    Route::delete('/mi-banda/photos/{photo}', [BandProfileController::class, 'destroyPhoto'])->name('band.profile.photos.destroy');
});

// Admin routes
Route::middleware('auth')->prefix('admin')->group(function () {
    // Reviews
    Route::resource('reviews', ReviewAdminController::class)->names('admin.reviews')->register();
    Route::delete('/reviews/{review}/photos/{photo}', [ReviewAdminController::class, 'destroyPhoto'])->name('admin.reviews.photos.destroy');

    // News
    Route::resource('news', NewsAdminController::class)->names('admin.news')->register();

    // Bands
    Route::resource('bands', BandAdminController::class)->names('admin.bands')->except(['show'])->register();
    Route::delete('/bands/{band}/photos/{photo}', [BandAdminController::class, 'destroyPhoto'])->name('admin.bands.photos.destroy');

    // Shows (create directly, auto-approved)
    Route::get('/shows', [ShowAdminController::class, 'index'])->name('admin.shows.index');
    Route::get('/shows/create', [ShowAdminController::class, 'create'])->name('admin.shows.create');
    Route::post('/shows', [ShowAdminController::class, 'store'])->name('admin.shows.store');
    Route::get('/shows/{show}/edit', [ShowAdminController::class, 'edit'])->name('admin.shows.edit');
    Route::put('/shows/{show}', [ShowAdminController::class, 'update'])->name('admin.shows.update');

    // Shows approval
    Route::get('/shows/pending', [ShowAdminController::class, 'pending'])->name('admin.shows.pending');
    Route::post('/shows/{show}/approve', [ShowAdminController::class, 'approve'])->name('admin.shows.approve');
    Route::post('/shows/{show}/reject', [ShowAdminController::class, 'reject'])->name('admin.shows.reject');
    Route::delete('/shows/{show}', [ShowAdminController::class, 'destroy'])->name('admin.shows.destroy');

    // Users
    Route::resource('users', UserAdminController::class)->names('admin.users')->except(['show'])->register();
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
