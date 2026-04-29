@php
    $currentPosterUrlValue = old('poster_url', str_starts_with((string) $movie->poster_path, 'http') ? $movie->poster_path : '');
    $currentBannerUrlValue = old('banner_url', str_starts_with((string) $movie->banner_path, 'http') ? $movie->banner_path : '');
@endphp

<x-admin-layout title="Edit Movie" subtitle="Perbarui data movie dan relasi cast & crew.">
    <x-slot name="actions">
        <a href="{{ route('admin.movies.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
    </x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>{{ $movie->title }}</h2>
            <span class="admin-badge">Edit</span>
        </header>
        <div class="admin-panel-body">
            <form id="movie-update" method="POST" action="{{ route('admin.movies.update', $movie) }}" enctype="multipart/form-data" class="admin-form">
                @csrf
                @method('PUT')

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="title">Title</label>
                        <input id="title" class="admin-input" name="title" value="{{ old('title', $movie->title) }}" maxlength="150" required>
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="release_year">Release Year</label>
                        <input id="release_year" class="admin-input" type="number" name="release_year" value="{{ old('release_year', $movie->release_year) }}" min="1888" max="{{ date('Y') + 2 }}">
                        <x-input-error :messages="$errors->get('release_year')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="duration_minutes">Duration Minutes</label>
                        <input id="duration_minutes" class="admin-input" type="number" name="duration_minutes" value="{{ old('duration_minutes', $movie->duration_minutes) }}" min="1">
                        <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="trailer_url">Trailer URL</label>
                        <input id="trailer_url" class="admin-input" type="url" name="trailer_url" value="{{ old('trailer_url', $movie->trailer_url) }}" placeholder="https://youtube.com/...">
                        <x-input-error :messages="$errors->get('trailer_url')" class="mt-2" />
                    </div>

                    <div class="admin-field">
                        <label for="poster_url">Poster URL</label>
                        <input id="poster_url" class="admin-input" type="url" name="poster_url" value="{{ $currentPosterUrlValue }}" placeholder="https://image.tmdb.org/t/p/w500/...">
                        <p class="admin-help">Isi untuk mengganti poster memakai link. Jika upload file dipilih, file upload yang dipakai.</p>
                        <x-input-error :messages="$errors->get('poster_url')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="banner_url">Banner URL</label>
                        <input id="banner_url" class="admin-input" type="url" name="banner_url" value="{{ $currentBannerUrlValue }}" placeholder="https://image.tmdb.org/t/p/original/...">
                        <p class="admin-help">Isi untuk mengganti banner memakai link. Jika upload file dipilih, file upload yang dipakai.</p>
                        <x-input-error :messages="$errors->get('banner_url')" class="mt-2" />
                    </div>

                    <div class="admin-field">
                        <label for="poster">Upload Poster</label>
                        <input id="poster" class="admin-file" type="file" name="poster" accept="image/*">
                        @if($movie->poster_url)
                            <div class="admin-preview"><img src="{{ $movie->poster_url }}" alt="Poster {{ $movie->title }}"><span>Poster saat ini</span></div>
                        @endif
                        <x-input-error :messages="$errors->get('poster')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="banner">Upload Banner</label>
                        <input id="banner" class="admin-file" type="file" name="banner" accept="image/*">
                        @if($movie->banner_url)
                            <div class="admin-preview admin-preview-wide"><img src="{{ $movie->banner_url }}" alt="Banner {{ $movie->title }}"><span>Banner saat ini</span></div>
                        @endif
                        <x-input-error :messages="$errors->get('banner')" class="mt-2" />
                    </div>
                    <div class="admin-field admin-span-2">
                        <label for="description">Description</label>
                        <textarea id="description" class="admin-textarea" name="description" rows="5">{{ old('description', $movie->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                <div class="admin-form-grid">
                    <fieldset class="admin-field">
                        <legend>Genres</legend>
                        <div class="admin-check-grid">
                            @forelse($genres as $genre)
                                <label class="admin-check-row">
                                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}" @checked(in_array($genre->id, old('genres', $movie->genres->pluck('id')->all())))>
                                    <span>{{ $genre->name }}</span>
                                </label>
                            @empty
                                <p class="admin-help">Belum ada genre.</p>
                            @endforelse
                        </div>
                        <x-input-error :messages="$errors->get('genres')" class="mt-2" />
                    </fieldset>

                    <fieldset class="admin-field">
                        <legend>Directors</legend>
                        <div class="admin-check-grid">
                            @forelse($directors as $director)
                                <label class="admin-check-row">
                                    <input type="checkbox" name="directors[]" value="{{ $director->id }}" @checked(in_array($director->id, old('directors', $movie->directors->pluck('id')->all())))>
                                    <span>{{ $director->name }}</span>
                                </label>
                            @empty
                                <p class="admin-help">Belum ada director.</p>
                            @endforelse
                        </div>
                        <x-input-error :messages="$errors->get('directors')" class="mt-2" />
                    </fieldset>
                </div>

                <fieldset class="admin-field">
                    <legend>Actors & Role Name</legend>
                    <div class="admin-check-grid">
                        @forelse($actors as $actor)
                            <div class="admin-actor-row">
                                <label class="admin-check-row" style="background:transparent;padding:0">
                                    <input type="checkbox" name="actors[]" value="{{ $actor->id }}" @checked(in_array($actor->id, old('actors', $movie->actors->pluck('id')->all())))>
                                    <span>{{ $actor->name }}</span>
                                </label>
                                <input class="admin-input" name="role_names[{{ $actor->id }}]" value="{{ old('role_names.' . $actor->id, $selectedActors[$actor->id] ?? '') }}" placeholder="Role name">
                            </div>
                        @empty
                            <p class="admin-help">Belum ada actor.</p>
                        @endforelse
                    </div>
                    <x-input-error :messages="$errors->get('actors')" class="mt-2" />
                </fieldset>
            </form>

            <form id="movie-delete" method="POST" action="{{ route('admin.movies.destroy', $movie) }}" onsubmit="return confirm('Hapus movie ini? Data review/rating/watchlist terkait juga ikut terhapus oleh relasi database.')">
                @csrf
                @method('DELETE')
            </form>

            <div class="admin-actions" style="justify-content:space-between;margin-top:1rem">
                <button form="movie-delete" type="submit" class="admin-btn-danger">Delete Movie</button>
                <div class="admin-actions">
                    <a href="{{ route('admin.movies.index') }}" class="admin-btn-outline">Cancel</a>
                    <button form="movie-update" class="admin-btn" type="submit">Save Movie</button>
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
