<x-app-layout>
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Edit Barang</h1>
        <p class="text-sm text-gray-600">Stok tidak bisa diedit manual</p>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
        <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="name">Nama Barang</label>
                    <input id="name" name="name" value="{{ old('name', $item->name) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="sku">SKU</label>
                    <input id="sku" name="sku" value="{{ old('sku', $item->sku) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                    @error('sku')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $item->category_id) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="unit">Satuan</label>
                    <input id="unit" name="unit" value="{{ old('unit', $item->unit) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                    @error('unit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">{{ old('description', $item->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Stok</label>
                <div class="text-sm text-gray-800">{{ $item->stock }}</div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="image">Gambar (opsional)</label>
                @if ($item->image_path)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->name }}" class="h-20 w-20 rounded-md border object-cover" />
                    </div>
                @endif
                <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" class="block w-full text-sm" />
                @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('items.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Batal</a>
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
