<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Tambah Aktor') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<form method="POST" action="{{ url('/admin/actors') }}" class="space-y-6">
						@csrf

						<div>
							<x-input-label for="name" :value="__('Nama Aktor')" />
							<x-text-input
								id="name"
								name="name"
								type="text"
								class="block mt-1 w-full"
								:value="old('name')"
								maxlength="100"
								required
								autofocus
							/>
							<x-input-error :messages="$errors->get('name')" class="mt-2" />
						</div>

						<div>
							<x-input-label for="photo_path" :value="__('Path Foto (Opsional)')" />
							<x-text-input
								id="photo_path"
								name="photo_path"
								type="text"
								class="block mt-1 w-full"
								:value="old('photo_path')"
								placeholder="contoh: actors/tom-hanks.jpg"
							/>
							<x-input-error :messages="$errors->get('photo_path')" class="mt-2" />
						</div>

						<div class="flex items-center gap-3">
							<x-primary-button>
								{{ __('Simpan') }}
							</x-primary-button>

							<a
								href="{{ url('/admin/actors') }}"
								class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
							>
								{{ __('Batal') }}
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
