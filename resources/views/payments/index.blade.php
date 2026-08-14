<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Riwayat Pembayaran</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Pantau semua transaksi pembayaran yang masuk dari pelanggan.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('payments.export', request()->query()) }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-white px-5 py-3 font-bold text-slate-600 border border-slate-200 hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-300 shadow-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </a>
                
                <a href="{{ route('payments.create') }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 px-5 py-3 font-bold text-white shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-emerald-500/40 focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                    <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Input Pembayaran</span>
                </a>
            </div>
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
        <form method="GET" action="{{ route('payments.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="q">Cari Ref. Invoice</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="Nomor Invoice..."
                        class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3 pl-12 pr-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm" />
                </div>
            </div>
            
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="method">Metode Transfer</label>
                <div class="relative">
                    <select id="method" name="method" class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm cursor-pointer appearance-none">
                        <option value="">-- Semua Metode --</option>
                        <option value="Cash" {{ request('method') === 'Cash' ? 'selected' : '' }}>Tunai / Cash</option>
                        <option value="Transfer Bank" {{ request('method') === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Giro" {{ request('method') === 'Giro' ? 'selected' : '' }}>Giro Bilyet</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="md:col-span-3 flex items-center gap-3">
                <button type="submit" class="flex-1 rounded-2xl bg-slate-800 px-4 py-3.5 text-sm font-bold text-white hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-500/20 transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                    Terapkan Filter
                </button>
                <a href="{{ route('payments.index') }}" class="p-3.5 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20" title="Reset Pencarian">
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
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Info Transaksi</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Ref. Invoice & Pelanggan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Metode</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Nominal Pembayaran</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($payments as $payment)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <!-- Info Transaksi -->
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0 shadow-sm mt-0.5">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="font-extrabold text-slate-800 text-base">{{ $payment->payment_number ?: 'TRX-' . substr($payment->id, 0, 8) }}</span>
                                        <div class="flex items-center gap-1.5 text-slate-500">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="text-xs font-bold">{{ $payment->payment_date->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Invoice & Pelanggan -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="font-extrabold font-mono text-emerald-600 hover:text-emerald-800 transition-colors text-sm flex items-center gap-1.5">
                                        {{ $payment->invoice->invoice_number }}
                                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-4 h-4 rounded bg-slate-200 flex items-center justify-center text-[8px] font-bold text-slate-600 uppercase border border-white">
                                            {{ substr(optional($payment->invoice->customer)->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-600">{{ optional($payment->invoice->customer)->name ?? 'Unknown Customer' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Metode -->
                            <td class="px-6 py-4">
                                @php
                                    $methodColors = [
                                        'Cash' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Transfer Bank' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Giro' => 'bg-purple-50 text-purple-700 border-purple-200'
                                    ];
                                    $mColor = $methodColors[$payment->payment_method] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold {{ $mColor }} border shadow-sm">
                                    @if($payment->payment_method === 'Cash')
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @elseif($payment->payment_method === 'Transfer Bank')
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @endif
                                    {{ $payment->payment_method }}
                                </span>
                            </td>

                            <!-- Nominal -->
                            <td class="px-6 py-4 text-right">
                                <span class="font-extrabold text-slate-800 text-base font-mono tracking-tight">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <div class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider">PIC: {{ optional($payment->creator)->name ?? 'System' }}</div>
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center justify-center gap-1.5 p-2 rounded-xl border border-sky-200 bg-sky-50 text-sky-600 hover:text-white hover:bg-sky-500 hover:border-sky-500 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20" title="Detail Transaksi">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span class="text-xs font-bold hidden sm:inline-block">Detail</span>
                                    </a>
                                    @if (auth()->user()->role?->slug === 'admin' || auth()->user()->role?->slug === 'super-admin')
                                        <form method="POST" action="{{ route('payments.destroy', $payment) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembayaran ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 p-2 rounded-xl border border-rose-200 bg-rose-50 text-rose-600 hover:text-white hover:bg-rose-500 hover:border-rose-500 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20" title="Hapus Transaksi">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span class="text-xs font-bold hidden sm:inline-block">Batal</span>
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
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Belum Ada Riwayat Pembayaran</p>
                                    <p class="mt-1 text-sm text-slate-500">Silakan input pembayaran pertama atau sesuaikan filter Anda.</p>
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
            {{ $payments->links() }}
        </div>
    </div>
</x-app-layout>
