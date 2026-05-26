<x-app-layout>
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Mutasi Stok</h1>
            <p class="text-sm text-gray-600">Riwayat perubahan stok lengkap</p>
        </div>

        @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
            <a href="{{ route('mutations.export', request()->query()) }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium hover:bg-gray-50">
                Export CSV
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('mutations.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <label class="block text-sm font-medium mb-1" for="action">Jenis Aksi</label>
            <select id="action" name="action" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">
                <option value="">Semua</option>
                @foreach (['Barang Masuk','Barang Keluar'] as $a)
                    <option value="{{ $a }}" @selected(request('action') === $a)>{{ $a }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="from">Dari</label>
            <input id="from" name="from" type="date" value="{{ request('from') }}" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="to">Sampai</label>
            <input id="to" name="to" type="date" value="{{ request('to') }}" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
        </div>
        <div class="flex items-end gap-2">
            <button class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800" type="submit">Filter</button>
            <a class="rounded-md border border-gray-300 px-4 py-2 text-sm" href="{{ route('mutations.index') }}">Reset</a>
        </div>
    </form>

    <div class="bg-white border border-emerald-100 rounded-lg overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-emerald-50 text-emerald-900">
                <tr>
                    <th class="text-left font-medium px-4 py-3">Waktu</th>
                    <th class="text-left font-medium px-4 py-3">Barang</th>
                    <th class="text-left font-medium px-4 py-3">Aksi</th>
                    <th class="text-left font-medium px-4 py-3">Before</th>
                    <th class="text-left font-medium px-4 py-3">After</th>
                    <th class="text-left font-medium px-4 py-3">Delta</th>
                    <th class="text-left font-medium px-4 py-3">User</th>
                    <th class="text-left font-medium px-4 py-3">Catatan</th>
                    <th class="text-right font-medium px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-emerald-100">
                @forelse ($mutations as $m)
                    <tr>
                        <td class="px-4 py-3">{{ $m->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $m->item_name_snapshot ?? $m->item?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $m->action }}</td>
                        <td class="px-4 py-3">{{ $m->stock_before }}</td>
                        <td class="px-4 py-3">{{ $m->stock_after }}</td>
                        <td class="px-4 py-3">{{ $m->delta }}</td>
                        <td class="px-4 py-3">{{ $m->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $m->note ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('mutations.show', $m) }}"
                               class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-emerald-200 bg-emerald-50/40 text-emerald-900 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200/60"
                               title="Detail" aria-label="Detail">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-gray-500" colspan="9">Belum ada mutasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $mutations->links() }}</div>
</x-app-layout>
