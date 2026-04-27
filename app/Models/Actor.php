<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Actor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_actors')
            ->withPivot('role_name');
    }

    public function getRoleNameForMovie(Movie $movie): ?string
    {
        $pivot = $this->movies()
            ->where('movie_id', $movie->id)
            ->first();

        return $pivot ? $pivot->pivot->role_name : null;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this -> hasMediaUrl ($this->photo_path);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $term . '%');
    }

    public function scopeSortByName(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderBy('name', $direction);
    }

}