<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DirectorController as AdminDirectorController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WatchlistController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

// Semua yang butuh LOGIN masuk sini
Route::middleware('auth')->group(function () {
    Route::get('/my-watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/movies/{movie}/ratings', [MovieController::class, 'storeRating'])
        ->name('movies.ratings.store');
    Route::post('/movies/{movie}/reviews', [MovieController::class, 'storeReview'])
        ->name('movies.reviews.store');

    // Kelola Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

    // KHUSUS ADMIN (Satpam Middleware Aktif)
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', function () {
            return view('admin.index'); // Manggil file resources/views/admin/index.blade.php
        })->name('admin.dashboard');

        Route::resource('movies', AdminMovieController::class)
            ->names('admin.movies');

        Route::resource('directors', AdminDirectorController::class)
            ->names('admin.directors');
        
        Route::resource('genres', AdminGenreController::class)
            ->names('admin.genres');

        Route::resource('reviews', AdminReviewController::class)
            ->only(['index', 'destroy'])
            ->names('admin.reviews');

    });
});

require __DIR__ . '/auth.php';
