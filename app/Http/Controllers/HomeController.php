<?php // Pembuka file PHP

namespace App\Http\Controllers; // Menentukan namespace controller ini agar dikenali sebagai bagian dari folder App\Http\Controllers

use App\Models\Movie; // Mengimpor model Movie untuk mengambil data film dari tabel movies
use App\Models\Review; // Mengimpor model Review untuk mengambil data ulasan/review dari tabel reviews
use App\Models\Watchlist; // Mengimpor model Watchlist untuk mengambil data watchlist user
use Illuminate\Support\Facades\Auth; // Mengimpor facade Auth untuk mengecek user login dan mengambil ID user yang sedang login

class HomeController extends Controller // Membuat class HomeController yang mewarisi class dasar Controller Laravel
{
    // Method ini digunakan untuk menampilkan halaman utama/home
    // Data yang dikirim ke home meliputi hero movies, rekomendasi movie, watchlist user, dan review terbaru
    public function index() // Method index akan dipanggil saat route home diakses, misalnya Route::get('/', [HomeController::class, 'index'])
    {
        $currentMonth = (int) now()->month; // Mengambil bulan saat ini dalam bentuk angka, lalu dipastikan menjadi integer, contoh: Mei = 5
        $currentYear = (int) now()->year; // Mengambil tahun saat ini dalam bentuk angka, lalu dipastikan menjadi integer, contoh: 2026
        $monthName = strtoupper(now()->format('F')); // Mengambil nama bulan saat ini dalam bahasa Inggris lalu dibuat huruf besar, contoh: MAY

        $heroMovies = Movie::query() // Memulai query ke model Movie untuk mengambil film yang akan ditampilkan di hero carousel
            ->select(['id', 'title', 'description', 'release_year', 'duration_minutes', 'poster_path', 'banner_path', 'trailer_url']) // Memilih hanya kolom yang dibutuhkan agar query lebih ringan
            ->withRatingsStats() // Memanggil scope/method model untuk menambahkan statistik rating umum, biasanya ratings_count dan ratings_avg_score
            ->withCount([ // Menambahkan perhitungan jumlah rating khusus untuk bulan dan tahun saat ini
                'ratings as month_ratings_count' => fn ($query) => $query // Menghitung relasi ratings dan menyimpan hasilnya sebagai month_ratings_count
                    ->whereMonth('created_at', $currentMonth) // Hanya hitung rating yang dibuat pada bulan saat ini
                    ->whereYear('created_at', $currentYear), // Hanya hitung rating yang dibuat pada tahun saat ini
            ])
            ->withAvg([ // Menambahkan perhitungan rata-rata rating khusus untuk bulan dan tahun saat ini
                'ratings as month_avg_score' => fn ($query) => $query // Mengambil rata-rata relasi ratings dan menyimpan hasilnya sebagai month_avg_score
                    ->whereMonth('created_at', $currentMonth) // Hanya ambil rating yang dibuat pada bulan saat ini
                    ->whereYear('created_at', $currentYear), // Hanya ambil rating yang dibuat pada tahun saat ini
            ], 'score') // Kolom yang dihitung rata-ratanya adalah kolom score pada tabel ratings
            ->whereHas('ratings', fn ($query) => $query // Memastikan film yang dipilih benar-benar punya rating pada bulan dan tahun saat ini
                ->whereMonth('created_at', $currentMonth) // Rating harus dibuat pada bulan saat ini
                ->whereYear('created_at', $currentYear)) // Rating harus dibuat pada tahun saat ini
            ->orderByDesc('month_avg_score') // Mengurutkan film berdasarkan rata-rata rating bulanan tertinggi
            ->orderByDesc('month_ratings_count') // Jika rata-rata rating sama, urutkan berdasarkan jumlah rating bulanan terbanyak
            ->take(5) // Ambil maksimal 5 film untuk hero carousel
            ->get(); // Jalankan query dan ambil hasilnya sebagai Collection

        if ($heroMovies->isEmpty()) { // Jika tidak ada film yang punya rating pada bulan ini, gunakan fallback ke rating umum
            $heroMovies = Movie::query() // Memulai query baru untuk mengambil film hero berdasarkan statistik rating umum
                ->select(['id', 'title', 'description', 'release_year', 'duration_minutes', 'poster_path', 'banner_path', 'trailer_url']) // Memilih hanya kolom yang dibutuhkan agar query tetap ringan
                ->withRatingsStats() // Menambahkan statistik rating umum dari model, biasanya ratings_count dan ratings_avg_score
                ->orderByDesc('ratings_avg_score') // Mengurutkan berdasarkan rata-rata rating umum tertinggi
                ->orderByDesc('ratings_count') // Jika rata-rata sama, urutkan berdasarkan jumlah rating terbanyak
                ->take(5) // Ambil maksimal 5 film sebagai hero carousel
                ->get(); // Jalankan query dan ambil hasilnya
        }

        $recommendedMovies = Movie::query() // Memulai query untuk mengambil daftar film rekomendasi di bagian Movie List
            ->forHomeCard() // Memanggil scope/method model yang kemungkinan memilih data yang dibutuhkan untuk kartu film di home
            ->withCount('reviews') // Menghitung jumlah review untuk setiap film dan menyimpannya sebagai reviews_count
            ->withWatchlistForUser(Auth::id()) // Menambahkan status watchlist berdasarkan user login; jika guest, Auth::id() bernilai null
            ->orderByRaw('(ratings_count + reviews_count) DESC') // Mengurutkan film berdasarkan kombinasi jumlah rating dan jumlah review tertinggi
            ->orderByDesc('ratings_avg_score') // Jika engagement sama, urutkan berdasarkan rata-rata rating tertinggi
            ->take(12) // Ambil maksimal 12 film untuk ditampilkan di home
            ->get(); // Jalankan query dan ambil hasilnya sebagai Collection

        $hasMoreRecommended = Movie::count() > 12; // Mengecek apakah jumlah semua film lebih dari 12 untuk menentukan apakah tombol See More perlu ditampilkan

        $userWatchlist = collect(); // Membuat collection kosong sebagai default jika user belum login
        $hasMoreWatchlist = false; // Default tombol See More watchlist tidak ditampilkan jika user belum login

        if (Auth::check()) { // Mengecek apakah user sedang login
            $userWatchlist = Watchlist::query() // Memulai query ke tabel watchlist
                ->forUser((int) Auth::id()) // Mengambil watchlist hanya milik user yang sedang login
                ->withMovieCard() // Memuat data movie yang dibutuhkan untuk kartu film, kemungkinan relasi movie + scope forHomeCard
                ->latest('updated_at') // Mengurutkan watchlist dari yang paling baru diupdate
                ->take(10) // Ambil maksimal 10 item watchlist untuk ditampilkan di home
                ->get(); // Jalankan query dan ambil hasilnya sebagai Collection

            $hasMoreWatchlist = Watchlist::query() // Memulai query untuk mengecek total watchlist user
                ->forUser((int) Auth::id()) // Hitung hanya watchlist milik user yang sedang login
                ->count() > 10; // Jika jumlahnya lebih dari 10, maka tombol See More watchlist akan ditampilkan
        }

        $latestReviews = Review::query() // Memulai query untuk mengambil review yang akan ditampilkan di section other reviews
            ->with([ // Eager loading relasi agar tidak terjadi N+1 query saat Blade memanggil data user dan movie
                'user:id,name,username,profile_photo', // Ambil relasi user dengan kolom tertentu saja agar query lebih ringan
                'movie:id,title,release_year,poster_path,duration_minutes', // Ambil relasi movie dengan kolom tertentu saja agar query lebih ringan
            ])
            ->withLikesCount() // Memanggil scope/method model untuk menghitung jumlah like review, biasanya menghasilkan likes_count
            ->orderByDesc('likes_count') // Mengurutkan review berdasarkan jumlah like terbanyak
            ->latestFirst() // Mengurutkan review terbaru lebih dulu, biasanya scope ini memakai created_at DESC
            ->take(5) // Ambil maksimal 5 review untuk ditampilkan di home
            ->get(); // Jalankan query dan ambil hasilnya sebagai Collection

        return view('home', compact( // Menampilkan view resources/views/home.blade.php dan mengirim data ke Blade
            'heroMovies', // Mengirim data film hero carousel ke Blade sebagai variabel $heroMovies
            'monthName', // Mengirim nama bulan ke Blade sebagai variabel $monthName
            'recommendedMovies', // Mengirim daftar film rekomendasi ke Blade sebagai variabel $recommendedMovies
            'userWatchlist', // Mengirim data watchlist user ke Blade sebagai variabel $userWatchlist
            'latestReviews', // Mengirim data review terbaru/terpopuler ke Blade sebagai variabel $latestReviews
            'hasMoreRecommended', // Mengirim boolean untuk menentukan apakah tombol See More movie list tampil
            'hasMoreWatchlist' // Mengirim boolean untuk menentukan apakah tombol See More watchlist tampil
        ));
    }
}
