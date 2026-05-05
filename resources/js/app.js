import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('liveSearch', (config = {}) => ({
	query: '',
	results: [],
	open: false,
	loading: false,
	error: '',
	hasSearched: false,
	minLength: 2,
	timer: null,
	controller: null,
	searchUrl: config.searchUrl || '',
	suggestUrl: config.suggestUrl || '',
	seeMoreUrl: config.searchUrl || '',

	init() {
		this.query = (this.query || '').trim();
		this.updateSeeMore();
	},

	updateSeeMore() {
		this.seeMoreUrl = this.buildQueryUrl(this.searchUrl, this.query);
	},

	buildQueryUrl(baseUrl, query) {
		const url = new URL(baseUrl, window.location.origin);
		url.searchParams.set('q', query);
		return url.toString();
	},

	close() {
		this.open = false;
	},

	onFocus() {
		if (this.query.length >= this.minLength) {
			this.open = true;
		}
	},

	onInput() {
		this.query = this.query.trim();
		this.updateSeeMore();
		this.error = '';

		if (this.timer) {
			clearTimeout(this.timer);
		}

		if (this.query.length < this.minLength) {
			this.open = false;
			this.results = [];
			this.loading = false;
			this.hasSearched = false;
			if (this.controller) {
				this.controller.abort();
				this.controller = null;
			}
			return;
		}

		this.loading = true;
		this.timer = setTimeout(() => {
			this.fetchResults();
		}, 250);
	},

	async fetchResults() {
		const currentQuery = this.query;
		if (this.controller) {
			this.controller.abort();
		}

		this.controller = new AbortController();

		try {
			const response = await fetch(this.buildQueryUrl(this.suggestUrl, currentQuery), {
				headers: {
					Accept: 'application/json',
				},
				signal: this.controller.signal,
			});

			if (!response.ok || this.query !== currentQuery) {
				return;
			}

			const payload = await response.json();
			const items = Array.isArray(payload.data) ? payload.data : [];
			this.results = items;
			this.seeMoreUrl = payload.see_more_url || this.buildQueryUrl(this.searchUrl, currentQuery);
			this.hasSearched = true;
			this.open = true;
		} catch (error) {
			if (error.name === 'AbortError') {
				return;
			}
			this.error = 'Gagal memuat saran.';
			this.results = [];
			this.hasSearched = true;
			this.open = true;
		} finally {
			if (this.query === currentQuery) {
				this.loading = false;
			}
		}
	},
}));

Alpine.start();
