<x-app-layout>
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Detail Barang</h1>
            <p class="text-sm text-gray-600">Informasi barang</p>
        </div>
        <a href="{{ route('items.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Kembali</a>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="text-sm text-gray-500">Nama</div>
            <div class="font-medium">{{ $item->name }}</div>

            <div class="mt-3 text-sm text-gray-500">SKU</div>
            <div class="font-medium">{{ $item->sku }}</div>

            <div class="mt-3 text-sm text-gray-500">Kategori</div>
            <div class="font-medium">{{ $item->category?->name }}</div>

            <div class="mt-3 text-sm text-gray-500">Stok</div>
            <div class="font-medium">{{ $item->stock }} {{ $item->unit }}</div>

            <div class="mt-3 text-sm text-gray-500">Deskripsi</div>
            <div class="text-sm text-gray-800">{{ $item->description ?? '-' }}</div>
        </div>

        <div>
            <div class="text-sm text-gray-500 mb-2">Gambar</div>
            @if ($item->image_path)
                <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->name }}" class="w-full max-w-sm rounded-lg border object-cover" />
            @else
                <div class="text-sm text-gray-500">Tidak ada gambar.</div>
            @endif
        </div>
    </div>
</x-app-layout>
