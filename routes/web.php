<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BandController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Admin\BandAdminController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\Admin\ShowAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShowSubmissionController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

Route::get('/bands', [BandController::class, 'index'])->name('bands.index');
Route::get('/bands/{band}', [BandController::class, 'show'])->name('bands.show');

Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::post('/agenda/search', [AgendaController::class, 'search'])->name('agenda.search');

// Bands submit their own shows (goes to pending queue)
Route::middleware('auth')->group(function () {
    Route::get('/shows/submit', [ShowSubmissionController::class, 'create'])->name('shows.submit');
    Route::post('/shows/submit', [ShowSubmissionController::class, 'store'])->name('shows.submit.store');
});

// Admin routes
Route::middleware('auth')->prefix('admin')->group(function () {
    // Reviews
    Route::resource('reviews', ReviewAdminController::class)->names('admin.reviews');
    Route::delete('/reviews/{review}/photos/{photo}', [ReviewAdminController::class, 'destroyPhoto'])->name('admin.reviews.photos.destroy');

    // Bands
    Route::resource('bands', BandAdminController::class)->names('admin.bands')->except(['show']);
    Route::delete('/bands/{band}/photos/{photo}', [BandAdminController::class, 'destroyPhoto'])->name('admin.bands.photos.destroy');

    // Shows (create directly, auto-approved)
    Route::get('/shows/create', [ShowAdminController::class, 'create'])->name('admin.shows.create');
    Route::post('/shows', [ShowAdminController::class, 'store'])->name('admin.shows.store');

    // Shows approval
    Route::get('/shows/pending', [ShowAdminController::class, 'pending'])->name('admin.shows.pending');
    Route::post('/shows/{show}/approve', [ShowAdminController::class, 'approve'])->name('admin.shows.approve');
    Route::post('/shows/{show}/reject', [ShowAdminController::class, 'reject'])->name('admin.shows.reject');
    Route::delete('/shows/{show}', [ShowAdminController::class, 'destroy'])->name('admin.shows.destroy');
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
