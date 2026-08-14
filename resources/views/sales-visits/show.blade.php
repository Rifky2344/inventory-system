<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-violet-600 to-fuchsia-600 bg-clip-text text-transparent">Laporan Detail Kunjungan</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Informasi mendalam mengenai pertemuan dengan klien dan langkah selanjutnya.</p>
            </div>
            
            <a href="{{ route('sales-visits.index') }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 text-sm font-bold text-slate-600">
                <svg class="w-5 h-5 text-slate-400 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Main Content -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <!-- Primary Record Card -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
                    <!-- Decorative Graphic -->
                    <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-gradient-to-br from-violet-100/50 to-fuchsia-100/50 blur-3xl mix-blend-multiply pointer-events-none"></div>

                    <div class="p-8 pb-0">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-100 to-fuchsia-100 flex items-center justify-center border-2 border-white shadow-sm shrink-0">
                                <svg class="w-7 h-7 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Entitas Bisnis / Pelanggan</h2>
                                <a href="{{ route('customers.show', $visit->customer) }}" class="text-2xl font-extrabold text-slate-800 hover:text-violet-600 transition-colors">
                                    {{ optional($visit->customer)->name ?? 'Pelanggan Tidak Ditemukan' }}
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col">
                                <span class="text-xs font-bold text-slate-400 mb-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> Kontak</span>
                                <span class="text-sm font-bold text-slate-700 font-mono tracking-wide">{{ optional($visit->customer)->contact ?? '-' }}</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col">
                                <span class="text-xs font-bold text-slate-400 mb-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Kota</span>
                                <span class="text-sm font-bold text-slate-700">{{ optional($visit->customer)->city ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 pt-6 relative z-10">
                        <div class="h-px w-full bg-slate-100 mb-6"></div>

                        <div class="mb-8">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Laporan Kunjungan Utama
                            </h3>
                            <div class="p-5 rounded-2xl bg-white border border-slate-200/60 shadow-sm">
                                <p class="text-slate-700 leading-relaxed font-medium whitespace-pre-wrap">{{ $visit->visit_result }}</p>
                            </div>
                        </div>

                        @if($visit->note)
                            <div>
                                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Catatan Internal Tim
                                </h3>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100/80 shadow-inner">
                                    <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-wrap">{{ $visit->note }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Metadata -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Status & Sales Card -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Informasi Eksekusi
                    </h3>

                    <div class="space-y-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-extrabold text-lg border-2 border-white shadow-sm shrink-0">
                                {{ substr(optional($visit->sales)->name ?? '?', 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sales Representative</span>
                                <span class="text-base font-bold text-slate-700">{{ optional($visit->sales)->name ?? 'Sales tidak ditemukan' }}</span>
                            </div>
                        </div>
                        
                        <div class="h-px w-full bg-slate-100"></div>
                        
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Jadwal Pelaksanaan</span>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-100 text-slate-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-sm font-bold">{{ optional($visit->visit_date)->format('d F Y') }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Status Follow Up</span>
                            @if($visit->follow_up_status === 'Completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Selesai (Completed)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Tertunda (Pending)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Tindakan Sistem
                    </h3>

                    <div class="flex flex-col gap-3">
                        @if (auth()->user()->role?->slug === 'sales' || auth()->user()->role?->slug === 'admin' || auth()->user()->role?->slug === 'super-admin')
                            @if(isset($visit->id))
                                <a href="{{ route('sales-visits.edit', ['sales_visit' => $visit->id]) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-amber-50 text-amber-600 font-bold hover:bg-amber-100 hover:text-amber-700 transition-colors border border-amber-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit Kunjungan
                                </a>
                            @else
                                <span class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-100 text-slate-400 font-bold border border-slate-200 cursor-not-allowed">
                                    Edit Kunjungan
                                </span>
                            @endif
                        @endif

                        @if (auth()->user()->role?->slug === 'admin' || auth()->user()->role?->slug === 'super-admin')
                            @if(isset($visit->id))
                                <form method="POST" action="{{ route('sales-visits.destroy', ['sales_visit' => $visit->id]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kunjungan ini?')" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-rose-50 text-rose-600 font-bold hover:bg-rose-100 hover:text-rose-700 transition-colors border border-rose-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus Permanen
                                    </button>
                                </form>
                            @else
                                <span class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-100 text-slate-400 font-bold border border-slate-200 cursor-not-allowed">
                                    Hapus Permanen
                                </span>
                            @endif
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <div class="flex flex-col gap-2 text-xs font-medium text-slate-400">
                            <div class="flex justify-between items-center">
                                <span>Record ID</span>
                                <span class="font-mono text-slate-500">{{ $visit->id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Tercatat</span>
                                <span class="text-slate-500">{{ optional($visit->created_at)->format('d M Y H:i') }}</span>
                            </div>
                            @if($visit->updated_at && $visit->created_at && $visit->updated_at != $visit->created_at)
                                <div class="flex justify-between items-center">
                                    <span>Diperbarui</span>
                                    <span class="text-slate-500">{{ optional($visit->updated_at)->format('d M Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
