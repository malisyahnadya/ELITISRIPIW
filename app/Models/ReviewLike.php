<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewLike extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'review_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    // Scope untuk mendapatkan like berdasarkan review tertentu
    public function scopeForReview(Builder $query, int $reviewId): Builder
    {
        return $query->where('review_id', $reviewId);
    }

    // Scope untuk mendapatkan like berdasarkan user tertentu
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Metode untuk mengecek apakah like sudah ada untuk user dan review tertentu
    public static function existsForUserAndReview(int $userId, int $reviewId): bool
    {
        return static::query()
            ->where('user_id', $userId)
            ->where('review_id', $reviewId)
            ->exists();
    }
}