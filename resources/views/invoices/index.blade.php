<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-teal-600 to-indigo-600 bg-clip-text text-transparent">Daftar Invoice</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Kelola tagihan, pantau pembayaran, dan status piutang pelanggan.</p>
            </div>

            @if (in_array(auth()->user()->role?->slug, ['sales', 'admin', 'super-admin'], true))
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('invoices.export', request()->query()) }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-white px-5 py-3 font-bold text-slate-600 border border-slate-200 hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 transition-all duration-300 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/10">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </a>
                    
                    <a href="{{ route('invoices.create') }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-teal-500 to-indigo-600 px-5 py-3 font-bold text-white shadow-lg shadow-teal-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-teal-500/40 focus:outline-none focus:ring-4 focus:ring-teal-500/30">
                        <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Buat Invoice Baru</span>
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
        <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="q">Pencarian Umum</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-teal-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="No. Invoice atau Nama Pelanggan..."
                        class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3 pl-12 pr-4 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm" />
                </div>
            </div>
            
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="status">Status Pembayaran</label>
                <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3 px-4 text-slate-700 font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Unpaid" {{ request('status') === 'Unpaid' ? 'selected' : '' }}>Belum Dibayar (Unpaid)</option>
                    <option value="Partially Paid" {{ request('status') === 'Partially Paid' ? 'selected' : '' }}>Dibayar Sebagian (Partially Paid)</option>
                    <option value="Paid" {{ request('status') === 'Paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                    <option value="Overdue" {{ request('status') === 'Overdue' ? 'selected' : '' }}>Jatuh Tempo (Overdue)</option>
                </select>
            </div>

            <div class="md:col-span-3 flex items-center gap-3">
                <button type="submit" class="flex-1 rounded-2xl bg-slate-800 px-4 py-3 text-sm font-bold text-white hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-500/20 transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                    Terapkan Filter
                </button>
                <a href="{{ route('invoices.index') }}" class="p-3 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20" title="Reset Pencarian">
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
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Invoice & Pelanggan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Tanggal</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Nilai Total</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Sisa Tagihan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Pembayaran</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Status Pesanan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($invoices as $invoice)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="font-extrabold font-mono text-teal-600 hover:text-teal-800 transition-colors text-base flex items-center gap-1.5">
                                        {{ $invoice->invoice_number }}
                                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-md bg-slate-200 flex items-center justify-center text-[9px] font-bold text-slate-600 uppercase border border-white">
                                            {{ substr($invoice->customer->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-slate-700">{{ $invoice->customer->name }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $invoice->invoice_date->format('d M Y') }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="font-extrabold text-slate-800">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                @if($invoice->remaining_amount <= 0)
                                    <span class="text-sm font-bold text-slate-400">-</span>
                                @else
                                    <span class="font-extrabold text-rose-600 bg-rose-50 px-3 py-1 rounded-lg border border-rose-100">
                                        Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($invoice->payment_status === 'Paid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        LUNAS
                                    </span>
                                @elseif($invoice->payment_status === 'Partially Paid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        SEBAGIAN
                                    </span>
                                @elseif($invoice->payment_status === 'Overdue')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        JATUH TEMPO
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        BELUM DIBAYAR
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($invoice->status === 'pending')
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                                @elseif($invoice->status === 'processing')
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700 border border-amber-200">Dalam Proses</span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">Selesai</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('invoices.show', $invoice) }}"
                                   class="inline-flex items-center justify-center gap-2 p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-teal-600 hover:bg-teal-50 hover:border-teal-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                   title="Lihat Detail Invoice">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span class="text-xs font-bold hidden sm:inline-block">Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Tidak Ada Data Invoice</p>
                                    <p class="mt-1 text-sm text-slate-500">Sesuaikan filter pencarian atau buat invoice baru.</p>
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
            {{ $invoices->links() }}
        </div>
    </div>
</x-app-layout>
