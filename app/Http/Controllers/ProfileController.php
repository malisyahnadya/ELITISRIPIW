<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     *
     * Halaman ini berisi:
     * - informasi dasar user
     * - daftar watchlist user
     * - daftar film yang sudah direview user
     * - daftar film yang sudah diberi rating user
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $watchlist = $user
            ->profileWatchList()
            ->paginate(10, ['*'], 'watchlist_page');

        $reviewedMovies = $user
            ->profileReviewedMovies()
            ->paginate(10, ['*'], 'review_page');

        $ratedMovies = $user
            ->profileRatedMovies()
            ->paginate(10, ['*'], 'rating_page');

        return view('profile.index', compact(
            'user',
            'watchlist',
            'reviewedMovies',
            'ratedMovies'
        ));
    }

    /**
     * Menampilkan halaman edit profil.
     */
    public function edit(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:250'],

            'username' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'username')->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'bio' => ['nullable', 'string', 'max:500'],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('profile_photo')) {
            $this->deleteStoredProfilePhoto($user);

            $validated['profile_photo'] = $request
                ->file('profile_photo')
                ->store('profiles', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ], [
            'current_password.current_password' => 'Password lama yang kamu masukkan salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil pengguna.
     */
    public function destroyPhoto(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->deleteStoredProfilePhoto($user);

        $user->update([
            'profile_photo' => null,
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Menghapus file foto profil dari storage public.
     *
     * Foto hanya dihapus kalau:
     * - user punya profile_photo
     * - profile_photo bukan URL eksternal
     */
    private function deleteStoredProfilePhoto(User $user): void
    {
        if (! $user->profile_photo) {
            return;
        }

        if (str_starts_with($user->profile_photo, 'http')) {
            return;
        }

        Storage::disk('public')->delete($user->profile_photo);
    }
}