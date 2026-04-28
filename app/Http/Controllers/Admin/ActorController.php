<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ActorController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        $sort = request('sort', 'asc');

        $actors = Actor::query()
            ->withCount('movies')
            ->search($search)
            ->sortByName($sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.actors.index', compact('actors', 'search', 'sort'));
    }

    public function create(): View
    {
        return view('admin.actors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('actors', 'name')],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        Actor::create([
            'name' => $validated['name'],
            'photo_path' => $request->hasFile('photo') ? $request->file('photo')->store('actors', 'public') : null,
        ]);

        return redirect()->route('admin.actors.index')
            ->with('success', 'Actor berhasil ditambahkan.');
    }

    public function show(Actor $actor): RedirectResponse
    {
        return redirect()->route('admin.actors.edit', $actor);
    }

    public function edit(Actor $actor): View
    {
        $actor->loadCount('movies');

        return view('admin.actors.edit', compact('actor'));
    }

    public function update(Request $request, Actor $actor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('actors', 'name')->ignore($actor->id)],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photoPath = $actor->photo_path;

        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('actors', 'public');
        }

        $actor->update([
            'name' => $validated['name'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('admin.actors.index')
            ->with('success', 'Actor berhasil diperbarui.');
    }

    public function destroy(Actor $actor): RedirectResponse
    {
        if ($actor->photo_path) {
            Storage::disk('public')->delete($actor->photo_path);
        }

        $actor->movies()->detach();
        $actor->delete();

        return redirect()->route('admin.actors.index')
            ->with('success', 'Actor berhasil dihapus.');
    }
}
