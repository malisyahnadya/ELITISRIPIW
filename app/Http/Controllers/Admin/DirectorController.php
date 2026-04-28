<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Director;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DirectorController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        $sort = request('sort', 'asc');

        $directors = Director::query()
            ->withCount('movies')
            ->search($search)
            ->sortByName($sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.directors.index', compact('directors', 'search', 'sort'));
    }

    public function create(): View
    {
        return view('admin.directors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('directors', 'name')],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        Director::create([
            'name' => $validated['name'],
            'photo_path' => $request->hasFile('photo') ? $request->file('photo')->store('directors', 'public') : null,
        ]);

        return redirect()->route('admin.directors.index')
            ->with('success', 'Director berhasil ditambahkan.');
    }

    public function show(Director $director): RedirectResponse
    {
        return redirect()->route('admin.directors.edit', $director);
    }

    public function edit(Director $director): View
    {
        $director->loadCount('movies');

        return view('admin.directors.edit', compact('director'));
    }

    public function update(Request $request, Director $director): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('directors', 'name')->ignore($director->id)],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photoPath = $director->photo_path;

        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('directors', 'public');
        }

        $director->update([
            'name' => $validated['name'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('admin.directors.index')
            ->with('success', 'Director berhasil diperbarui.');
    }

    public function destroy(Director $director): RedirectResponse
    {
        if ($director->photo_path) {
            Storage::disk('public')->delete($director->photo_path);
        }

        $director->movies()->detach();
        $director->delete();

        return redirect()->route('admin.directors.index')
            ->with('success', 'Director berhasil dihapus.');
    }
}
