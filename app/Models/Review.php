<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_id',
        'review_text',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi review dimiliki oleh satu user.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi review dimiliki oleh satu movie.
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    // Relasi like yang masuk ke review ini (1 review bisa punya banyak like).
    public function likes(): HasMany
    {
        return $this->hasMany(ReviewLike::class);
    }

    // Relasi many-to-many untuk daftar user yang me-like review ini.
    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'review_likes')
            ->withPivot('created_at');
    }

    // Scope untuk mengurutkan review dari yang paling baru.
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    // Scope untuk mengambil review berdasarkan movie tertentu.
    public function scopeForMovie(Builder $query, int $movieId): Builder
    {
        return $query->where('movie_id', $movieId);
    }

    // Scope untuk mengambil review berdasarkan user tertentu.
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk menambahkan kolom likes_count ke hasil query review.
    public function scopeWithLikesCount(Builder $query): Builder
    {
        return $query->withCount('likes');
    }

    // Scope untuk mengurutkan review berdasarkan jumlah like (self-contained).
    public function scopeOrderByLikes(Builder $query, string $direction = 'desc'): Builder
    {
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        return $query
            ->withLikesCount()
            ->orderBy('likes_count', $direction);
    }

    // Scope gabungan untuk review populer: like terbanyak lalu terbaru.
    public function scopePopular(Builder $query): Builder
    {
        return $query
            ->orderByLikes('desc')
            ->latestFirst();
    }
}