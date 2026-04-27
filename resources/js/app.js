import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
	const roots = document.querySelectorAll('[data-live-search]');

	roots.forEach((root) => {
		const input = root.querySelector('[data-search-input]');
		const dropdown = root.querySelector('[data-search-dropdown]');
		const results = root.querySelector('[data-search-results]');
		const seeMore = root.querySelector('[data-search-see-more]');
		const searchUrl = root.getAttribute('data-search-url') || '';
		const suggestUrl = root.getAttribute('data-suggest-url') || '';

		if (!input || !dropdown || !results || !seeMore || !searchUrl || !suggestUrl) {
			return;
		}

		let timer = null;
		let activeQuery = '';

		const closeDropdown = () => {
			dropdown.classList.add('hidden');
		};

		const buildQueryUrl = (baseUrl, query) => {
			const url = new URL(baseUrl, window.location.origin);
			url.searchParams.set('q', query);
			return url.toString();
		};

		const escapeHtml = (value) => {
			return value
				.replaceAll('&', '&amp;')
				.replaceAll('<', '&lt;')
				.replaceAll('>', '&gt;')
				.replaceAll('"', '&quot;')
				.replaceAll("'", '&#039;');
		};

		const renderResults = (items) => {
			if (!items.length) {
				results.innerHTML = '<li class="px-3 py-2 text-xs text-slate-500">Film tidak ditemukan.</li>';
				return;
			}

			results.innerHTML = items
				.map((movie) => {
					const title = escapeHtml(movie.title);
					const year = escapeHtml(String(movie.release_year ?? '-'));
					const duration = escapeHtml(String(movie.duration ?? '-'));
					const score = escapeHtml(String(movie.average_score ?? '0.0'));
					const poster = escapeHtml(movie.poster_url || 'https://via.placeholder.com/48x72?text=No');
					const url = escapeHtml(movie.url);

					return `
						<li>
							<a href="${url}" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-100">
								<img src="${poster}" alt="${title}" class="h-12 w-8 rounded object-cover">
								<span class="min-w-0 flex-1">
									<span class="block truncate text-sm font-medium text-slate-700">${title}</span>
									<span class="block text-xs text-slate-500">${year} • ${duration} • ${score}/5</span>
								</span>
							</a>
						</li>
					`;
				})
				.join('');
		};

		input.addEventListener('input', function () {
			const query = input.value.trim();
			activeQuery = query;
			seeMore.href = buildQueryUrl(searchUrl, query);

			if (timer) {
				clearTimeout(timer);
			}

			if (query.length < 2) {
				results.innerHTML = '<li class="px-3 py-2 text-xs text-slate-500">Ketik minimal 2 huruf.</li>';
				closeDropdown();
				return;
			}

			timer = setTimeout(async () => {
				try {
					const response = await fetch(buildQueryUrl(suggestUrl, query), {
						headers: {
							Accept: 'application/json',
						},
					});

					if (!response.ok || activeQuery !== query) {
						return;
					}

					const payload = await response.json();
					renderResults(Array.isArray(payload.data) ? payload.data : []);
					seeMore.href = payload.see_more_url || buildQueryUrl(searchUrl, query);
					dropdown.classList.remove('hidden');
				} catch (error) {
					results.innerHTML = '<li class="px-3 py-2 text-xs text-red-500">Gagal memuat saran.</li>';
					dropdown.classList.remove('hidden');
				}
			}, 250);
		});

		input.addEventListener('focus', function () {
			if (input.value.trim().length >= 2) {
				dropdown.classList.remove('hidden');
			}
		});

		document.addEventListener('click', function (event) {
			if (!root.contains(event.target)) {
				closeDropdown();
			}
		});
	});
});
