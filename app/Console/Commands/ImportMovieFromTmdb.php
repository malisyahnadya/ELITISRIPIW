<?php

namespace App\Console\Commands;

use App\Models\Actor;
use App\Models\Director;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportMovieFromTmdb extends Command
{
    protected $signature = 'tmdb:import-movie
                            {query : Movie title or TMDB ID}
                            {--id : Treat query as TMDB ID}
                            {--overwrite : Overwrite existing data}
                            {--cast-limit=12 : Number of cast members to import}';

    protected $description = 'Import movie details, poster, banner, trailer, genres, actors, and directors from TMDB';

    public function handle(): int
    {
        $apiKey = config('services.tmdb.key') ?: env('TMDB_API_KEY');

        if (!$apiKey) {
            $this->error('TMDB_API_KEY belum diisi di .env');
            return self::FAILURE;
        }

        $query = (string) $this->argument('query');
        $isId = $this->option('id') || ctype_digit($query);

        $tmdbId = $isId ? (int) $query : $this->searchMovieId($query);

        if (!$tmdbId) {
            $this->error("Movie tidak ditemukan di TMDB: {$query}");
            return self::FAILURE;
        }

        $details = $this->getMovieDetails($tmdbId);

        if (!$details) {
            $this->error("Gagal mengambil detail movie dari TMDB.");
            return self::FAILURE;
        }

        $movie = $this->saveMovie($details);

        $this->syncGenres($movie, $details);
        $this->syncDirectors($movie, $details);
        $this->syncActors($movie, $details);

        $this->info("Selesai import: {$movie->title}");

        return self::SUCCESS;
    }

    private function searchMovieId(string $title): ?int
    {
        $response = $this->tmdbGet('/search/movie', [
            'query' => $title,
            'include_adult' => false,
        ]);

        if (!$response || !$response->successful()) {
            return null;
        }

        return $response->json('results.0.id');
    }

    private function getMovieDetails(int $tmdbId): ?array
    {
        $response = $this->tmdbGet('/movie/' . $tmdbId, [
            'append_to_response' => 'credits,videos',
        ]);

        if (!$response || !$response->successful()) {
            return null;
        }

        return $response->json();
    }

    private function saveMovie(array $details): Movie
    {
        $tmdbId = (int) $details['id'];
        $title = $details['title'] ?? $details['original_title'] ?? 'Untitled Movie';

        $movie = Movie::where('tmdb_id', $tmdbId)->first();

        if (!$movie) {
            $movie = Movie::where('title', $title)->first();
        }

        if (!$movie) {
            $movie = new Movie();
        }

        $movie->tmdb_id = $tmdbId;

        $this->fillIfAllowed($movie, 'title', $title);
        $this->fillIfAllowed($movie, 'description', $details['overview'] ?? null);
        $this->fillIfAllowed($movie, 'release_year', $this->releaseYear($details['release_date'] ?? null));
        $this->fillIfAllowed($movie, 'duration_minutes', $details['runtime'] ?? null);
        $this->fillIfAllowed($movie, 'poster_path', $this->imageUrl($details['poster_path'] ?? null, 'w500'));
        $this->fillIfAllowed($movie, 'banner_path', $this->imageUrl($details['backdrop_path'] ?? null, 'original'));
        $this->fillIfAllowed($movie, 'trailer_url', $this->trailerUrl($details['videos']['results'] ?? []));

        $movie->save();

        $this->info("Movie saved: {$movie->title}");

        return $movie;
    }

    private function syncGenres(Movie $movie, array $details): void
    {
        $genreIds = [];

        foreach (($details['genres'] ?? []) as $genreData) {
            if (empty($genreData['name'])) {
                continue;
            }

            $genre = Genre::firstOrCreate([
                'name' => $genreData['name'],
            ]);

            $genreIds[] = $genre->id;
        }

        $movie->genres()->sync($genreIds);

        $this->info('Genres synced: ' . count($genreIds));
    }

    private function syncDirectors(Movie $movie, array $details): void
    {
        $directorIds = [];

        $crew = collect($details['credits']['crew'] ?? [])
            ->where('job', 'Director')
            ->values();

        foreach ($crew as $person) {
            if (empty($person['name'])) {
                continue;
            }

            $director = Director::where('tmdb_id', $person['id'] ?? null)->first();

            if (!$director) {
                $director = Director::where('name', $person['name'])->first();
            }

            if (!$director) {
                $director = new Director();
            }

            $director->tmdb_id = $person['id'] ?? null;
            $director->name = $person['name'];

            if ($this->option('overwrite') || blank($director->photo_path)) {
                $director->photo_path = $this->imageUrl($person['profile_path'] ?? null, 'w185');
            }

            $director->save();

            $directorIds[] = $director->id;
        }

        $movie->directors()->sync($directorIds);

        $this->info('Directors synced: ' . count($directorIds));
    }

    private function syncActors(Movie $movie, array $details): void
    {
        $actorSync = [];
        $limit = (int) $this->option('cast-limit');

        $cast = collect($details['credits']['cast'] ?? [])
            ->take($limit)
            ->values();

        foreach ($cast as $person) {
            if (empty($person['name'])) {
                continue;
            }

            $actor = Actor::where('tmdb_id', $person['id'] ?? null)->first();

            if (!$actor) {
                $actor = Actor::where('name', $person['name'])->first();
            }

            if (!$actor) {
                $actor = new Actor();
            }

            $actor->tmdb_id = $person['id'] ?? null;
            $actor->name = $person['name'];

            if ($this->option('overwrite') || blank($actor->photo_path)) {
                $actor->photo_path = $this->imageUrl($person['profile_path'] ?? null, 'w185');
            }

            $actor->save();

            $actorSync[$actor->id] = [
                'role_name' => $person['character'] ?? null,
            ];
        }

        $movie->actors()->sync($actorSync);

        $this->info('Actors synced: ' . count($actorSync));
    }

    private function fillIfAllowed(Movie $movie, string $field, mixed $value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if ($this->option('overwrite') || blank($movie->{$field})) {
            $movie->{$field} = $value;
        }
    }

    private function trailerUrl(array $videos): ?string
    {
        $collection = collect($videos);

        $video = $collection
            ->where('site', 'YouTube')
            ->where('type', 'Trailer')
            ->where('official', true)
            ->first();

        if (!$video) {
            $video = $collection
                ->where('site', 'YouTube')
                ->where('type', 'Trailer')
                ->first();
        }

        if (!$video) {
            $video = $collection
                ->where('site', 'YouTube')
                ->first();
        }

        if (!$video || empty($video['key'])) {
            return null;
        }

        return 'https://www.youtube.com/watch?v=' . $video['key'];
    }

    private function imageUrl(?string $path, string $size): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return 'https://image.tmdb.org/t/p/' . trim($size, '/') . $path;
    }

    private function releaseYear(?string $releaseDate): ?int
    {
        if (!$releaseDate || strlen($releaseDate) < 4) {
            return null;
        }

        return (int) substr($releaseDate, 0, 4);
    }

    private function tmdbGet(string $path, array $params = [])
    {
        return Http::get(
            rtrim(config('services.tmdb.base_url', 'https://api.themoviedb.org/3'), '/') . $path,
            array_merge([
                'api_key' => config('services.tmdb.key') ?: env('TMDB_API_KEY'),
                'language' => config('services.tmdb.language', 'en-US'),
            ], $params)
        );
    }
}