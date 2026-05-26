<x-app-layout>
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Detail Mutasi</h1>
            <p class="text-sm text-gray-600">{{ $mutation->created_at?->format('Y-m-d H:i') }}</p>
        </div>
        <a href="{{ route('mutations.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Kembali</a>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-6 text-sm shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="text-gray-500">Barang</div>
                <div class="font-medium">
                    {{ $mutation->item_name_snapshot ?? $mutation->item?->name ?? '-' }}
                    ({{ $mutation->item_sku_snapshot ?? $mutation->item?->sku ?? '-' }})
                </div>

                <div class="mt-3 text-gray-500">Aksi</div>
                <div class="font-medium">{{ $mutation->action }}</div>

                <div class="mt-3 text-gray-500">User</div>
                <div class="font-medium">{{ $mutation->user?->name ?? '-' }}</div>

                <div class="mt-3 text-gray-500">Catatan</div>
                <div class="text-gray-800">{{ $mutation->note ?? '-' }}</div>
            </div>

            <div>
                <div class="text-gray-500">Stock Before</div>
                <div class="font-medium">{{ $mutation->stock_before }}</div>

                <div class="mt-3 text-gray-500">Stock After</div>
                <div class="font-medium">{{ $mutation->stock_after }}</div>

                <div class="mt-3 text-gray-500">Delta</div>
                <div class="font-medium">{{ $mutation->delta }}</div>

                <div class="mt-3 text-gray-500">Transaksi terkait</div>
                @if ($mutation->transaction)
                    <div class="font-medium">
                        <a href="{{ route('transactions.show', $mutation->transaction) }}" class="text-emerald-800 hover:underline">{{ $mutation->transaction->transaction_number }}</a>
                    </div>
                @else
                    <div class="text-gray-700">-</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
