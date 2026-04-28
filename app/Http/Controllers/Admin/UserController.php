<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $sort = $request->string('sort', 'asc')->toString();
        $sort = $sort === 'desc' ? 'desc' : 'asc';

        $users = User::query()
            ->withCount(['reviews', 'ratings', 'watchlists'])
            ->search($search)
            ->when($sort === 'desc', fn ($query) => $query->orderByDesc('created_at')->orderByDesc('id'))
            ->when($sort === 'asc', fn ($query) => $query->orderBy('name')->orderBy('username'))
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'sort'));
    }

    public function edit(User $user): View
    {
        $user->loadCount(['reviews', 'ratings', 'watchlists']);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,user'],
        ]);

        if ($user->id === $request->user()->id && $validated['role'] !== 'admin') {
            return back()->with('error', 'Akun admin yang sedang login tidak bisa diubah menjadi user biasa.');
        }

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Role user berhasil diperbarui.');
    }
}
