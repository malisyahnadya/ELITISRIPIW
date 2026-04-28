<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class FillMovieImagesFromTmdb extends Command
{
    protected $signature = 'movies:fill-tmdb-images
        {--overwrite : Replace poster_path and banner_path even when they already contain values}
        {--force : Alias for --overwrite}
        {--limit= : Limit how many movies are processed}
        {--language= : TMDB language code, example: en-US or id-ID}
        {--dry-run : Preview matched TMDB data without saving}';

    protected $description = 'Fill movie poster_path and banner_path from TMDB by matching the movie title and release year.';

    public function handle(): int
    {
        $apiKey = trim((string) (config('services.tmdb.api_key') ?: config('services.tmdb.key')));
        $accessToken = trim((string) (config('services.tmdb.access_token') ?: config('services.tmdb.token')));
        $baseUrl = rtrim((string) config('services.tmdb.base_url', 'https://api.themoviedb.org/3'), '/');
        $imageBaseUrl = rtrim((string) config('services.tmdb.image_base_url', 'https://image.tmdb.org/t/p'), '/');
        $language = (string) ($this->option('language') ?: config('services.tmdb.language', 'en-US'));
        $overwrite = (bool) ($this->option('overwrite') || $this->option('force'));
        $limit = $this->option('limit') !== null ? max(1, (int) $this->option('limit')) : null;

        if ($apiKey === '' && $accessToken === '') {
            $this->error('TMDB_API_KEY atau TMDB_ACCESS_TOKEN belum diisi di .env.');
            $this->line('Tambahkan salah satu ke .env:');
            $this->line('TMDB_API_KEY=isi_api_key_tmdb_kamu');
            $this->line('TMDB_ACCESS_TOKEN=isi_read_access_token_tmdb_kamu');

            return self::FAILURE;
        }

        $query = Movie::query()->orderBy('id');

        if (! $overwrite) {
            $query->where(function ($q) {
                $q->whereNull('poster_path')
                    ->orWhere('poster_path', '')
                    ->orWhereNull('banner_path')
                    ->orWhere('banner_path', '');
            });
        }

        if ($limit) {
            $query->limit($limit);
        }

        $movies = $query->get();

        if ($movies->isEmpty()) {
            $this->info('Tidak ada movie yang perlu diisi gambar. Pakai --overwrite kalau mau menimpa gambar lama.');
            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;
        $notFound = 0;

        $this->info('Mulai mencari poster dan banner dari TMDB...');

        foreach ($movies as $movie) {
            $needsPoster = $overwrite || blank($movie->poster_path);
            $needsBanner = $overwrite || blank($movie->banner_path);

            if (! $needsPoster && ! $needsBanner) {
                $skipped++;
                $this->line("Skip: {$movie->title} sudah punya poster dan banner.");
                continue;
            }

            $title = $this->cleanTitle((string) $movie->title);
            $year = $this->extractYear($movie);

            try {
                $result = $this->searchTmdbMovie($baseUrl, $apiKey, $accessToken, $title, $year, $language);

                if (! $result) {
                    $notFound++;
                    $this->warn('Tidak ditemukan di TMDB: ' . $movie->title . ($year ? " ({$year})" : ''));
                    continue;
                }

                $posterPath = $result['poster_path'] ?? null;
                $backdropPath = $result['backdrop_path'] ?? null;
                $posterUrl = $posterPath ? $imageBaseUrl . '/' . config('services.tmdb.poster_size', 'w500') . $posterPath : null;
                $bannerUrl = $backdropPath ? $imageBaseUrl . '/' . config('services.tmdb.banner_size', 'original') . $backdropPath : null;

                $matchedTitle = $result['title'] ?? $result['original_title'] ?? 'TMDB movie';
                $this->line("Matched: {$movie->title} => {$matchedTitle}");

                if ($this->option('dry-run')) {
                    $this->line('  poster: ' . ($posterUrl ?: '-'));
                    $this->line('  banner: ' . ($bannerUrl ?: '-'));
                    continue;
                }

                $dirty = false;

                if ($needsPoster && $posterUrl) {
                    $movie->poster_path = $posterUrl;
                    $dirty = true;
                }

                if ($needsBanner && $bannerUrl) {
                    $movie->banner_path = $bannerUrl;
                    $dirty = true;
                }

                if ($dirty) {
                    $movie->save();
                    $updated++;
                    $this->info("Updated: {$movie->title}");
                } else {
                    $this->warn("TMDB ada, tapi poster/banner kosong: {$movie->title}");
                }

                usleep(180000);
            } catch (Throwable $exception) {
                $this->warn("Error {$movie->title}: {$exception->getMessage()}");
            }
        }

        $this->newLine();

        if ($this->option('dry-run')) {
            $this->info('Dry-run selesai. Tidak ada data database yang diubah.');
        } else {
            $this->info("Selesai. Updated: {$updated}, skipped: {$skipped}, not found: {$notFound}.");
        }

        return self::SUCCESS;
    }

    private function searchTmdbMovie(string $baseUrl, string $apiKey, string $accessToken, string $title, ?int $year, string $language): ?array
    {
        $params = [
            'query' => $title,
            'include_adult' => false,
            'language' => $language,
            'page' => 1,
        ];

        if ($year) {
            $params['year'] = $year;
            $params['primary_release_year'] = $year;
        }

        if ($apiKey !== '') {
            $params['api_key'] = $apiKey;
        }

        $request = Http::timeout(20)->retry(2, 600);

        if ($accessToken !== '') {
            $request = $request->withToken($accessToken);
        }

        $response = $request->get($baseUrl . '/search/movie', $params);

        if (! $response->successful()) {
            $this->warn("Gagal TMDB: {$title} ({$response->status()})");
            return null;
        }

        $results = collect($response->json('results', []));

        if ($results->isEmpty()) {
            return null;
        }

        if ($year) {
            $sameYear = $results->first(function ($item) use ($year) {
                $releaseDate = $item['release_date'] ?? null;

                return is_string($releaseDate) && Str::startsWith($releaseDate, (string) $year);
            });

            if ($sameYear) {
                return $sameYear;
            }
        }

        return $results->first();
    }

    private function cleanTitle(string $title): string
    {
        $clean = preg_replace('/\s*\(\d{4}\)\s*$/', '', trim($title));

        return trim($clean ?: $title);
    }

    private function extractYear(Movie $movie): ?int
    {
        if (! blank($movie->release_year)) {
            return (int) $movie->release_year;
        }

        if (preg_match('/\((\d{4})\)\s*$/', (string) $movie->title, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
