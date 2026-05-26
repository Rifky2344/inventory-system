<x-app-layout>
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold leading-tight text-gray-900">Transaksi Stok</h1>
            <p class="mt-1 text-sm text-gray-600">Barang masuk & keluar</p>
        </div>

        <div class="flex gap-2">
            @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                <a href="{{ route('transactions.export', request()->query()) }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Export CSV
                </a>
            @endif
            <a href="{{ route('transactions.in.create') }}" class="rounded-md bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">Barang Masuk</a>
            <a href="{{ route('transactions.out.create') }}" class="rounded-md bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700">Barang Keluar</a>
        </div>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="type">Jenis</label>
                <select id="type" name="type" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">
                    <option value="">Semua</option>
                    <option value="in" @selected(request('type') === 'in')>IN</option>
                    <option value="out" @selected(request('type') === 'out')>OUT</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="from">Dari</label>
                <input id="from" name="from" type="date" value="{{ request('from') }}" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="to">Sampai</label>
                <input id="to" name="to" type="date" value="{{ request('to') }}" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
            </div>
            <div class="flex items-end gap-2">
                <button class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800" type="submit">Filter</button>
                <a class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" href="{{ route('transactions.index') }}">Reset</a>
            </div>
        </form>
    </div>

    @error('transaction')
        <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md p-3">{{ $message }}</div>
    @enderror

    <div class="bg-white border border-emerald-100 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-emerald-50 text-emerald-900">
                <tr>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">No</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Jenis</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Tanggal</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Barang</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Qty</th>
                    <th class="text-left font-semibold uppercase tracking-wide text-xs px-4 py-3.5">User</th>
                    <th class="text-right font-semibold uppercase tracking-wide text-xs px-4 py-3.5">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-emerald-100">
                @forelse ($transactions as $t)
                    <tr class="odd:bg-white even:bg-emerald-50/20 hover:bg-emerald-50/40">
                        <td class="px-4 py-3.5 font-medium text-gray-900 whitespace-nowrap">{{ $t->transaction_number }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs border {{ $t->type === 'in' ? 'border-green-300 bg-green-50 text-green-800' : 'border-amber-300 bg-amber-50 text-amber-800' }}">
                                {{ strtoupper($t->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap text-gray-700">{{ $t->transaction_date?->format('Y-m-d') }}</td>
                        <td class="px-4 py-3.5 text-gray-900">{{ $t->item_name_snapshot ?? $t->item?->name ?? '-' }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap text-gray-700">{{ $t->quantity }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap text-gray-700">{{ $t->user?->name }}</td>
                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-2">
                                <a href="{{ route('transactions.show', $t) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-emerald-200 bg-emerald-50/40 text-emerald-900 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200/60"
                                   title="Detail" aria-label="Detail">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>
                                @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                                    <a href="{{ route('transactions.edit', $t) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-amber-200 bg-amber-50/40 text-amber-900 hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-200/60"
                                       title="Edit" aria-label="Edit">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('transactions.destroy', $t) }}" onsubmit="return confirm('Hapus transaksi ini? Stok akan direversal.')">
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
                                @else
                                    <span class="px-2 py-1 text-xs text-gray-500">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-gray-500" colspan="7">Belum ada transaksi.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</x-app-layout>
