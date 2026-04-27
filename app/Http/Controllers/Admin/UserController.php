<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $search = request('search');
        $sort = request('sort', 'asc');

        $users = User::query()
            ->search($search)
            ->sortByUsername($sort)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'sort'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->loadCount(['reviews', 'ratings']);
        return view('admin.users.show', compact('user'));
    }

    // Admin biasanya cuma butuh edit role user, jadi kita skip method create/store
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update role user (admin/user)
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,user'], // Cuma boleh pilih dua ini
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name}'s role updated successfully.");
    }

    // Hapus user (hati-hati, ini aksi sensitif)
    public function destroy(User $user): RedirectResponse
    {
        // Proteksi: Admin dilarang hapus dirinya sendiri wkwk
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account, cok!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User has been deleted.');
    }
}
