<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class Rating extends Model
{
    use HasFactory;

    public const MIN_SCORE = 1;
    public const MAX_SCORE = 5;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'movie_id',
        'score',
    ];

    // Pastikan Laravel menganggap score sebagai integer, serta created_at sebagai objek Carbon
    protected $casts = [
        'score' => 'integer',
        'created_at' => 'datetime',
    ];

    // Mutator untuk memastikan skor selalu antara MIN_SCORE dan MAX_SCORE
    public function setScoreAttribute(mixed $value): void
    {
        $score = (int) $value;

        if ($score < self::MIN_SCORE || $score > self::MAX_SCORE) {
            throw ValidationException::withMessages([
                'score' => sprintf('Score harus antara %d sampai %d.', self::MIN_SCORE, self::MAX_SCORE),
            ]);
        }

        $this->attributes['score'] = $score;
    }

    // Relasi dengan User dan Movie
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Movie
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    // Scope untuk mendapatkan rating berdasarkan movie tertentu
    public function scopeForMovie(Builder $query, int $movieId): Builder
    {
        return $query->where('movie_id', $movieId);
    }

    // Scope untuk mendapatkan rating berdasarkan user tertentu
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Metode untuk menghitung rata-rata skor untuk sebuah film
    public static function averageScoreForMovie(int $movieId): float
    {
        return (float) (static::query()->forMovie($movieId)->avg('score') ?? 0);
    }

}