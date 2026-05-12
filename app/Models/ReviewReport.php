<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewReport extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'review_id',
        'reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relasi report dimiliki oleh satu user.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi report dimiliki oleh satu review.
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function scopeForReview(Builder $query, int $reviewId): Builder
    {
        return $query->where('review_id', $reviewId);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $searchQuery) use ($term) {
            $searchQuery->where('reason', 'LIKE', '%' . $term . '%')
                ->orWhereHas('user', function (Builder $userQuery) use ($term) {
                    $userQuery->where('name', 'LIKE', '%' . $term . '%')
                        ->orWhere('username', 'LIKE', '%' . $term . '%')
                        ->orWhere('email', 'LIKE', '%' . $term . '%');
                })
                ->orWhereHas('review', function (Builder $reviewQuery) use ($term) {
                    $reviewQuery->where('review_text', 'LIKE', '%' . $term . '%');
                })
                ->orWhereHas('review.user', function (Builder $reviewUserQuery) use ($term) {
                    $reviewUserQuery->where('name', 'LIKE', '%' . $term . '%')
                        ->orWhere('username', 'LIKE', '%' . $term . '%')
                        ->orWhere('email', 'LIKE', '%' . $term . '%');
                })
                ->orWhereHas('review.movie', function (Builder $movieQuery) use ($term) {
                    $movieQuery->where('title', 'LIKE', '%' . $term . '%');
                });
        });
    }

    public static function existsForUserAndReview(int $userId, int $reviewId): bool
    {
        return static::query()
            ->where('user_id', $userId)
            ->where('review_id', $reviewId)
            ->exists();
    }
}
