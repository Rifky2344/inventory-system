<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">Riwayat Mutasi Stok</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Log sistem terperinci dari setiap perubahan pada inventaris.</p>
            </div>

            @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('mutations.export', request()->query()) }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 px-5 py-3 font-bold text-white shadow-lg shadow-teal-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-teal-500/40 focus:outline-none focus:ring-4 focus:ring-teal-500/30">
                        <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Export Riwayat</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Filter Form -->
    <div class="mb-6 bg-white/70 backdrop-blur-xl border border-white/60 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative z-20">
        <form method="GET" action="{{ route('mutations.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="action">Jenis Aksi Mutasi</label>
                <select id="action" name="action" class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm cursor-pointer">
                    <option value="">Semua Jenis Aksi</option>
                    @foreach (['Barang Masuk','Barang Keluar'] as $a)
                        <option value="{{ $a }}" @selected(request('action') === $a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="from">Dari Tanggal</label>
                <input id="from" name="from" type="date" value="{{ request('from') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3 px-4 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm" />
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="to">Sampai Tanggal</label>
                <input id="to" name="to" type="date" value="{{ request('to') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3 px-4 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm" />
            </div>
            
            <div class="md:col-span-3 flex items-center gap-3">
                <button type="submit" class="flex-1 rounded-2xl bg-slate-800 px-4 py-3 text-sm font-bold text-white hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-500/20 transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                    Filter Log
                </button>
                <a href="{{ route('mutations.index') }}" class="p-3 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20" title="Reset Filter">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Modern Table -->
    <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Waktu</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Barang</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Aksi Sistem</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Perubahan (Delta)</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">User</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($mutations as $m)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">{{ $m->created_at?->format('d M Y') }}</span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $m->created_at?->format('H:i:s') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800">{{ $m->item_name_snapshot ?? $m->item?->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($m->action === 'Barang Masuk')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                        MASUK
                                    </span>
                                @elseif($m->action === 'Barang Keluar')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                                        KELUAR
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $m->action }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-3 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                                    <span class="text-xs font-bold text-slate-400">{{ $m->stock_before }}</span>
                                    <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    <span class="text-xs font-bold text-slate-600">{{ $m->stock_after }}</span>
                                    
                                    <div class="ml-2 pl-2 border-l border-slate-200">
                                        <span class="font-extrabold {{ $m->delta > 0 ? 'text-emerald-600' : ($m->delta < 0 ? 'text-rose-600' : 'text-slate-500') }}">
                                            {{ $m->delta > 0 ? '+' : '' }}{{ $m->delta }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">
                                        {{ substr($m->user?->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-600">{{ $m->user?->name ?? 'Sistem' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('mutations.show', $m) }}"
                                   class="inline-flex p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-teal-600 hover:bg-teal-50 hover:border-teal-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                   title="Lihat Log Detail">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Belum Ada Riwayat Mutasi</p>
                                    <p class="mt-1 text-sm text-slate-500">Log mutasi akan otomatis tercatat ketika terjadi perubahan stok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination & Per Page -->
    <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex-grow">
            {{ $mutations->links() }}
        </div>
        <div class="flex items-center gap-3 bg-white/60 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/60 shadow-sm">
            <label for="per_page_mutations" class="text-sm font-bold text-slate-500">Tampilkan:</label>
            <select id="per_page_mutations" class="rounded-xl border border-slate-200 bg-white py-1.5 pl-3 pr-8 text-sm font-medium text-slate-700 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 cursor-pointer" onchange="window.location.href = this.value">
                @foreach([15, 25, 50, 100] as $size)
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => $size]) }}" @selected(request('per_page', 15) == $size)>
                        {{ $size }} log
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</x-app-layout>
