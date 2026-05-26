<x-app-layout>
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Tambah Kategori</h1>
        <p class="text-sm text-gray-600">Buat kategori atau sub-kategori</p>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1" for="name">Nama</label>
                <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="parent_id">Parent (opsional)</label>
                <select id="parent_id" name="parent_id" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">
                    <option value="">-</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('categories.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Batal</a>
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
