<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Director extends Model
{
    use HasFactory;
    use ResolvesMediaUrls;

    protected $fillable = [
        'name',
        'photo_path',
    ];

    // Pastikan Laravel menganggap created_at dan updated_at sebagai objek Carbon
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi many-to-many dengan Movie
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_directors');
    }

    // Accessor untuk mendapatkan URL foto sutradara
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->photo_path);
    }

    // Scope untuk pencarian sutradara berdasarkan nama
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $term . '%');
    }

    // Scope untuk mengurutkan sutradara berdasarkan nama
    public function scopeSortByName(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('name', $direction);
    }
}