<x-admin-layout title="Add Movie" subtitle="Isi data dasar film, genre, director, actor, dan media movie.">
    <x-slot name="actions">
        <a href="{{ route('admin.movies.index') }}" class="admin-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
    </x-slot>

    <section class="admin-panel">
        <header class="admin-panel-head">
            <h2>Movie Form</h2>
            <span class="admin-badge">Create</span>
        </header>
        <div class="admin-panel-body">
            <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data" class="admin-form">
                @csrf

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="title">Title</label>
                        <input id="title" class="admin-input" name="title" value="{{ old('title') }}" maxlength="150" required>
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="release_year">Release Year</label>
                        <input id="release_year" class="admin-input" type="number" name="release_year" value="{{ old('release_year') }}" min="1888" max="{{ date('Y') + 2 }}">
                        <x-input-error :messages="$errors->get('release_year')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="duration_minutes">Duration Minutes</label>
                        <input id="duration_minutes" class="admin-input" type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1">
                        <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="trailer_url">Trailer URL</label>
                        <input id="trailer_url" class="admin-input" type="url" name="trailer_url" value="{{ old('trailer_url') }}" placeholder="https://youtube.com/...">
                        <x-input-error :messages="$errors->get('trailer_url')" class="mt-2" />
                    </div>

                    <div class="admin-field">
                        <label for="poster_url">Poster URL</label>
                        <input id="poster_url" class="admin-input" type="url" name="poster_url" value="{{ old('poster_url') }}" placeholder="https://image.tmdb.org/t/p/w500/...">
                        <p class="admin-help">Boleh pakai link TMDB/manual. Dipakai kalau tidak upload file poster.</p>
                        <x-input-error :messages="$errors->get('poster_url')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="banner_url">Banner URL</label>
                        <input id="banner_url" class="admin-input" type="url" name="banner_url" value="{{ old('banner_url') }}" placeholder="https://image.tmdb.org/t/p/original/...">
                        <p class="admin-help">Boleh pakai link TMDB/manual. Dipakai kalau tidak upload file banner.</p>
                        <x-input-error :messages="$errors->get('banner_url')" class="mt-2" />
                    </div>

                    <div class="admin-field">
                        <label for="poster">Upload Poster</label>
                        <input id="poster" class="admin-file" type="file" name="poster" accept="image/*">
                        <p class="admin-help">Opsional. Jika dipilih, file upload ini dipakai sebagai poster.</p>
                        <x-input-error :messages="$errors->get('poster')" class="mt-2" />
                    </div>
                    <div class="admin-field">
                        <label for="banner">Upload Banner</label>
                        <input id="banner" class="admin-file" type="file" name="banner" accept="image/*">
                        <p class="admin-help">Opsional. Jika dipilih, file upload ini dipakai sebagai banner.</p>
                        <x-input-error :messages="$errors->get('banner')" class="mt-2" />
                    </div>
                    <div class="admin-field admin-span-2">
                        <label for="description">Description</label>
                        <textarea id="description" class="admin-textarea" name="description" rows="5">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                <div class="admin-form-grid">
                    <fieldset class="admin-field">
                        <legend>Genres</legend>
                        <div class="admin-check-grid">
                            @forelse($genres as $genre)
                                <label class="admin-check-row">
                                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}" @checked(in_array($genre->id, old('genres', [])))>
                                    <span>{{ $genre->name }}</span>
                                </label>
                            @empty
                                <p class="admin-help">Belum ada genre. <a href="{{ route('admin.genres.create') }}" style="color:#fff;text-decoration:underline">Tambah genre</a> dulu.</p>
                            @endforelse
                        </div>
                        <x-input-error :messages="$errors->get('genres')" class="mt-2" />
                    </fieldset>

                    <fieldset class="admin-field">
                        <legend>Directors</legend>
                        <div class="admin-check-grid">
                            @forelse($directors as $director)
                                <label class="admin-check-row">
                                    <input type="checkbox" name="directors[]" value="{{ $director->id }}" @checked(in_array($director->id, old('directors', [])))>
                                    <span>{{ $director->name }}</span>
                                </label>
                            @empty
                                <p class="admin-help">Belum ada director. <a href="{{ route('admin.directors.create') }}" style="color:#fff;text-decoration:underline">Tambah director</a> dulu.</p>
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
                                    <input type="checkbox" name="actors[]" value="{{ $actor->id }}" @checked(in_array($actor->id, old('actors', [])))>
                                    <span>{{ $actor->name }}</span>
                                </label>
                                <input class="admin-input" name="role_names[{{ $actor->id }}]" value="{{ old('role_names.' . $actor->id) }}" placeholder="Role name">
                            </div>
                        @empty
                            <p class="admin-help">Belum ada actor. <a href="{{ route('admin.actors.create') }}" style="color:#fff;text-decoration:underline">Tambah actor</a> dulu.</p>
                        @endforelse
                    </div>
                    <x-input-error :messages="$errors->get('actors')" class="mt-2" />
                </fieldset>

                <div class="admin-actions" style="justify-content:flex-end">
                    <a href="{{ route('admin.movies.index') }}" class="admin-btn-outline">Cancel</a>
                    <button class="admin-btn" type="submit">Save Movie</button>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
