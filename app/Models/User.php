<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\ResolvesMediaUrls;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use ResolvesMediaUrls;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profile_photo',
        'bio',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Metode untuk memeriksa apakah user memiliki peran admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    public function reviewLikes(): HasMany
    {
        return $this->hasMany(ReviewLike::class);
    }

    public function watchedMovies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'watchlists')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function ratedMovies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'ratings')
            ->withPivot('score', 'created_at');
    }

    public function reviewedMovies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'reviews')
            ->withPivot('review_text', 'created_at', 'updated_at');
    }

    public function likedReviews(): BelongsToMany
    {
        return $this->belongsToMany(Review::class, 'review_likes')
            ->withPivot('created_at');
    }

    // Scope untuk mendapatkan daftar watchlist user dengan informasi film dan statusnya
    public function profileWatchList()
    {
        return $this->watchedMovies()
            ->forProfileCard()
            ->withPivot('status')
            ->latest('watchlists.created_at');
    }

    // Relasi khusus buat nampilin history review di halaman profil
    public function profileReviewedMovies()
    {
        return $this->reviewedMovies()
            ->forProfileCard()
            ->withPivot('review_text', 'reviews.created_at', 'reviews.updated_at')
            ->latest('reviews.created_at');
    }

    // Relasi khusus buat nampilin history rating di halaman profil
    public function profileRatedMovies()
    {
        return $this->ratedMovies()
            ->forProfileCard()
            ->withPivot('score', 'ratings.created_at')
            ->latest('ratings.created_at');
    }

    // Accessor untuk mendapatkan URL lengkap dari profile photo
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->profile_photo);
    }
    
   
    // Scope untuk mendapatkan statistik profil seperti jumlah review, rating, dll.
    public function scopeWithProfileStats(Builder $query): Builder
    {
        return $query->withCount('reviews', 'ratings', 'watchedMovies', 'likedReviews');
    }

    // Scope untuk pencarian berdasarkan name, username, atau email
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $searchQuery) use ($term) {
            $searchQuery->where('name', 'LIKE', '%' . $term . '%')
                ->orWhere('username', 'LIKE', '%' . $term . '%')
                ->orWhere('email', 'LIKE', '%' . $term . '%');
        });
    }

    // Scope untuk mengurutkan berdasarkan username
    public function scopeSortByUsername(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('username', $direction);
    }

    // Scope untuk mengurutkan berdasarkan email
    public function scopeSortByEmail(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('email', $direction);
    }

    // Scope untuk mengurutkan berdasarkan tanggal pembuatan
    public function scopeSortByCreatedAt(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('created_at', $direction);
    }

    // Scope untuk mengecek apakah user telah menyukai sebuah ulasan
    public function hasLikedReview(Review $review): bool
    {
        return $this->likedReviews()->where('review_id', $review->id)->exists();
    }

    // Metode untuk mengecek apakah user memiliki film tertentu di watchlist
    public function hasInWatchlist(Movie $movie): bool
    {
        return $this->watchedMovies()->where('movie_id', $movie->id)->exists();
    }

    //    Metode untuk mengecek apakah user telah memberikan rating pada sebuah film
    public function hasRatedMovie(Movie $movie): bool
    {
        return $this->ratedMovies()->where('movie_id', $movie->id)->exists();
    }

    // Metode untuk mengecek apakah user telah menulis review untuk sebuah film
    public function hasReviewedMovie(Movie $movie): bool
    {
        return $this->reviewedMovies()->where('movie_id', $movie->id)->exists();
    }

    // Scope untuk menghitung jumlah ulasan yang ditulis oleh user
    public function scopeWithReviewCount(Builder $query): Builder
    {
        return $query->withCount('reviews');
    }

    // Scope untuk menghitung jumlah rating yang diberikan oleh user
    public function scopeWithRatingCount(Builder $query): Builder
    {
        return $query->withCount('ratings');
    }

    // Scope untuk menghitung jumlah film yang ada di watchlist user
    public function scopeWithWatchlistCount(Builder $query): Builder
    {
        return $query->withCount('watchedMovies');
    }

    // Scope untuk menghitung jumlah review yang disukai oleh user
    public function scopeWithLikedReviewsCount(Builder $query): Builder
    {
        return $query->withCount('likedReviews');
    }
}
