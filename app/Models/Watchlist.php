<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    use HasFactory;

    public const STATUS_PLAN_TO_WATCH = 'plan_to_watch';
    public const STATUS_WATCHING = 'watching';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'movie_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForMovie(Builder $query, int $movieId): Builder
    {
        return $query->where('movie_id', $movieId);
    }

    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->whereHas('movie', function (Builder $q) use ($term) {
            $q->where('title', 'LIKE', '%' . $term . '%');
        });
    }
}