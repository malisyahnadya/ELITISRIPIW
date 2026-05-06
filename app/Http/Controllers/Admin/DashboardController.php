<?php

// Namespace untuk controller admin.
namespace App\Http\Controllers\Admin;

// Base controller untuk semua controller.
use App\Http\Controllers\Controller;
// Model untuk aktor film.
use App\Models\Actor;
// Model untuk sutradara film.
use App\Models\Director;
// Model untuk genre film.
use App\Models\Genre;
// Model untuk film.
use App\Models\Movie;
// Model untuk rating film.
use App\Models\Rating;
// Model untuk review film.
use App\Models\Review;
// Model untuk user aplikasi.
use App\Models\User;
// Facade untuk query builder advanced dan raw queries.
use Illuminate\Support\Facades\DB;
// Type hint untuk return view.
use Illuminate\View\View;

// Controller untuk menampilkan dashboard admin dengan statistik sistem.
class DashboardController extends Controller
{
    // Menampilkan dashboard admin dengan berbagai statistik dan analytics.
    public function index(): View
    {
        // Hitung total jumlah film di database.
        $movieCount = Movie::count();
        // Hitung total jumlah user terdaftar.
        $userCount = User::count();
        // Hitung total jumlah review yang dibuat user.
        $reviewCount = Review::count();
        // Hitung total jumlah rating yang diberikan user.
        $ratingCount = Rating::count();

        // Array statistik utama untuk dashboard display.
        $stats = [
            // Total jumlah film.
            'movies' => $movieCount,
            // Total jumlah user.
            'users' => $userCount,
            // Total jumlah review.
            'reviews' => $reviewCount,
            // Total jumlah rating.
            'ratings' => $ratingCount,
            // Total jumlah aktor.
            'actors' => Actor::count(),
            // Total jumlah genre.
            'genres' => Genre::count(),
            // Total jumlah sutradara.
            'directors' => Director::count(),
            // Rata-rata skor rating semua film.
            'avg_rating' => Rating::avg('score') ?? 0,
            // Total jumlah item dalam watchlist semua user.
            'watchlists' => DB::table('watchlists')->count(),
            // Jumlah user dengan role admin.
            'admin_users' => User::where('role', 'admin')->count(),
            // Rata-rata jumlah review per user (total review / total user).
            'avg_reviews_per_user' => $userCount > 0 ? $reviewCount / $userCount : 0,
        ];

        // Ambil 6 film terbaru dengan genre dan statistik rating.
        $latestMovies = Movie::query()
            // Preload genre untuk setiap film (hanya id dan name).
            ->with(['genres:id,name'])
            // Preload statistik rating (rata-rata dan jumlah).
            ->withRatingsStats()
            // Urutkan berdasarkan created_at terbaru.
            ->latest()
            // Batasi hanya 6 item.
            ->take(6)
            // Eksekusi query.
            ->get();

        // Ambil 5 genre dengan jumlah film terbanyak.
        $genreLeaders = Genre::query()
            // Hitung jumlah film untuk setiap genre.
            ->withCount('movies')
            // Urutkan berdasarkan jumlah film descending.
            ->orderByDesc('movies_count')
            // Urutkan secundary berdasarkan nama genre ascending.
            ->orderBy('name')
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Ambil 5 aktor dengan jumlah film terbanyak.
        $actorLeaders = Actor::query()
            // Hitung jumlah film untuk setiap aktor.
            ->withCount('movies')
            // Urutkan berdasarkan jumlah film descending.
            ->orderByDesc('movies_count')
            // Urutkan secondary berdasarkan nama aktor ascending.
            ->orderBy('name')
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Ambil 5 sutradara dengan jumlah film terbanyak.
        $directorLeaders = Director::query()
            // Hitung jumlah film untuk setiap sutradara.
            ->withCount('movies')
            // Urutkan berdasarkan jumlah film descending.
            ->orderByDesc('movies_count')
            // Urutkan secondary berdasarkan nama sutradara ascending.
            ->orderBy('name')
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Ambil 8 user terbaru dengan jumlah review dan rating mereka.
        $users = User::query()
            // Hitung jumlah review dan rating untuk setiap user.
            ->withCount(['reviews', 'ratings'])
            // Urutkan berdasarkan created_at terbaru.
            ->latest()
            // Batasi 8 item.
            ->take(8)
            // Eksekusi query.
            ->get();

        // Ambil 5 review terbaru dengan data user dan film serta jumlah like.
        $reviews = Review::query()
            // Preload user (hanya id, name, username) dan movie (hanya id, title).
            ->with(['user:id,name,username', 'movie:id,title'])
            // Hitung jumlah like untuk setiap review.
            ->withCount('likes')
            // Urutkan berdasarkan created_at terbaru.
            ->latest()
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Ambil 5 film dengan rating rata-rata tertinggi beserta jumlah ratingnya.
        $topRatedMovies = DB::table('movies')
            // Join dengan tabel ratings untuk mengakses skor rating.
            ->leftJoin('ratings', 'movies.id', '=', 'ratings.movie_id')
            // Pilih kolom yang diperlukan.
            ->select(
                // ID film.
                'movies.id',
                // Judul film.
                'movies.title',
                // Tahun rilis film.
                'movies.release_year',
                // Hitung rata-rata skor rating, default 0 jika tidak ada rating.
                DB::raw('COALESCE(AVG(ratings.score), 0) as avg_rating'),
                // Hitung jumlah rating untuk film tersebut.
                DB::raw('COUNT(ratings.id) as ratings_count')
            )
            // Group by id, title, release_year untuk agregasi.
            ->groupBy('movies.id', 'movies.title', 'movies.release_year')
            // Urutkan berdasarkan rating rata-rata descending.
            ->orderByDesc('avg_rating')
            // Urutkan secondary berdasarkan jumlah rating descending.
            ->orderByDesc('ratings_count')
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Ambil 5 film dengan jumlah review terbanyak.
        $mostReviewedMovies = DB::table('movies')
            // Join dengan tabel reviews untuk mengakses data review.
            ->leftJoin('reviews', 'movies.id', '=', 'reviews.movie_id')
            // Pilih kolom yang diperlukan.
            ->select(
                // ID film.
                'movies.id',
                // Judul film.
                'movies.title',
                // Hitung jumlah review untuk film tersebut.
                DB::raw('COUNT(reviews.id) as reviews_count')
            )
            // Group by id dan title untuk agregasi.
            ->groupBy('movies.id', 'movies.title')
            // Urutkan berdasarkan jumlah review descending.
            ->orderByDesc('reviews_count')
            // Urutkan secondary berdasarkan judul film ascending.
            ->orderBy('movies.title')
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Ambil 5 film yang paling banyak ditambahkan ke watchlist.
        $mostWatchlistedMovies = DB::table('movies')
            // Join dengan tabel watchlists untuk mengakses data watchlist.
            ->leftJoin('watchlists', 'movies.id', '=', 'watchlists.movie_id')
            // Pilih kolom yang diperlukan.
            ->select(
                // ID film.
                'movies.id',
                // Judul film.
                'movies.title',
                // Hitung jumlah watchlist item untuk film tersebut.
                DB::raw('COUNT(watchlists.id) as watchlists_count')
            )
            // Group by id dan title untuk agregasi.
            ->groupBy('movies.id', 'movies.title')
            // Urutkan berdasarkan jumlah watchlist descending.
            ->orderByDesc('watchlists_count')
            // Urutkan secondary berdasarkan judul film ascending.
            ->orderBy('movies.title')
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Ambil 5 review terbaru dengan data user dan film.
        $latestReviews = Review::query()
            // Preload user (hanya id, name, username) dan movie (hanya id, title).
            ->with(['user:id,name,username', 'movie:id,title'])
            // Urutkan berdasarkan created_at terbaru.
            ->latest()
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Ambil data aktivitas review per bulan untuk 5 bulan terakhir (untuk chart).
        $reviewActivity = Review::query()
            // Format created_at menjadi format tahun-bulan (YYYY-MM).
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            // Hitung jumlah review per bulan.
            ->selectRaw('COUNT(*) as total')
            // Filter hanya review yang punya created_at.
            ->whereNotNull('created_at')
            // Filter hanya review dari 5 bulan terakhir ke hari pertama bulan tersebut.
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            // Group by bulan untuk agregasi.
            ->groupBy('month')
            // Urutkan berdasarkan bulan ascending (dari bulan lama ke baru).
            ->orderBy('month')
            // Eksekusi query.
            ->get();

        // Ambil distribusi rating dari 1-5 (berapa banyak rating untuk setiap skor).
        $ratingDistribution = Rating::query()
            // Pilih kolom score.
            ->select('score')
            // Hitung jumlah rating untuk setiap skor.
            ->selectRaw('COUNT(*) as total')
            // Group by score untuk agregasi.
            ->groupBy('score')
            // Urutkan berdasarkan score descending (dari 5 ke 1).
            ->orderByDesc('score')
            // Eksekusi query.
            ->get();

        // Ambil film trending berdasarkan aktivitas (review + rating + watchlist).
        $trendingMovies = Movie::query()
            // Pilih kolom dasar film.
            ->select('movies.id', 'movies.title', 'movies.release_year')
            // Subquery: hitung jumlah review untuk setiap film.
            ->selectSub(function ($query) {
                $query->from('reviews')
                    // Hitung review.
                    ->selectRaw('COUNT(*)')
                    // Filter review yang match dengan movie_id.
                    ->whereColumn('reviews.movie_id', 'movies.id');
            }, 'reviews_count')
            // Subquery: hitung jumlah rating untuk setiap film.
            ->selectSub(function ($query) {
                $query->from('ratings')
                    // Hitung rating.
                    ->selectRaw('COUNT(*)')
                    // Filter rating yang match dengan movie_id.
                    ->whereColumn('ratings.movie_id', 'movies.id');
            }, 'ratings_count')
            // Subquery: hitung jumlah watchlist untuk setiap film.
            ->selectSub(function ($query) {
                $query->from('watchlists')
                    // Hitung watchlist.
                    ->selectRaw('COUNT(*)')
                    // Filter watchlist yang match dengan movie_id.
                    ->whereColumn('watchlists.movie_id', 'movies.id');
            }, 'watchlists_count')
            // Eksekusi query dasar.
            ->get()
            // Map setiap film untuk hitung trend score.
            ->map(function ($movie) {
                // Hitung trend score: review weight 3, rating weight 2, watchlist weight 1.
                $movie->trend_score =
                    // Review diberi weight 3 (paling penting).
                    ((int) $movie->reviews_count * 3) +
                    // Rating diberi weight 2 (cukup penting).
                    ((int) $movie->ratings_count * 2) +
                    // Watchlist diberi weight 1 (standar).
                    ((int) $movie->watchlists_count);

                return $movie;
            })
            // Urutkan berdasarkan trend score descending.
            ->sortByDesc('trend_score')
            // Batasi 5 item.
            ->take(5)
            // Reindex array agar urutan dimulai dari 0.
            ->values();

        // Ambil 5 film yang paling baru diimport dari TMDB.
        $recentTmdbImports = Movie::query()
            // Filter hanya film yang punya tmdb_id (sudah diimport).
            ->whereNotNull('tmdb_id')
            // Urutkan berdasarkan created_at terbaru.
            ->latest()
            // Batasi 5 item.
            ->take(5)
            // Pilih hanya kolom yang diperlukan untuk efficiency.
            ->get(['id', 'title', 'release_year', 'tmdb_id', 'created_at']);

        // Array untuk tracking kelengkapan data film (untuk data quality check).
        $movieCompleteness = [
            // Jumlah film tanpa poster.
            'missing_poster' => Movie::whereNull('poster_path')->orWhere('poster_path', '')->count(),
            // Jumlah film tanpa banner.
            'missing_banner' => Movie::whereNull('banner_path')->orWhere('banner_path', '')->count(),
            // Jumlah film tanpa trailer URL.
            'missing_trailer' => Movie::whereNull('trailer_url')->orWhere('trailer_url', '')->count(),
            // Jumlah film tanpa aktor yang dipilih.
            'without_actors' => Movie::doesntHave('actors')->count(),
            // Jumlah film tanpa sutradara yang dipilih.
            'without_directors' => Movie::doesntHave('directors')->count(),
            // Jumlah film tanpa genre yang dipilih.
            'without_genres' => Movie::doesntHave('genres')->count(),
        ];

        // Ambil 5 user paling aktif berdasarkan aktivitas mereka.
        $activeUsers = User::query()
            // Hitung jumlah review dan rating untuk setiap user.
            ->withCount(['reviews', 'ratings'])
            // Eksekusi query.
            ->get()
            // Map setiap user untuk hitung activity score.
            ->map(function ($user) {
                // Hitung activity score: review weight 3, rating weight 2.
                $user->activity_score =
                    // Review diberi weight 3 (aktivitas lebih penting).
                    ((int) $user->reviews_count * 3) +
                    // Rating diberi weight 2.
                    ((int) $user->ratings_count * 2);

                return $user;
            })
            // Urutkan berdasarkan activity score descending.
            ->sortByDesc('activity_score')
            // Batasi 5 item.
            ->take(5)
            // Reindex array agar urutan dimulai dari 0.
            ->values();

        // Ambil review yang membutuhkan perhatian moderasi (review panjang >= 300 karakter).
        $reviewAlerts = Review::query()
            // Preload user (hanya id, name, username) dan movie (hanya id, title).
            ->with(['user:id,name,username', 'movie:id,title'])
            // Hitung jumlah like untuk setiap review.
            ->withCount('likes')
            // Filter review yang panjang text-nya >= 300 karakter (mungkin butuh review moderasi).
            ->whereRaw('CHAR_LENGTH(review_text) >= 300')
            // Urutkan berdasarkan created_at terbaru.
            ->latest()
            // Batasi 5 item.
            ->take(5)
            // Eksekusi query.
            ->get();

        // Array untuk checklist kualitas data dan sistem.
        $dataQuality = [
            // Cek apakah TMDB API key sudah dikonfigurasi dan filled.
            'tmdb_api_connected' => filled(config('services.tmdb.key') ?: env('TMDB_API_KEY')),
            // Cek apakah folder storage sudah di-link ke public.
            'storage_linked' => file_exists(public_path('storage')),
            // Total jumlah film di database.
            'movies_total' => $movieCount,
            // Jumlah film yang punya poster (tidak null dan tidak kosong).
            'movies_with_poster' => Movie::whereNotNull('poster_path')->where('poster_path', '!=', '')->count(),
            // Jumlah film yang punya trailer URL (tidak null dan tidak kosong).
            'movies_with_trailer' => Movie::whereNotNull('trailer_url')->where('trailer_url', '!=', '')->count(),
        ];

        // Kirim semua data ke view admin dashboard.
        return view('admin.index', compact(
            // Statistik utama sistem (total count).
            'stats',
            // 6 film terbaru.
            'latestMovies',
            // 5 genre dengan film terbanyak.
            'genreLeaders',
            // 5 aktor dengan film terbanyak.
            'actorLeaders',
            // 5 sutradara dengan film terbanyak.
            'directorLeaders',
            // 8 user terbaru.
            'users',
            // 5 review terbaru.
            'reviews',
            // 5 film dengan rating tertinggi.
            'topRatedMovies',
            // 5 film dengan review terbanyak.
            'mostReviewedMovies',
            // 5 film dengan watchlist terbanyak.
            'mostWatchlistedMovies',
            // 5 review terbaru (duplikat untuk legacy?).
            'latestReviews',
            // Aktivitas review per bulan untuk 5 bulan terakhir (untuk chart).
            'reviewActivity',
            // Distribusi rating dari 1-5 (untuk chart).
            'ratingDistribution',
            // 5 film trending berdasarkan aktivitas.
            'trendingMovies',
            // 5 film terbaru dari import TMDB.
            'recentTmdbImports',
            // Status kelengkapan data film (missing poster, banner, etc).
            'movieCompleteness',
            // 5 user paling aktif berdasarkan activity score.
            'activeUsers',
            // 5 review yang panjang (potential moderasi alert).
            'reviewAlerts',
            // Checklist kualitas sistem dan data.
            'dataQuality'
        ));
    }
}