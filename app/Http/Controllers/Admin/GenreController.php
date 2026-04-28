<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        $sort = request('sort', 'asc');

        $genres = Genre::query()
            ->withCount('movies')
            ->search($search)
            ->sortByName($sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.genres.index', compact('genres', 'search', 'sort'));
    }

    public function create(): View
    {
        return view('admin.genres.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('genres', 'name')],
        ]);

        Genre::create($validated);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre berhasil ditambahkan.');
    }

    public function show(Genre $genre): RedirectResponse
    {
        return redirect()->route('admin.genres.edit', $genre);
    }

    public function edit(Genre $genre): View
    {
        $genre->loadCount('movies');

        return view('admin.genres.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('genres', 'name')->ignore($genre->id)],
        ]);

        $genre->update($validated);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre berhasil diperbarui.');
    }

    public function destroy(Genre $genre): RedirectResponse
    {
        $genre->movies()->detach();
        $genre->delete();

        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre berhasil dihapus.');
    }
}
