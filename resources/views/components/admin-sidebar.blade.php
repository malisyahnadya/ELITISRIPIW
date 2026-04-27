                          <nav class="mt-3 space-y-1.5">
                                @foreach ($menuItems as $item)
                                    @php
                                        $exists = Route::has($item['route']);
                                        $href = $exists ? route($item['route']) : '#';
                                        $active = request()->routeIs($item['pattern']);
                                    @endphp
                                    <a
                                        href="{{ $href }}"
                                        class="menu-link group {{ $active ? 'menu-link-active' : 'menu-link-idle' }} {{ !$exists ? 'menu-link-disabled' : '' }}"
                                    >
                                        <span class="menu-icon {{ $active ? 'menu-icon-active' : '' }}">
                                            @if ($item['icon'] === 'dashboard')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm0 8h8v-4H3v4zm10 0h8V11h-8v10zm0-18v4h8V3h-8z" />
                                                </svg>
                                            @elseif ($item['icon'] === 'movie')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-9 5h8a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            @elseif ($item['icon'] === 'genre')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10v10H7zM4 4h16v16H4z" />
                                                </svg>
                                            @elseif ($item['icon'] === 'actor')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-6.13a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            @elseif ($item['icon'] === 'director')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 20.055a12.083 12.083 0 01-6.16-9.477L12 14z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-6.13a4 4 0 11-8 0 4 4 0 018 0zM6 8a3 3 0 11-6 0 3 3 0 016 0zm12 0a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            @endif
                                        </span>

                                        <span class="text-sm font-medium">{{ $item['label'] }}</span>

                                        @if (!$exists)
                                            <span class="ml-auto rounded-full border border-slate-600 px-2 py-0.5 text-[10px] uppercase tracking-wide text-slate-400">Soon</span>
                                        @endif
                                    </a>
                                @endforeach
                            </nav>