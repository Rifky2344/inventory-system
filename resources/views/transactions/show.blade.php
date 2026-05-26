<x-app-layout>
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Detail Transaksi</h1>
            <p class="text-sm text-gray-600">{{ $transaction->transaction_number }}</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Kembali</a>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <div class="text-gray-500">Jenis</div>
                <div class="font-medium">{{ strtoupper($transaction->type) }}</div>

                <div class="mt-3 text-gray-500">Tanggal</div>
                <div class="font-medium">{{ $transaction->transaction_date?->format('Y-m-d') }}</div>

                <div class="mt-3 text-gray-500">Barang</div>
                <div class="font-medium">
                    {{ $transaction->item_name_snapshot ?? $transaction->item?->name ?? '-' }}
                    ({{ $transaction->item_sku_snapshot ?? $transaction->item?->sku ?? '-' }})
                </div>

                <div class="mt-3 text-gray-500">Qty</div>
                <div class="font-medium">{{ $transaction->quantity }}</div>

                <div class="mt-3 text-gray-500">Input oleh</div>
                <div class="font-medium">{{ $transaction->user?->name ?? '-' }}</div>

                <div class="mt-3 text-gray-500">Catatan</div>
                <div class="text-gray-800">{{ $transaction->note ?? '-' }}</div>
            </div>

            <div>
                <div class="text-gray-500">Bukti</div>
                @if ($transaction->proof_path)
                    @php
                        $url = asset('storage/'.$transaction->proof_path);
                        $ext = strtolower(pathinfo($transaction->proof_path, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif'], true);
                    @endphp

                    <div class="mt-2">
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="text-emerald-800 hover:underline">Buka bukti</a>
                    </div>

                    @if ($isImage)
                        <div class="mt-3">
                            <img src="{{ $url }}" alt="Bukti transaksi" class="w-full max-w-lg rounded-lg border object-cover" />
                        </div>
                    @else
                        <div class="mt-3 text-sm text-gray-600">Preview hanya untuk file gambar. Silakan klik “Buka bukti”.</div>
                    @endif
                @else
                    <div class="mt-2 text-sm text-gray-600">Tidak ada bukti.</div>
                @endif
            </div>
        </div>

        @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
            <div class="mt-6 flex items-center gap-2">
                <a href="{{ route('transactions.edit', $transaction) }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Edit</a>
            </div>
        @endif
    </div>
</x-app-layout>
