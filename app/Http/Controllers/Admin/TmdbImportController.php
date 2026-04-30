<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TmdbImportController extends Controller
{
    public function index()
    {
        return view('admin.tmdb-import.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:255'],
            'import_type' => ['required', 'in:title,id'],
            'cast_limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'overwrite' => ['nullable', 'boolean'],
        ]);

        $params = [
            'query' => $validated['query'],
            '--cast-limit' => $validated['cast_limit'] ?? 12,
        ];

        if ($validated['import_type'] === 'id') {
            $params['--id'] = true;
        }

        if ($request->boolean('overwrite')) {
            $params['--overwrite'] = true;
        }

        Artisan::call('tmdb:import-movie', $params);

        $output = Artisan::output();

        if (str_contains($output, 'Selesai import')) {
            return redirect()
                ->route('admin.movies.index')
                ->with('success', 'Movie berhasil di-import dari TMDB.')
                ->with('tmdb_output', $output);
        }

        return back()
            ->withInput()
            ->with('error', 'Import TMDB gagal. Cek output detail di bawah.')
            ->with('tmdb_output', $output);
    }
}