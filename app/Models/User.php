<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
}
