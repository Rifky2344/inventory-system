<x-app-layout>
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold leading-tight text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600">Ringkasan gudang</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
            <div class="text-sm font-medium text-gray-600">Total barang</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalItems }}</div>
        </div>
        <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
            <div class="text-sm font-medium text-gray-600">Total kategori</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalCategories }}</div>
        </div>
        <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
            <div class="text-sm font-medium text-gray-600">Barang masuk hari ini</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $inToday }}</div>
        </div>
        <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
            <div class="text-sm font-medium text-gray-600">Barang keluar hari ini</div>
            <div class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">{{ $outToday }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Barang stok rendah</h2>
                    <div class="mt-0.5 text-xs text-gray-500">Ambang: {{ $lowStockThreshold }}</div>
                </div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($lowStockItems as $item)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                            <div class="text-xs text-gray-500">SKU: {{ $item->sku }}</div>
                        </div>
                        <div class="text-sm font-semibold">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs border {{ $item->stock <= $lowStockThreshold ? 'border-amber-300 bg-amber-50 text-amber-800' : 'border-emerald-200 bg-emerald-50/40 text-emerald-900' }}">
                                Stok: {{ $item->stock }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-sm text-gray-500">Tidak ada barang stok rendah.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-900">Grafik mingguan transaksi</h2>
                <div class="text-xs text-gray-500">Minggu ini: {{ $weekly->count() }} baris</div>
            </div>

            <div class="rounded-md border border-dashed border-emerald-200 bg-emerald-50/40 p-4">
                <p class="text-sm text-gray-700">(Placeholder) Implementasi chart akan ditambahkan setelah modul transaksi UI selesai.</p>
            </div>
        </div>
    </div>
</x-app-layout>
