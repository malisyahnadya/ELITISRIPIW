<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ActorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $search = request('search');
        $sort = request('sort', 'asc');

        $actors = Actor::query()
            ->search($search)
            ->sortByName($sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.actors.index', compact('actors', 'search', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.actors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:actors,name'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('actors', 'public');
        }

        Actor::create([
            'name' => $validated['name'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('admin.actors.index')
            ->with('success', 'Actor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $actor = Actor::findOrFail($id);
        return view('admin.actors.show', compact('actor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $actor = Actor::findOrFail($id);
        return view('admin.actors.edit', compact('actor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $actor = Actor::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:actors,name,' . $actor->id],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $photoPath = $actor->photo_path;

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            // Simpan foto baru
            $photoPath = $request->file('photo')->store('actors', 'public');
        }

        $actor->update([
            'name' => $validated['name'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('admin.actors.index')
            ->with('success', 'Actor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $actor = Actor::findOrFail($id);

        // Hapus foto jika ada
        if ($actor->photo_path) {
            Storage::disk('public')->delete($actor->photo_path);
        }

        $actor->delete();

        return redirect()->route('admin.actors.index')
            ->with('success', 'Actor deleted successfully.');
    }
}
