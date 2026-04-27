<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Actor;
use App\Models\Director;
use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;


use Illuminate\View\View;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $search = request('search');
        $sort = request('sort', 'asc');  
        
        $movies = Movie::query()
            ->search($search)
            ->sortByTitle($sort)
            ->paginate(10)
            ->withQueryString();

        // UBAH: Tambahin $search dan $sort ke dalam compact
        return view("admin.movies.index", compact("movies", "search", "sort"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $genres    = Genre::orderBy('name')->get();
        $actors    = Actor::orderBy('name')->get();
        $directors = Director::orderBy('name')->get();
 
        return view('admin.movies.create', compact('genres', 'actors', 'directors'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255', 'unique:movies,title'],
            'description'      => ['nullable', 'string'],
            'release_year'     => ['required', 'integer', 'min:1888', 'max:' . (date('Y') + 1)],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'trailer_url'      => ['required', 'url', 'max:255'],
            'poster'           => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner'           => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'genres'           => ['required', 'array', 'min:1'],
            'genres.*'         => ['exists:genres,id'],
            'directors'        => ['required', 'array', 'min:1'],
            'directors.*'      => ['exists:directors,id'],
            'actors'           => ['nullable', 'array'],
            'actors.*'         => ['exists:actors,id'],
            'role_names'       => ['nullable', 'array'],
            'role_names.*'     => ['nullable', 'string', 'max:100'],
        ]);
 
        // Upload poster dan banner
        $posterPath = $request->file('poster')->store('movies/posters', 'public');
        $bannerPath = $request->file('banner')->store('movies/banners', 'public');
 
        // Buat movie
        $movie = Movie::create([
            'title'            => $validated['title'],
            'description'      => $validated['description'] ?? null,
            'release_year'     => $validated['release_year'],
            'duration_minutes' => $validated['duration_minutes'],
            'trailer_url'      => $validated['trailer_url'],
            'poster_path'      => $posterPath,
            'banner_path'      => $bannerPath,
        ]);
 
        // Sync relasi genre dan director
        $movie->genres()->sync($validated['genres']);
        $movie->directors()->sync($validated['directors']);
 
        // Sync relasi actor dengan role_name di pivot
        if (!empty($validated['actors'])) {
            $actorSync = [];
            foreach ($validated['actors'] as $index => $actorId) {
                $actorSync[$actorId] = [
                    'role_name' => $validated['role_names'][$index] ?? null,
                ];
            }
            $movie->actors()->sync($actorSync);
        }
 
        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movies)
    {
        return view('admin.movies.show', compact('movie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie): View
    {
        $genres    = Genre::orderBy('name')->get();
        $actors    = Actor::orderBy('name')->get();
        $directors = Director::orderBy('name')->get();
 
        // Load relasi yang sudah terpilih
        $movie->load('genres', 'directors', 'actors');
 
        // Buat map actorId => role_name untuk prefill form
        $selectedActors = $movie->actors->mapWithKeys(function ($actor) {
            return [$actor->id => $actor->pivot->role_name];
        });
 
        return view('admin.movies.edit', compact(
            'movie',
            'genres',
            'actors',
            'directors',
            'selectedActors'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, Movie $movie): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255', 'unique:movies,title,' . $movie->id],
            'description'      => ['nullable', 'string'],
            'release_year'     => ['required', 'integer', 'min:1888', 'max:' . (date('Y') + 1)],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'trailer_url'      => ['nullable', 'url', 'max:255'],
            'poster'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'genres'           => ['required', 'array', 'min:1'],
            'genres.*'         => ['exists:genres,id'],
            'directors'        => ['required', 'array', 'min:1'],
            'directors.*'      => ['exists:directors,id'],
            'actors'           => ['nullable', 'array'],
            'actors.*'         => ['exists:actors,id'],
            'role_names'       => ['nullable', 'array'],
            'role_names.*'     => ['nullable', 'string', 'max:100'],
        ]);
 
        $posterPath = $movie->poster_path;
        $bannerPath = $movie->banner_path;
 
        // Update poster jika ada file baru
        if ($request->hasFile('poster')) {
            Storage::disk('public')->delete($posterPath);
            $posterPath = $request->file('poster')->store('movies/posters', 'public');
        }
 
        // Update banner jika ada file baru
        if ($request->hasFile('banner')) {
            Storage::disk('public')->delete($bannerPath);
            $bannerPath = $request->file('banner')->store('movies/banners', 'public');
        }
 
        $movie->update([
            'title'            => $validated['title'],
            'description'      => $validated['description'] ?? null,
            'release_year'     => $validated['release_year'],
            'duration_minutes' => $validated['duration_minutes'],
            'trailer_url'      => $validated['trailer_url'] ?? null,
            'poster_path'      => $posterPath,
            'banner_path'      => $bannerPath,
        ]);
 
        // Sync relasi genre dan director
        $movie->genres()->sync($validated['genres']);
        $movie->directors()->sync($validated['directors']);
 
        // Sync relasi actor dengan role_name di pivot
        if (!empty($validated['actors'])) {
            $actorSync = [];
            foreach ($validated['actors'] as $index => $actorId) {
                $actorSync[$actorId] = [
                    'role_name' => $validated['role_names'][$index] ?? null,
                ];
            }
            $movie->actors()->sync($actorSync);
        } else {
            // Jika semua actor dihapus, detach semua
            $movie->actors()->detach();
        }
 
        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): RedirectResponse
    {
        // Hapus poster dan banner dari storage
        if ($movie->poster_path) {
            Storage::disk('public')->delete($movie->poster_path);
        }
 
        if ($movie->banner_path) {
            Storage::disk('public')->delete($movie->banner_path);
        }
 
        // Lepas semua relasi pivot sebelum hapus
        $movie->genres()->detach();
        $movie->directors()->detach();
        $movie->actors()->detach();
 
        $movie->delete();
 
        return redirect()->route('admin.movies.index')
                         ->with('success', 'Movie deleted successfully.');
    }
}
