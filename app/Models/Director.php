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

    // Nama tabel yang digunakan model ini
    protected $table = 'directors';

    // Tabel directors kamu tidak memiliki kolom created_at dan updated_at,
    // jadi timestamps harus dimatikan agar Laravel tidak mencoba mengisi updated_at.
    public $timestamps = false;

    protected $fillable = [
        'tmdb_id',
        'name',
        'photo_path',
    ];

    // Pastikan Laravel menganggap created_at dan updated_at sebagai objek Carbon
    // Catatan: ini aman dibiarkan, tetapi karena $timestamps = false,
    // Laravel tidak akan otomatis menulis created_at / updated_at saat insert/update.
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
    // Mendukung:
    // 1. Link TMDB / link internet, contoh: https://image.tmdb.org/...
    // 2. File lokal dari storage, contoh: directors/photos/nama.jpg
    public function getPhotoUrlAttribute(): ?string
    {
        $path = $this->photo_path;

        if (!$path) {
            return null;
        }

        // Jika photo_path sudah berupa URL penuh dari TMDB atau internet,
        // langsung gunakan URL tersebut.
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Jika bukan URL, anggap sebagai file lokal di storage/app/public
        return $this->resolveMediaUrl($path);
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