<?php

namespace App\Console\Commands;

use App\Models\Actor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FillActorImagesFromTmdb extends Command
{
    protected $signature = 'actors:fill-tmdb-images {--overwrite}';

    protected $description = 'Fill actor profile_path from TMDB';

    public function handle(): int
    {
        $apiKey = config('services.tmdb.key');

        if (!$apiKey) {
            $this->error('TMDB_API_KEY belum diisi di .env');
            return self::FAILURE;
        }

        $actors = Actor::query()
            ->when(!$this->option('overwrite'), function ($query) {
                $query->whereNull('profile_path')->orWhere('profile_path', '');
            })
            ->get();

        foreach ($actors as $actor) {
            $response = Http::get(config('services.tmdb.base_url') . '/search/person', [
                'api_key' => $apiKey,
                'query' => $actor->name,
                'language' => config('services.tmdb.language', 'en-US'),
                'include_adult' => false,
            ]);

            if (!$response->successful()) {
                $this->warn("Gagal fetch: {$actor->name}");
                continue;
            }

            $result = $response->json('results.0');

            if (!$result || empty($result['profile_path'])) {
                $this->warn("Foto tidak ditemukan: {$actor->name}");
                continue;
            }

           $actor->photo_path =
    rtrim(config('services.tmdb.image_base_url'), '/') .
    '/w185' .
    $result['profile_path'];
            $actor->save();

            $this->info("Updated: {$actor->name}");
        }

        $this->info('Selesai isi foto actor dari TMDB.');

        return self::SUCCESS;
    }
}