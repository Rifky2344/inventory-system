<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent">Tambah Kategori</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Buat klasifikasi baru untuk mengorganisir inventaris Anda.</p>
            </div>
            <a href="{{ route('categories.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-sky-50 hover:border-sky-200 hover:shadow-sky-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-sky-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] overflow-hidden relative">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-sky-400 via-indigo-400 to-violet-400"></div>
            
            <form method="POST" action="{{ route('categories.store') }}" class="p-8 sm:p-10">
                @csrf

                <div class="space-y-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="name">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Elektronik, Minuman, Pakaian"
                            class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm" />
                        @error('name')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="category_code">Kode Kategori (Opsional)</label>
                        <input id="category_code" name="category_code" value="{{ old('category_code') }}" placeholder="Contoh: ELK, MNM, PKN"
                            class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm uppercase" />
                        <p class="mt-2 text-xs text-slate-500 font-medium">Digunakan untuk format otomatis SKU barang (Maks. 50 karakter).</p>
                        @error('category_code')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                    </div>



                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="description">Deskripsi (Opsional)</label>
                        <textarea id="description" name="description" rows="4" placeholder="Jelaskan detail kategori secara lengkap..."
                            class="w-full rounded-2xl border border-slate-200/70 bg-white py-4 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="mt-10 pt-6 flex items-center justify-end gap-4 border-t border-slate-100/80">
                    <a href="{{ route('categories.index') }}" class="px-6 py-3.5 rounded-2xl text-sm font-bold text-slate-500 bg-slate-50/50 hover:bg-slate-100 hover:text-slate-800 transition-all focus:outline-none focus:ring-4 focus:ring-slate-100">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-indigo-500 hover:from-sky-600 hover:to-indigo-600 shadow-lg shadow-sky-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-sky-500/40 focus:outline-none focus:ring-4 focus:ring-sky-500/30">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
