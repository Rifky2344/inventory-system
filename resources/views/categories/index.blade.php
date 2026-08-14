<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent">Kategori Barang</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Struktur klasifikasi dan pengelompokan inventaris gudang.</p>
            </div>

            @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('categories.create') }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 px-5 py-3 font-bold text-white shadow-lg shadow-sky-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-sky-500/40 focus:outline-none focus:ring-4 focus:ring-sky-500/30">
                        <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Kategori</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-100 text-teal-700 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="font-medium text-sm">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Filter/Search Bar -->
    <div class="mb-6 bg-white/70 backdrop-blur-xl border border-white/60 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative z-20">
        <form method="GET" action="{{ route('categories.index') }}" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative group w-full sm:flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    name="q"
                    value="{{ $q ?? request('q') }}"
                    placeholder="Cari nama kategori atau deskripsi..."
                    class="w-full pl-12 rounded-2xl border border-slate-200 bg-white/50 py-3 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm"
                />
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none rounded-2xl bg-slate-800 px-6 py-3 text-sm font-bold text-white hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-500/20 transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                    Cari
                </button>
                @if (trim((string) ($q ?? request('q'))) !== '')
                    <a href="{{ route('categories.index') }}" class="p-3 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20" title="Reset Pencarian">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Modern Table -->
    <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Nama Kategori</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Kode</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Deskripsi</th>
                        @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                            <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($categories as $category)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-50 to-indigo-50 border border-sky-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-sky-400 group-hover:text-sky-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </div>
                                    <span class="font-bold text-slate-800 group-hover:text-sky-700 transition-colors">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($category->category_code)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase font-mono">
                                        {{ $category->category_code }}
                                    </span>
                                @else
                                    <span class="text-xs font-medium text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-500 whitespace-normal line-clamp-1 max-w-xs">{{ $category->description ?: '-' }}</span>
                            </td>
                            @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-sky-600 hover:bg-sky-50 hover:border-sky-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Kategori yang memiliki barang tidak dapat dihapus.')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                                                    title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true) ? '4' : '3' }}" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Belum Ada Kategori</p>
                                    <p class="mt-1 text-sm text-slate-500">Mulai dengan menambahkan kategori untuk mengelompokkan barang.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @endif
</x-app-layout>
