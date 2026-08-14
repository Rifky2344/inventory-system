<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">Detail Log Mutasi</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Rekaman sistem untuk perubahan stok pada <span class="font-bold text-slate-700">{{ $mutation->created_at?->format('d M Y, H:i') }}</span></p>
            </div>
            
            <a href="{{ route('mutations.index') }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 text-sm font-bold text-slate-600">
                <svg class="w-5 h-5 text-slate-400 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Riwayat
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Main Details -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative p-8">
                    <!-- Decorative Graphic -->
                    <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-gradient-to-br from-teal-100/50 to-cyan-100/50 blur-3xl mix-blend-multiply pointer-events-none"></div>
                    
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Informasi Umum
                    </h3>
                    
                    <div class="flex flex-col gap-8 relative z-10">
                        <!-- Item -->
                        <div>
                            <span class="block text-sm font-bold text-slate-500 mb-1">Barang Terkait</span>
                            <div class="flex flex-col gap-1">
                                <span class="text-2xl font-extrabold text-slate-800">{{ $mutation->item_name_snapshot ?? $mutation->item?->name ?? '-' }}</span>
                                <span class="text-sm font-bold text-slate-500 font-mono tracking-wide">SKU: {{ $mutation->item_sku_snapshot ?? $mutation->item?->sku ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <!-- Action Type -->
                            <div>
                                <span class="block text-sm font-bold text-slate-500 mb-2">Aksi Sistem</span>
                                @if($mutation->action === 'Barang Masuk')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-sm font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                        BARANG MASUK
                                    </span>
                                @elseif($mutation->action === 'Barang Keluar')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-sm font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                                        BARANG KELUAR
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-sm font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $mutation->action }}
                                    </span>
                                @endif
                            </div>

                            <!-- Related Transaction -->
                            <div>
                                <span class="block text-sm font-bold text-slate-500 mb-2">Transaksi Terkait</span>
                                @if ($mutation->transaction)
                                    <div class="inline-flex items-center gap-2 group">
                                        <div class="px-4 py-1.5 bg-sky-50 border border-sky-200 text-sky-700 rounded-xl font-bold font-mono text-sm">
                                            {{ $mutation->transaction->transaction_number }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium italic">Tidak ada referensi transaksi.</span>
                                @endif
                            </div>
                        </div>

                        <!-- Note / Remarks -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100/80 shadow-inner">
                            <span class="block text-sm font-bold text-slate-400 mb-2">Catatan Mutasi</span>
                            <p class="text-slate-700 leading-relaxed font-medium">
                                {{ $mutation->note ?: 'Tidak ada catatan tambahan yang direkam oleh sistem.' }}
                            </p>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Right Column: Stock Changes & System Meta -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Stock Delta Card -->
                <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                        Perubahan Stok
                    </h3>
                    
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-between w-full mb-6">
                            <div class="flex flex-col items-center p-4 bg-slate-50 rounded-2xl w-24 border border-slate-100">
                                <span class="text-xs font-bold text-slate-400 mb-1">Stock Awal</span>
                                <span class="text-xl font-extrabold text-slate-600">{{ $mutation->stock_before }}</span>
                            </div>
                            
                            <div class="flex flex-col items-center">
                                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                            
                            <div class="flex flex-col items-center p-4 bg-slate-100 rounded-2xl w-24 border border-slate-200">
                                <span class="text-xs font-bold text-slate-500 mb-1">Stock Akhir</span>
                                <span class="text-xl font-extrabold text-slate-800">{{ $mutation->stock_after }}</span>
                            </div>
                        </div>

                        <div class="w-full flex items-center justify-between p-4 rounded-2xl border border-slate-200 {{ $mutation->delta > 0 ? 'bg-emerald-50/50' : ($mutation->delta < 0 ? 'bg-rose-50/50' : 'bg-slate-50') }}">
                            <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">Delta Perubahan</span>
                            <span class="text-2xl font-extrabold {{ $mutation->delta > 0 ? 'text-emerald-600' : ($mutation->delta < 0 ? 'text-rose-600' : 'text-slate-500') }}">
                                {{ $mutation->delta > 0 ? '+' : '' }}{{ $mutation->delta }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Meta Card -->
                <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Meta Sistem
                    </h3>
                    
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-extrabold text-lg border-2 border-white shadow-sm shrink-0">
                                {{ substr($mutation->user?->name ?? '?', 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aktor / User</span>
                                <span class="text-base font-bold text-slate-700">{{ $mutation->user?->name ?? 'Sistem Otomatis' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col pt-5 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">ID Rekaman Unik</span>
                            <span class="text-sm font-medium text-slate-600 font-mono tracking-wide">{{ $mutation->id }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
