<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Director;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $search = request('search');
        $sort = request('sort', 'asc');

        $directors = Director::query()
            ->search($search)
            ->sortByName($sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.directors.index', [
            'directors' => $directors,
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.directors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:directors,name'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('directors', 'public');
        }

        Director::create([
            'name' => $validated['name'],
            'photo_path' => $photoPath,
        ]);

        return redirect()
            ->route('admin.directors.index')
            ->with('success', 'Director created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Director $director): RedirectResponse
    {
        return redirect()->route('admin.directors.edit', $director);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Director $director): View
    {
        return view('admin.directors.edit', [
            'director' => $director,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Director $director): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:directors,name,' . $director->id],
            'photo' => ['nullable', 'image', 'max:2048'],
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

        return redirect()
            ->route('admin.directors.index')
            ->with('success', 'Data Director updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Director $director): RedirectResponse
    {
        if ($director->photo_path) {
            Storage::disk('public')->delete($director->photo_path);
        }

        // Lepas relasi pivot sebelum hapus untuk mencegah konflik FK.
        $director->movies()->detach();
        $director->delete();

        return redirect()
            ->route('admin.directors.index')
            ->with('success', 'Directorer deleted successfully.');
    }
}
