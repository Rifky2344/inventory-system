<x-app-layout>
    <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold">Kategori</h1>
            <p class="text-sm text-gray-600">Kelola kategori dan sub-kategori</p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
            <form method="GET" action="{{ route('categories.index') }}" class="flex items-center gap-2">
                <input
                    name="q"
                    value="{{ $q ?? request('q') }}"
                    placeholder="Cari kategori..."
                    class="w-64 rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200/50"
                />
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Cari</button>
                @if (trim((string) ($q ?? request('q'))) !== '')
                    <a href="{{ route('categories.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
                @endif
            </form>

            @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                <a href="{{ route('categories.create') }}" class="inline-flex items-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
                    Tambah Kategori
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-emerald-50 text-emerald-900">
                <tr>
                    <th class="text-left font-medium px-4 py-3">Nama</th>
                    <th class="text-left font-medium px-4 py-3">Parent</th>
                    <th class="text-left font-medium px-4 py-3">Deskripsi</th>
                    @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                        <th class="text-right font-medium px-4 py-3">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-emerald-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $category->parent?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $category->description ?? '-' }}</td>
                        @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-amber-200 bg-amber-50/40 text-amber-900 hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-200/60"
                                       title="Edit" aria-label="Edit">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                        </svg>
                                    </a>

                                    <form class="inline" method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-red-200 bg-red-50 text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-200/60"
                                                title="Hapus" aria-label="Hapus">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M3 6h18" />
                                                <path d="M8 6V4h8v2" />
                                                <path d="M6 6l1 16h10l1-16" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-gray-500" colspan="4">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</x-app-layout>
