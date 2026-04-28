<?php

namespace App\Console\Commands;

use App\Models\Actor;
use App\Models\Director;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FillAllTmdbImages extends Command
{
    protected $signature = 'tmdb:fill-all-images {--overwrite}';

    protected $description = 'Fill movie, actor, and director images from TMDB';

    public function handle(): int
    {
        $apiKey = env('TMDB_API_KEY');

        if (!$apiKey) {
            $this->error('TMDB_API_KEY belum diisi di .env');
            return self::FAILURE;
        }

        // ================= MOVIES =================
        $this->info('=== MOVIES ===');

        foreach (Movie::all() as $movie) {

            $response = Http::get('https://api.themoviedb.org/3/search/movie', [
                'api_key' => $apiKey,
                'query' => $movie->title,
            ]);

            $result = $response->json('results.0');

            if (!$result) {
                $this->warn("Movie tidak ditemukan: {$movie->title}");
                continue;
            }

            if ($this->option('overwrite') || !$movie->poster_path) {
                $movie->poster_path = 'https://image.tmdb.org/t/p/w500' . $result['poster_path'];
            }

            if ($this->option('overwrite') || !$movie->banner_path) {
                $movie->banner_path = 'https://image.tmdb.org/t/p/original' . $result['backdrop_path'];
            }

            $movie->save();

            $this->info("Movie: {$movie->title}");
        }

        // ================= ACTORS =================
        $this->info('=== ACTORS ===');

        foreach (Actor::all() as $actor) {

            $response = Http::get('https://api.themoviedb.org/3/search/person', [
                'api_key' => $apiKey,
                'query' => $actor->name,
            ]);

            $result = $response->json('results.0');

            if (!$result || empty($result['profile_path'])) {
                $this->warn("Actor tidak ditemukan: {$actor->name}");
                continue;
            }

            $actor->photo_path = 'https://image.tmdb.org/t/p/w185' . $result['profile_path'];
            $actor->save();

            $this->info("Actor: {$actor->name}");
        }

        // ================= DIRECTORS =================
        $this->info('=== DIRECTORS ===');

        foreach (Director::all() as $director) {

            $response = Http::get('https://api.themoviedb.org/3/search/person', [
                'api_key' => $apiKey,
                'query' => $director->name,
            ]);

            $result = $response->json('results.0');

            if (!$result || empty($result['profile_path'])) {
                $this->warn("Director tidak ditemukan: {$director->name}");
                continue;
            }

            $director->photo_path = 'https://image.tmdb.org/t/p/w185' . $result['profile_path'];
            $director->save();

            $this->info("Director: {$director->name}");
        }

        $this->info('SELESAI SEMUA 🔥');

        return self::SUCCESS;
    }
}