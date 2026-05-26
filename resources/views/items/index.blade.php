<x-app-layout>
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold leading-tight text-gray-900">Barang</h1>
            <p class="mt-1 text-sm text-gray-600">Data barang, stok, dan pencarian</p>
        </div>

        @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
            <a href="{{ route('items.create') }}" class="inline-flex items-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
                Tambah Barang
            </a>
        @endif
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('items.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="q">Search</label>
                <input id="q" name="q" value="{{ request('q') }}" placeholder="Nama / SKU"
                    class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="category_id">Kategori</label>
                <select id="category_id" name="category_id" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">
                    <option value="">Semua</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800" type="submit">Filter</button>
                <a class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" href="{{ route('items.index') }}">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-emerald-50 text-emerald-900">
                <tr>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Barang</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">SKU</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Kategori</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Stok</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Satuan</th>
                    <th class="text-right font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-emerald-100">
                @forelse ($items as $item)
                    <tr class="odd:bg-white even:bg-emerald-50/20 hover:bg-emerald-50/40">
                        <td class="px-4 py-3.5 font-medium text-gray-900 whitespace-nowrap">{{ $item->name }}</td>
                        <td class="px-4 py-3.5 text-gray-700 whitespace-nowrap">{{ $item->sku }}</td>
                        <td class="px-4 py-3.5 text-gray-700">{{ $item->category?->name }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @php $threshold = (int) config('inventory.low_stock_threshold', 10); @endphp
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs border {{ $item->stock <= $threshold ? 'border-amber-300 bg-amber-50 text-amber-800' : 'border-emerald-200 bg-emerald-50/40 text-emerald-900' }}">
                                {{ $item->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-gray-700 whitespace-nowrap">{{ $item->unit }}</td>
                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-2">
                                <a href="{{ route('items.show', $item) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-emerald-200 bg-emerald-50/40 text-emerald-900 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200/60"
                                   title="Detail" aria-label="Detail">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>
                                @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                                    <a href="{{ route('items.edit', $item) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-amber-200 bg-amber-50/40 text-amber-900 hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-200/60"
                                       title="Edit" aria-label="Edit">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Hapus barang ini?')">
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
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-gray-500" colspan="6">Belum ada barang.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</x-app-layout>
