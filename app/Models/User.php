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

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->profile_photo);
    }
    
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where('username', 'LIKE', '%' . $term . '%')
            ->orWhere('email', 'LIKE', '%' . $term . '%');
    }

    public function scopeSortByUsername(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('username', $direction);
    }

    public function scopeSortByEmail(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('email', $direction);
    }

    public function scopeSortByCreatedAt(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('created_at', $direction);
    }

    public function hasLikedReview(Review $review): bool
    {
        return $this->likedReviews()->where('review_id', $review->id)->exists();
    }

    public function hasInWatchlist(Movie $movie): bool
    {
        return $this->watchedMovies()->where('movie_id', $movie->id)->exists();
    }

    public function hasRatedMovie(Movie $movie): bool
    {
        return $this->ratedMovies()->where('movie_id', $movie->id)->exists();
    }

    public function hasReviewedMovie(Movie $movie): bool
    {
        return $this->reviewedMovies()->where('movie_id', $movie->id)->exists();
    }

    public function scopeWithReviewCount(Builder $query): Builder
    {
        return $query->withCount('reviews');
    }

    public function scopeWithRatingCount(Builder $query): Builder
    {
        return $query->withCount('ratings');
    }

    public function scopeWithWatchlistCount(Builder $query): Builder
    {
        return $query->withCount('watchedMovies');
    }

    public function scopeWithLikedReviewsCount(Builder $query): Builder
    {
        return $query->withCount('likedReviews');
    }
}
