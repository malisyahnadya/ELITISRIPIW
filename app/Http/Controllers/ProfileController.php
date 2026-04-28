<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
class ProfileController extends Controller
{
    

    public function index(): View
    {
        $user = Auth::user();

        $watchlist = $user->profileWatchList()->paginate(10, ['*'], 'watchlist_page');
        $reviewedMovies = $user->profileReviewedMovies()->paginate(10, ['*'], 'review_page');
        $ratedMovies = $user->profileRatedMovies()->paginate(10, ['*'], 'rating_page');

        return view('profile.index', compact('user', 'watchlist', 'reviewedMovies', 'ratedMovies'));
    }

    public function edit(): View
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
 
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:250'],
            'username'      => ['required', 'string', 'max:30', 'unique:users,username,' . $user->id],
            'email'         => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'bio'           => ['nullable', 'string', 'max:500'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
 
        // Handle upload foto profil baru
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada dan bukan URL eksternal
            if ($user->profile_photo && !str_starts_with($user->profile_photo, 'http')) {
                Storage::disk('public')->delete($user->profile_photo);
            }
 
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('profiles', 'public');
        }
 
        $user->update($validated);
 
        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
            ],
        ], [
            'current_password.current_password' => 'Password lama yang kamu masukkan salah.',
            'password.confirmed'                => 'Konfirmasi password baru tidak cocok.',
        ]);
 
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);
 
        return redirect()->route('profile.index')
            ->with('success', 'Password berhasil diperbarui.');
    }

    public function destroyPhoto(): RedirectResponse
    {
        $user = Auth::user();
 
        if ($user->profile_photo && !str_starts_with($user->profile_photo, 'http')) {
            Storage::disk('public')->delete($user->profile_photo);
        }
 
        $user->update(['profile_photo' => null]);
 
        return redirect()->route('profile.edit')
            ->with('success', 'Foto profil berhasil dihapus.');
    }
}
