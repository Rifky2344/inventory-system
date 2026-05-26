<x-app-layout>
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Edit Transaksi</h1>
        <p class="text-sm text-gray-600">Anda bisa ubah qty (stok akan disesuaikan)</p>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
        <div class="mb-4 text-sm text-gray-700">
            <div><span class="text-gray-500">No:</span> <span class="font-medium">{{ $transaction->transaction_number }}</span></div>
            <div><span class="text-gray-500">Jenis:</span> <span class="font-medium">{{ strtoupper($transaction->type) }}</span></div>
            <div><span class="text-gray-500">Barang:</span> <span class="font-medium">{{ $transaction->item?->name }}</span></div>
        </div>

        <form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1" for="quantity">Qty</label>
                <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', $transaction->quantity) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                @error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="transaction_date">Tanggal</label>
                <input id="transaction_date" name="transaction_date" type="date" value="{{ old('transaction_date', $transaction->transaction_date?->toDateString()) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                @error('transaction_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="note">Catatan</label>
                <textarea id="note" name="note" rows="3" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">{{ old('note', $transaction->note) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="proof">Bukti (opsional)</label>
                <input id="proof" name="proof" type="file" class="block w-full text-sm" />
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('transactions.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Batal</a>
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
