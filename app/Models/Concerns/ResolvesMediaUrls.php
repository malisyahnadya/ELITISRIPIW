<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait ResolvesMediaUrls
{
    protected function resolveMediaUrl(?string $path, ?string $fallback = null): ?string
    {
        if (empty($path)) {
            return $fallback;
        }

        $path = trim($path);

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '//')) {
            return 'https:' . $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $relativePath = substr($path, strlen('storage/'));

            if (Storage::disk('public')->exists($relativePath)) {
                return asset($path);
            }

            return $fallback;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return $fallback;
    }
}
