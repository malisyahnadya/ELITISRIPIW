<?php

namespace App\Http\Controllers;

use App\Models\Movie;

class MovieController extends Controller
{
    public function show(Movie $movie)
    {
        $movie = Movie::query()
            ->withDetailData()
            ->findOrFail($movie->id);

        return view('movies.movie', [
            'movie' => $movie,
        ]);
    }
}
