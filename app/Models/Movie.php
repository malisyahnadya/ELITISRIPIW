<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'release_year',
        'duration_minutes',
        'poster_path',
        'banner_path',
        'trailer_url',
    ];

    protected $casts = [
        'release_year' => 'integer',
        'duration_minutes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'movie_actors')
            ->withPivot('role_name');
    }

    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(Director::class, 'movie_directors');
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

    // Accessor untuk mendapatkan durasi dalam format jam dan menit
    public function getDurationFormattedAttribute(): string
    {
        $minutes = (int) ($this->duration_minutes ?? 0);

        if ($minutes <= 0) {
            return '0 min';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours <= 0) {
            return sprintf('%d min', $remainingMinutes);
        }

        if ($remainingMinutes <= 0) {
            return sprintf('%dh', $hours);
        }

        return sprintf('%dh %dm', $hours, $remainingMinutes);
    }

    // Scope untuk menghitung rata-rata rating dan jumlah rating
    public function scopeWithRatingsStats(Builder $query): Builder
    {
        return $query->withCount('ratings')
            ->withAvg('ratings', 'score');
    }

    // Scope untuk pencarian berdasarkan judul
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where('title', 'like', "%{$term}%");
    }

    // Scope untuk mengambil data lengkap dengan relasi yang diperlukan untuk halaman detail film
    public function scopeWithDetailData(Builder $query): Builder
    {
        return $query
            ->with([
                'directors:id,name',
                'genres:id,name',
                'actors:id,name',
            ])
            ->withRatingsStats();
    }

    // Scope untuk mengambil data yang diperlukan untuk ditampilkan di card pada halaman utama
    public function scopeForHomeCard(Builder $query): Builder
    {
        return $query
            ->select([
                'id',
                'title',
                'duration_minutes',
                'poster_path',
                'release_year',
            ])
            ->withRatingsStats();
    }

    // Scope untuk filter berdasarkan genre, tahun rilis, dan skor minimum
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['genre_id'])) {
            $query->whereHas('genres', function (Builder $q) use ($filters) {
                $q->where('genres.id', $filters['genre_id']);
            });
        }

        if (!empty($filters['release_year'])) {
            $query->where('release_year', $filters['release_year']);
        }

        if (isset($filters['min_score']) && is_numeric($filters['min_score'])) {
            $minScore = (float) $filters['min_score'];

            $query->whereIn('movies.id', function ($subQuery) use ($minScore) {
                $subQuery->select('movie_id')
                    ->from('ratings')
                    ->groupBy('movie_id')
                    ->havingRaw('AVG(score) >= ?', [$minScore]);
            });
        }

        return $query;
    }

    // Accessor untuk mendapatkan judul lengkap dengan tahun rilis, misalnya "Inception (2010)"
    public function getTitleWithYearAttribute(): string
    {
        $year = $this->release_year ? " ({$this->release_year})" : '';
        return $this->title . $year;
    }

    // Accessor untuk mendapatkan URL poster, memeriksa apakah path sudah berupa URL atau perlu di-resolve
    public function getPosterUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->poster_path);
    }

    // Accessor untuk mendapatkan URL banner, menggunakan helper yang sama dengan poster
    public function getBannerUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->banner_path);
    }

    // Accessor untuk mendapatkan URL embed trailer dari YouTube
    public function getTrailerEmbedUrlAttribute(): ?string
    {
        if (empty($this->trailer_url)) {
            return null;
        }

        $url = trim($this->trailer_url);

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $url;
    }

    // Helper method untuk mengonversi path media menjadi URL yang dapat diakses
    protected function resolveMediaUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        return Storage::url($path);
    }
}
