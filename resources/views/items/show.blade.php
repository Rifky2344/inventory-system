<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Detail Barang</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Melihat informasi lengkap dan status stok barang.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('items.index') }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 text-sm font-bold text-slate-600">
                    <svg class="w-5 h-5 text-slate-400 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
                
                @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                    <a href="{{ route('items.edit', $item) }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 shadow-[0_4px_20px_rgba(16,185,129,0.3)] border border-transparent hover:shadow-emerald-500/40 hover:scale-105 transition-all duration-300 text-sm font-bold text-white">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Edit Barang
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative p-8 sm:p-10">
            <!-- Decorative elements -->
            <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-gradient-to-br from-emerald-100/50 to-teal-100/50 blur-3xl mix-blend-multiply pointer-events-none"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 relative z-10">
                
                <!-- Left Side: Product Image -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="relative w-full aspect-square rounded-[2rem] overflow-hidden bg-slate-50 border border-slate-100 shadow-inner group">
                        @if ($item->image_path)
                            <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                <span class="text-white font-bold text-sm bg-black/30 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20">Foto Produk</span>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                <svg class="w-24 h-24 mb-4 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="font-bold text-slate-400">Belum Ada Gambar</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Quick Stock Widget (Moved under image for balance) -->
                    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50/80 to-teal-50/40 p-6 shadow-sm relative overflow-hidden">
                        <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4">
                            <svg class="w-24 h-24 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-extrabold text-emerald-800/60 uppercase tracking-widest mb-1">Status Stok</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-emerald-700 tracking-tight">{{ $item->stock }}</span>
                                    <span class="text-sm font-bold text-emerald-600">{{ $item->unit }}</span>
                                </div>
                            </div>
                            
                            @php $threshold = (int) config('inventory.low_stock_threshold', 10); @endphp
                            @if($item->stock <= $threshold)
                                <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-500 shadow-sm border border-rose-200">
                                    <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-200">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Side: Details -->
                <div class="lg:col-span-7 flex flex-col gap-8">
                    <!-- Title & Header Info -->
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-600 border border-slate-200 tracking-wide">
                                {{ $item->category?->name ?? 'Tanpa Kategori' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 tracking-wide uppercase font-mono">
                                {{ $item->sku }}
                            </span>
                        </div>
                        <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight leading-tight">{{ $item->name }}</h2>
                    </div>

                    <!-- Description Block -->
                    <div class="bg-white/60 rounded-2xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Deskripsi Barang
                        </h3>
                        <p class="text-slate-600 leading-relaxed text-sm font-medium whitespace-pre-wrap">
                            {{ $item->description ?: 'Tidak ada deskripsi yang disediakan untuk barang ini.' }}
                        </p>
                    </div>

                    <!-- System Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50/80 rounded-2xl p-5 border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ditambahkan Pada</p>
                            <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $item->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="bg-slate-50/80 rounded-2xl p-5 border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Terakhir Diperbarui</p>
                            <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                {{ $item->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
