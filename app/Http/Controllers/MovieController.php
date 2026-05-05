<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Movie;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth; 

class MovieController extends Controller
{
    // Menangani daftar film, detail film, serta interaksi user (rating, review, watchlist).

    /**
     * Menampilkan halaman pencarian dengan data film untuk kartu beranda.
     * Memanfaatkan scope pada model Movie agar query tetap ringan untuk listing.
     */
    public function index()
    {
        // Ambil data minimal untuk card beranda + statistik rating agar tidak memicu query tambahan di view.
        $movies = Movie::query()
            // scope dari model Movie untuk memilih kolom yang dibutuhkan untuk card di home, biasanya id, title, release_year, poster_path, dll.
            ->forHomeCard()
            // Mengurutkan berdasarkan tahun rilis terbaru terlebih dahulu agar
            ->orderByDesc('release_year')
            // scope dari model Movie untuk menambahkan informasi apakah film ada di watchlist user yang sedang login.
            ->withWatchlistForUser(Auth::id())
            // Urutkan judul agar urutan di tahun rilis yang sama konsisten dan mudah dicari.
            ->orderBy('title')
            // paginate agar data tidak terlalu banyak sekaligus, dan otomatis menghandle query string ?page=2, ?page=3, dll.
            ->paginate(12);

        // diarahkan ke view search.blade.php dengan membawa data film yang sudah diproses.
        return view('search', [
            'movies' => $movies,
            'genres' => collect(),
            'releaseYears' => collect(),
            'q' => '',
            'selectedGenreId' => null,
            'selectedReleaseYear' => null,
            'selectedMinScore' => null,
        ]);
    }

    /**
     * Menampilkan detail film beserta relasi penting dan status interaksi user.
     * Memuat relasi Movie, serta mengecek model Rating/Review/Watchlist milik user aktif.
     */
    public function show(Movie $movie)
    {
        // Eager load relasi utama untuk halaman detail film.
        $movie->load([
            // 
            'directors:id,name,photo_path',
            'actors:id,name,photo_path',
            'genres:id,name',
            'reviews' => function ($query) {
                // Siapkan data review lengkap (user, jumlah like, status like user) untuk ditampilkan di view detail film.
                $query->with(['user:id,name,username,profile_photo'])
                    ->withLikesCount()
                    ->when(auth()->check(), function ($query) {
                        // Tandai apakah review sudah di-like oleh user yang sedang login.
                        $query->withExists([
                            'likes as liked_by_current_user' => function ($likeQuery) {
                                $likeQuery->where('user_id', auth()->id());
                            },
                        ]);
                    })
                    ->latestFirst();
            },
        ]);

        // Preload agregat rating agar accessor average_score memakai nilai yang sudah tersedia.
        $movie->loadCount('ratings');
        $movie->loadAvg('ratings', 'score');

        $userRating = null;
        $userReview = null;
        $userWatchlist = null;

        if (auth()->check()) {
            $userId = (int) auth()->id();

            // Ambil interaksi user saat ini terhadap film (rating/review/watchlist) bila sudah login.
            $userRating = Rating::query()
                ->forMovie((int) $movie->id)
                ->forUser($userId)
                ->first();

            $userReview = Review::query()
                ->forMovie((int) $movie->id)
                ->forUser($userId)
                ->first();

            $userWatchlist = Watchlist::query()
                ->forMovie((int) $movie->id)
                ->forUser($userId)
                ->first();
        }

        return view('movies.movie', compact('movie', 'userRating', 'userReview', 'userWatchlist'));
    }

    /**
     * Menyimpan atau memperbarui rating user untuk film tertentu.
     * Menggunakan model Rating dengan updateOrCreate agar 1 user hanya punya 1 rating per film.
     */
    public function storeRating(StoreRatingRequest $request, Movie $movie)
    {
        $validated = $request->validated();

        Rating::updateOrCreate(
            [
                'user_id' => (int) $request->user()->id,
                'movie_id' => (int) $movie->id,
            ],
            ['score' => (int) $validated['score']]
        );

        return back()->with('success', 'Rating berhasil disimpan.');
    }

    /**
     * Menyimpan atau memperbarui review user untuk film tertentu.
     * Menggunakan model Review dengan updateOrCreate agar 1 user hanya punya 1 review per film.
     */
    public function storeReview(StoreReviewRequest $request, Movie $movie)
    {
        $validated = $request->validated();

        Review::updateOrCreate(
            [
                'user_id' => (int) $request->user()->id,
                'movie_id' => (int) $movie->id,
            ],
            ['review_text' => $validated['review_text']]
        );

        return back()->with('success', 'Review berhasil disimpan.');
    }

        /**
     * Menghapus rating user yang sedang login untuk film tertentu.
     * Hanya rating milik user login yang boleh dihapus.
     */
    public function destroyRating(Movie $movie)
    {
        // Pastikan user sudah login.
        $userId = (int) auth()->id();

        // Cari rating berdasarkan movie_id dan user_id.
        $rating = Rating::query()
            ->forMovie((int) $movie->id)
            ->forUser($userId)
            ->first();

        // Kalau rating tidak ditemukan, kembalikan ke halaman sebelumnya.
        if (! $rating) {
            return back()->with('success', 'Rating tidak ditemukan.');
        }

        // Hapus rating dari database.
        $rating->delete();

        // Kembali ke halaman sebelumnya dengan pesan sukses.
        return back()->with('success', 'Rating berhasil dihapus.');
    }

    /**
     * Menghapus review user yang sedang login untuk film tertentu.
     * Hanya review milik user login yang boleh dihapus.
     */
    public function destroyReview(Movie $movie)
    {
        // Pastikan user sudah login.
        $userId = (int) auth()->id();

        // Cari review berdasarkan movie_id dan user_id.
        $review = Review::query()
            ->forMovie((int) $movie->id)
            ->forUser($userId)
            ->first();

        // Kalau review tidak ditemukan, kembalikan ke halaman sebelumnya.
        if (! $review) {
            return back()->with('success', 'Review tidak ditemukan.');
        }

        // Kalau relasi likes tidak memakai cascade delete di migration,
        // hapus like review terlebih dahulu supaya tidak error foreign key.
        $review->likes()->delete();

        // Hapus review dari database.
        $review->delete();

        // Kembali ke halaman sebelumnya dengan pesan sukses.
        return back()->with('success', 'Review berhasil dihapus.');
    }
    
}
