<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Director;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $sort = $request->string('sort', 'asc')->toString();

        $movies = Movie::query()
            ->with(['genres:id,name', 'directors:id,name'])
            ->withCount('reviews')
            ->withRatingsStats()
            ->search($search)
            ->sortByTitle($sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.movies.index', compact('movies', 'search', 'sort'));
    }

    public function create(): View
    {
        return view('admin.movies.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateMovie($request);

        $movie = Movie::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'release_year' => $validated['release_year'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'trailer_url' => $validated['trailer_url'] ?? null,
            'poster_path' => $this->mediaPathFromRequest($request, 'poster', 'poster_url', 'movies/posters'),
            'banner_path' => $this->mediaPathFromRequest($request, 'banner', 'banner_url', 'movies/banners'),
        ]);

        $this->syncRelations($movie, $validated);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie berhasil ditambahkan.');
    }

    public function show(Movie $movie): RedirectResponse
    {
        return redirect()->route('admin.movies.edit', $movie);
    }

    public function edit(Movie $movie): View
    {
        $movie->load(['genres:id,name', 'directors:id,name', 'actors:id,name']);

        $selectedActors = $movie->actors->mapWithKeys(fn ($actor) => [
            $actor->id => $actor->pivot->role_name,
        ]);

        return view('admin.movies.edit', array_merge(
            $this->formOptions(),
            compact('movie', 'selectedActors')
        ));
    }

    public function update(Request $request, Movie $movie): RedirectResponse
    {
        $validated = $this->validateMovie($request, $movie);

        $movie->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'release_year' => $validated['release_year'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'trailer_url' => $validated['trailer_url'] ?? null,
            'poster_path' => $this->mediaPathFromRequest($request, 'poster', 'poster_url', 'movies/posters', $movie->poster_path),
            'banner_path' => $this->mediaPathFromRequest($request, 'banner', 'banner_url', 'movies/banners', $movie->banner_path),
        ]);

        $this->syncRelations($movie, $validated);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie berhasil diperbarui.');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $this->deletePublicFile($movie->poster_path);
        $this->deletePublicFile($movie->banner_path);

        $movie->genres()->detach();
        $movie->directors()->detach();
        $movie->actors()->detach();
        $movie->delete();

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie berhasil dihapus.');
    }

    private function formOptions(): array
    {
        return [
            'genres' => Genre::orderBy('name')->get(),
            'actors' => Actor::orderBy('name')->get(),
            'directors' => Director::orderBy('name')->get(),
        ];
    }

    private function validateMovie(Request $request, ?Movie $movie = null): array
    {
        $movieId = $movie?->id;

        return $request->validate([
            'title' => ['required', 'string', 'max:150', Rule::unique('movies', 'title')->ignore($movieId)],
            'description' => ['nullable', 'string'],
            'release_year' => ['nullable', 'integer', 'min:1888', 'max:' . (date('Y') + 2)],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'trailer_url' => ['nullable', 'url', 'max:2048'],
            'poster_url' => ['nullable', 'url', 'max:2048'],
            'banner_url' => ['nullable', 'url', 'max:2048'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['exists:genres,id'],
            'directors' => ['nullable', 'array'],
            'directors.*' => ['exists:directors,id'],
            'actors' => ['nullable', 'array'],
            'actors.*' => ['exists:actors,id'],
            'role_names' => ['nullable', 'array'],
            'role_names.*' => ['nullable', 'string', 'max:100'],
        ]);
    }

    private function mediaPathFromRequest(Request $request, string $fileField, string $urlField, string $directory, ?string $current = null): ?string
    {
        if ($request->hasFile($fileField)) {
            $this->deletePublicFile($current);

            return $request->file($fileField)->store($directory, 'public');
        }

        if ($request->filled($urlField)) {
            $url = trim((string) $request->input($urlField));

            if ($current !== $url) {
                $this->deletePublicFile($current);
            }

            return $url;
        }

        return $current;
    }

    private function syncRelations(Movie $movie, array $validated): void
    {
        $movie->genres()->sync($validated['genres'] ?? []);
        $movie->directors()->sync($validated['directors'] ?? []);

        $actorSync = [];
        foreach (($validated['actors'] ?? []) as $actorId) {
            $actorSync[$actorId] = [
                'role_name' => $validated['role_names'][$actorId] ?? null,
            ];
        }

        $movie->actors()->sync($actorSync);
    }

    private function deletePublicFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $path = trim($path);

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return;
        }

        $path = ltrim($path, '/');
        $path = str_starts_with($path, 'storage/') ? substr($path, 8) : $path;

        Storage::disk('public')->delete($path);
    }
}
