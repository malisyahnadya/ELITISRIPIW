// Muat konfigurasi bootstrap frontend (axios, csrf, dll).
import './bootstrap';

// Import Alpine.js untuk reaktivitas ringan di komponen UI.
import Alpine from 'alpinejs';

// Expose Alpine ke global window agar bisa diakses dari Blade jika perlu.
window.Alpine = Alpine;

// Daftarkan komponen Alpine bernama liveSearch.
Alpine.data('liveSearch', (config = {}) => ({
	// Query yang sedang diketik user.
	query: '',
	// Daftar hasil suggestion dari server.
	results: [],
	// Status buka/tutup dropdown hasil.
	open: false,
	// Status loading saat fetch sedang berjalan.
	loading: false,
	// Pesan error ketika request gagal.
	error: '',
	// Penanda bahwa pencarian sudah pernah dijalankan.
	hasSearched: false,
	// Minimal panjang karakter sebelum request dikirim.
	minLength: 2,
	// Penyimpan ID timer debounce.
	timer: null,
	// Penyimpan AbortController untuk membatalkan request lama.
	controller: null,
	// URL halaman search penuh.
	searchUrl: config.searchUrl || '',
	// URL endpoint suggestion live search.
	suggestUrl: config.suggestUrl || '',
	// URL target tombol "See more results".
	seeMoreUrl: config.searchUrl || '',

	// Lifecycle saat komponen diinisialisasi.
	init() {
		// Rapikan query awal jika ada nilai dari server.
		this.query = (this.query || '').trim();
		// Sinkronkan URL "See more" berdasarkan query awal.
		this.updateSeeMore();
	},

	// Perbarui URL "See more" agar selalu sesuai query aktif.
	updateSeeMore() {
		// Bangun URL dengan query string q.
		this.seeMoreUrl = this.buildQueryUrl(this.searchUrl, this.query);
	},

	// Helper untuk membuat URL + parameter q.
	buildQueryUrl(baseUrl, query) {
		// Buat objek URL dari base URL terhadap origin saat ini.
		const url = new URL(baseUrl, window.location.origin);
		// Set parameter q sebagai kata kunci pencarian.
		url.searchParams.set('q', query);
		// Kembalikan URL final dalam bentuk string.
		return url.toString();
	},

	// Tutup dropdown suggestion.
	close() {
		// Ubah state agar dropdown tidak terlihat.
		this.open = false;
	},

	// Handler saat input mendapatkan fokus.
	onFocus() {
		// Jika query sudah memenuhi panjang minimal, tampilkan dropdown.
		if (this.query.length >= this.minLength) {
			this.open = true;
		}
	},

	// Handler saat user mengetik di input.
	onInput() {
		// Rapikan query untuk menghindari spasi berlebih.
		this.query = this.query.trim();
		// Sinkronkan URL "See more" dengan query terbaru.
		this.updateSeeMore();
		// Reset error lama setiap input baru.
		this.error = '';

		// Jika ada timer debounce lama, batalkan dulu.
		if (this.timer) {
			clearTimeout(this.timer);
		}

		// Jika query terlalu pendek, reset state dan hentikan proses.
		if (this.query.length < this.minLength) {
			// Sembunyikan dropdown karena belum layak mencari.
			this.open = false;
			// Kosongkan hasil suggestion.
			this.results = [];
			// Pastikan loading dimatikan.
			this.loading = false;
			// Tandai belum ada pencarian yang valid.
			this.hasSearched = false;
			// Jika ada request aktif, batalkan agar tidak balapan data.
			if (this.controller) {
				this.controller.abort();
				// Kosongkan referensi controller setelah dibatalkan.
				this.controller = null;
			}
			// Stop eksekusi lebih lanjut.
			return;
		}

		// Aktifkan indikator loading sebelum request dijadwalkan.
		this.loading = true;
		// Debounce 250ms agar request tidak dikirim di setiap ketikan.
		this.timer = setTimeout(() => {
			// Ambil hasil suggestion setelah jeda debounce.
			this.fetchResults();
		}, 250);
	},

	// Ambil hasil suggestion dari backend.
	async fetchResults() {
		// Simpan query saat ini untuk mencegah race condition.
		const currentQuery = this.query;
		// Jika masih ada request sebelumnya, batalkan dulu.
		if (this.controller) {
			this.controller.abort();
		}

		// Buat controller baru untuk request yang akan dikirim.
		this.controller = new AbortController();

		// Mulai blok try-catch untuk menangani request async.
		try {
			// Kirim GET request ke endpoint suggestion dengan header JSON.
			const response = await fetch(this.buildQueryUrl(this.suggestUrl, currentQuery), {
				headers: {
					// Beritahu server bahwa frontend mengharapkan JSON.
					Accept: 'application/json',
				},
				// Pasang signal abort agar request bisa dibatalkan.
				signal: this.controller.signal,
			});

			// Abaikan jika response gagal atau query sudah berubah saat response datang.
			if (!response.ok || this.query !== currentQuery) {
				return;
			}

			// Parse payload JSON dari server.
			const payload = await response.json();
			// Pastikan data hasil berupa array, fallback ke array kosong.
			const items = Array.isArray(payload.data) ? payload.data : [];
			// Simpan hasil suggestion ke state.
			this.results = items;
			// Pakai URL dari server, atau fallback bangun URL search penuh.
			this.seeMoreUrl = payload.see_more_url || this.buildQueryUrl(this.searchUrl, currentQuery);
			// Tandai bahwa pencarian sudah dilakukan.
			this.hasSearched = true;
			// Tampilkan dropdown hasil.
			this.open = true;
		// Tangani error jaringan/abort.
		} catch (error) {
			// Jika error karena abort, abaikan tanpa mengubah UI error.
			if (error.name === 'AbortError') {
				return;
			}
			// Set pesan error untuk ditampilkan ke user.
			this.error = 'Gagal memuat saran.';
			// Kosongkan hasil karena request gagal.
			this.results = [];
			// Tetap tandai sudah mencoba mencari.
			this.hasSearched = true;
			// Tetap buka dropdown agar pesan error terlihat.
			this.open = true;
		// Blok finally selalu dijalankan, baik sukses maupun gagal.
		} finally {
			// Matikan loading hanya jika query belum berubah.
			if (this.query === currentQuery) {
				this.loading = false;
			}
		}
	},
}));

// Jalankan Alpine setelah semua komponen didaftarkan.
Alpine.start();
