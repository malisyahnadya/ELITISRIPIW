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
        // Ambil user yang sedang login dari request.
        $user = $request->user();

        // Ambil daftar film yang ada di watchlist user dengan pagination.
        $watchlist = $user
            ->profileWatchList()
            ->paginate(10, ['*'], 'watchlist_page');

        // Ambil daftar film yang sudah pernah direview user.
        $reviewedMovies = $user
            ->profileReviewedMovies()
            // paginate untuk membatasi jumlah film yang ditampilkan per halaman, dan menghandle query string untuk pagination.
            ->paginate(10, ['*'], 'review_page');

        // Ambil daftar film yang sudah diberi rating oleh user.
        $ratedMovies = $user
            ->profileRatedMovies()
            ->paginate(10, ['*'], 'rating_page');

        // Kirim semua data profil ke view.
        return view('profile.index', compact( // mengirim variabel dalam bentuk array ke view profile.index
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
        // Ambil user yang sedang login dari request untuk ditampilkan di form edit profil.
        $user = $request->user();

        // Kirim data user ke view edit profil.
        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        // Ambil user yang sedang login dari request untuk diperbarui informasinya.
        $user = $request->user();

        // Validasi data yang dikirim dari form edit profil. Pastikan data sesuai dengan aturan yang ditetapkan, seperti format email, ukuran file foto, dll.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:250'],

            'username' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'username')->ignore($user->id),
            ],

            // Validasi untuk email: wajib diisi, harus format email yang valid, maksimal 100 karakter, dan harus unik di tabel users kecuali untuk user yang sedang diperbarui (ignore $user->id).
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            // Validasi untuk bio: opsional (nullable), harus berupa string, dan maksimal 500 karakter.
            'bio' => ['nullable', 'string', 'max:500'],

            // Validasi untuk foto profil: harus berupa file gambar dengan format tertentu dan ukuran maksimal 2MB. Foto ini opsional (nullable).
            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        // Jika ada file foto profil yang diunggah, proses penyimpanan file dan perbarui path foto di database. Sebelum menyimpan foto baru, hapus foto lama dari storage jika ada.
        if ($request->hasFile('profile_photo')) {
            $this->deleteStoredProfilePhoto($user);

            // Simpan file foto profil baru ke storage public dan dapatkan pathnya untuk disimpan di database.
            $validated['profile_photo'] = $request
                ->file('profile_photo')
                ->store('profiles', 'public');
        }

        // Perbarui data user di database dengan data yang sudah divalidasi. Hanya kolom yang ada di $validated yang akan diperbarui.
        $user->update($validated);

        // Setelah berhasil memperbarui profil, redirect kembali ke halaman profil dengan pesan sukses. Pengguna akan melihat pesan bahwa profil berhasil diperbarui.
        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        // Ambil user yang sedang login dari request untuk diperbarui passwordnya.
        $user = $request->user();

        // Validasi data yang dikirim dari form edit password. Pastikan password lama benar, dan password baru sesuai dengan aturan keamanan yang ditetapkan (minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan angka). Juga pastikan konfirmasi password baru cocok dengan password baru.
        $validated = $request->validate([
            // Validasi untuk current_password: wajib diisi dan harus sesuai dengan password lama user yang sedang login. Laravel menyediakan aturan 'current_password' untuk memudahkan validasi ini.
            'current_password' => ['required', 'current_password'],

            // Validasi untuk password baru: wajib diisi, harus dikonfirmasi (harus ada field password_confirmation yang cocok), dan harus memenuhi aturan keamanan yang ditetapkan menggunakan Password::min(8)->letters()->mixedCase()->numbers() untuk memastikan password kuat.
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ], [
            // Pesan error khusus untuk validasi current_password dan password confirmation agar lebih informatif bagi pengguna.
            'current_password.current_password' => 'Password lama yang kamu masukkan salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Perbarui password user di database dengan password baru yang sudah di-hash menggunakan Hash::make untuk keamanan.
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Setelah berhasil memperbarui password, redirect kembali ke halaman edit profil dengan pesan sukses. Pengguna akan melihat pesan bahwa password berhasil diperbarui.
        return redirect()
            ->route('profile.edit')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil pengguna.
     */
    public function destroyPhoto(Request $request): RedirectResponse
    {
        // Ambil user yang sedang login dari request untuk dihapus foto profilnya.
        $user = $request->user();

        // Hapus file foto profil dari storage jika ada, lalu perbarui kolom profile_photo di database menjadi null untuk menandakan bahwa user tidak lagi memiliki foto profil.
        $this->deleteStoredProfilePhoto($user);

        $user->update([
            'profile_photo' => null,
        ]);

        // Setelah berhasil menghapus foto profil, redirect kembali ke halaman edit profil dengan pesan sukses. Pengguna akan melihat pesan bahwa foto profil berhasil dihapus.
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

        // Jika profile_photo adalah URL eksternal (misalnya menggunakan layanan penyimpanan foto pihak ketiga), maka tidak perlu menghapus file dari storage karena bukan file lokal. Cukup abaikan proses penghapusan file.
        if (str_starts_with($user->profile_photo, 'http')) {
            return;
        }

        // Hapus file foto profil dari storage public menggunakan Storage::disk('public')->delete() dengan path yang disimpan di kolom profile_photo. Pastikan path yang disimpan di database sesuai dengan struktur penyimpanan di storage agar file yang benar-benar terkait dengan user ini yang dihapus.    
        Storage::disk('public')->delete($user->profile_photo);
    }
}