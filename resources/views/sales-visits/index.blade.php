<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-violet-600 to-fuchsia-600 bg-clip-text text-transparent">Kunjungan Sales</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Pantau aktivitas kunjungan sales dan progress negosiasi ke pelanggan.</p>
            </div>

            @if (in_array(auth()->user()->role?->slug, ['sales', 'admin', 'super-admin'], true))
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('sales-visits.export', request()->query()) }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-white px-5 py-3 font-bold text-slate-600 border border-slate-200 hover:border-violet-200 hover:bg-violet-50 hover:text-violet-700 transition-all duration-300 shadow-sm focus:outline-none focus:ring-4 focus:ring-violet-500/10">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Log
                    </a>
                    
                    <a href="{{ route('sales-visits.create') }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 to-fuchsia-600 px-5 py-3 font-bold text-white shadow-lg shadow-violet-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-violet-500/40 focus:outline-none focus:ring-4 focus:ring-violet-500/30">
                        <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Catat Kunjungan</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 flex items-center gap-3 shadow-sm" x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms>
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-grow text-sm font-bold text-emerald-800">{{ session('success') }}</div>
            <button @click="show = false" class="p-1 rounded-full text-emerald-500 hover:bg-emerald-200 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="mb-6 bg-white/70 backdrop-blur-xl border border-white/60 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative z-20">
        <form method="GET" action="{{ route('sales-visits.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="q">Cari Pelanggan</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-violet-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="Ketik nama pelanggan..."
                        class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3 pl-12 pr-4 text-slate-700 font-medium focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all shadow-sm" />
                </div>
            </div>
            
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="status">Status Follow Up</label>
                <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3 px-4 text-slate-700 font-medium focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all shadow-sm cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                </select>
            </div>

            <div class="md:col-span-3 flex items-center gap-3">
                <button type="submit" class="flex-1 rounded-2xl bg-slate-800 px-4 py-3 text-sm font-bold text-white hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-500/20 transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                    Filter
                </button>
                <a href="{{ route('sales-visits.index') }}" class="p-3 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20" title="Reset Pencarian">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Info Kunjungan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Sales Person</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Hasil / Kesimpulan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Follow Up</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($visits as $visit)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('customers.show', $visit->customer) }}" class="font-extrabold text-slate-800 hover:text-violet-600 transition-colors text-base flex items-center gap-1.5">
                                        {{ $visit->customer->name }}
                                    </a>
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-xs font-bold">{{ $visit->visit_date->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase border border-white shadow-sm shrink-0">
                                        {{ substr($visit->sales->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-700">{{ $visit->sales->name }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="max-w-[250px] overflow-hidden">
                                    <p class="text-sm font-medium text-slate-600 truncate" title="{{ $visit->visit_result }}">
                                        {{ $visit->visit_result }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($visit->follow_up_status === 'Completed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Tertunda
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a href="{{ route('sales-visits.show', $visit) }}"
                                       class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-violet-600 hover:bg-violet-50 hover:border-violet-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500/20"
                                       title="Detail Kunjungan">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    
                                    @if (in_array(auth()->user()->role?->slug, ['sales', 'admin', 'super-admin'], true))
                                        <a href="{{ route('sales-visits.edit', $visit) }}"
                                           class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20"
                                           title="Edit Kunjungan">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                    @endif
                                    
                                    @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin'], true))
                                        <form method="POST" action="{{ route('sales-visits.destroy', $visit) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log kunjungan ini?')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                                                    title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Belum Ada Kunjungan Sales</p>
                                    <p class="mt-1 text-sm text-slate-500">Sesuaikan filter atau catat aktivitas kunjungan pertama.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex-grow">
            {{ $visits->links() }}
        </div>
    </div>
</x-app-layout>
