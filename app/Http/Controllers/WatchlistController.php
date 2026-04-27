<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => ['required', 'integer', 'exists:movies,id'],
            'status' => [
                'required',
                Rule::in([
                    Watchlist::STATUS_PLAN_TO_WATCH,
                    Watchlist::STATUS_WATCHING,
                    Watchlist::STATUS_COMPLETED,
                ]),
            ],
        ]);

        Watchlist::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'movie_id' => $validated['movie_id'],
            ],
            ['status' => $validated['status']]
        );

        return back()->with('status', 'watchlist-saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    Watchlist::STATUS_PLAN_TO_WATCH,
                    Watchlist::STATUS_WATCHING,
                    Watchlist::STATUS_COMPLETED,
                ]),
            ],
        ]);

        $watchlist = Watchlist::query()
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $watchlist->update([
            'status' => $validated['status'],
        ]);

        return back()->with('status', 'watchlist-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
