<x-app-layout>
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Input Barang Keluar</h1>
        <p class="text-sm text-gray-600">Validasi stok wajib; tidak boleh minus</p>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
        <form method="POST" action="{{ route('transactions.out.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1" for="item_id">Barang</label>
                <select id="item_id" name="item_id" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">
                    @foreach ($items as $i)
                        <option value="{{ $i->id }}" @selected(old('item_id') == $i->id)>{{ $i->name }} (Stok: {{ $i->stock }})</option>
                    @endforeach
                </select>
                @error('item_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="quantity">Jumlah</label>
                    <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                    @error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="transaction_date">Tanggal</label>
                    <input id="transaction_date" name="transaction_date" type="date" value="{{ old('transaction_date', now()->toDateString()) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                    @error('transaction_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="note">Catatan</label>
                <textarea id="note" name="note" rows="3" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">{{ old('note') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="proof">Bukti (opsional)</label>
                <input id="proof" name="proof" type="file" class="block w-full text-sm" />
                @error('proof')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('transactions.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Batal</a>
                <button type="submit" class="rounded-md bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
