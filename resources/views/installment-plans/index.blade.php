<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent">Rencana Cicilan</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Pantau jadwal pembayaran termin, sisa tagihan, dan performa pembayaran cicilan pelanggan.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('installment-plans.export', request()->query()) }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-white px-5 py-3 font-bold text-slate-600 border border-slate-200 hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 transition-all duration-300 shadow-sm focus:outline-none focus:ring-4 focus:ring-sky-500/10">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export Excel
                </a>
            </div>
        </div>
    </div>

    @if ($overdue_count > 0)
        <div class="mb-6 rounded-2xl bg-rose-50 p-5 border border-rose-200 flex items-start gap-4 shadow-sm" x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms>
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-grow pt-1">
                <h3 class="text-sm font-bold text-rose-800">Perhatian: Cicilan Jatuh Tempo</h3>
                <p class="text-sm text-rose-700 mt-1 font-medium">Terdapat <strong>{{ $overdue_count }} cicilan yang telah melewati batas waktu</strong> dan memerlukan tindakan follow-up segera kepada pelanggan terkait.</p>
            </div>
            <button @click="show = false" class="p-1.5 rounded-full text-rose-500 hover:bg-rose-200 hover:text-rose-700 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="mb-6 bg-white/70 backdrop-blur-xl border border-white/60 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative z-20">
        <form method="GET" action="{{ route('installment-plans.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <div class="md:col-span-8">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="status">Filter Status Cicilan</label>
                <div class="relative">
                    <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm cursor-pointer appearance-none">
                        <option value="">-- Tampilkan Semua Status --</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Menunggu Pembayaran (Pending)</option>
                        <option value="Partially Paid" {{ request('status') === 'Partially Paid' ? 'selected' : '' }}>Dibayar Sebagian (Partially Paid)</option>
                        <option value="Paid" {{ request('status') === 'Paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="Overdue" {{ request('status') === 'Overdue' ? 'selected' : '' }}>Lewat Jatuh Tempo (Overdue)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="md:col-span-4 flex items-center gap-3">
                <button type="submit" class="flex-1 rounded-2xl bg-slate-800 px-4 py-3.5 text-sm font-bold text-white hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-500/20 transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                    Terapkan Filter
                </button>
                <a href="{{ route('installment-plans.index') }}" class="p-3.5 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20" title="Reset Filter">
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
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Referensi & Pelanggan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Jumlah Termin</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Total Tagihan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Sisa Cicilan</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Jatuh Tempo Berikutnya</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Status</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($invoices as $invoice)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="font-extrabold font-mono text-sky-600 hover:text-sky-800 transition-colors text-base flex items-center gap-1.5">
                                        {{ $invoice->invoice_number }}
                                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-md bg-slate-200 flex items-center justify-center text-[9px] font-bold text-slate-600 uppercase border border-white">
                                            {{ substr(optional($invoice->customer)->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-slate-700">{{ optional($invoice->customer)->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 font-extrabold border border-indigo-100 shadow-sm">
                                    {{ data_get($invoice, 'installment_summary.installment_count') ?? ($invoice->installment_count ?? $invoice->installments->count()) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="font-extrabold text-slate-800 font-mono">Rp {{ number_format((float) data_get($invoice, 'installment_summary.total_nominal', 0), 0, ',', '.') }}</span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                @php($remaining = (float) data_get($invoice, 'installment_summary.remaining_total', 0))
                                @if($remaining <= 0)
                                    <span class="text-sm font-bold text-slate-400">-</span>
                                @else
                                    <span class="font-extrabold text-rose-600 bg-rose-50 px-3 py-1 rounded-lg border border-rose-100 font-mono">
                                        Rp {{ number_format($remaining, 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @php($nextDue = data_get($invoice, 'installment_summary.next_due_date'))
                                @php($status = data_get($invoice, 'installment_summary.status'))
                                @if($nextDue)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 {{ now()->toDateString() > $nextDue->toDateString() && $status !== 'Paid' ? 'text-rose-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="{{ now()->toDateString() > $nextDue->toDateString() && $status !== 'Paid' ? 'text-rose-600 font-extrabold bg-rose-50 px-2 py-0.5 rounded border border-rose-100' : 'text-slate-600 font-medium' }}">
                                            {{ $nextDue->format('d M Y') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-bold">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @php($status = data_get($invoice, 'installment_summary.status'))
                                @if($status === 'Paid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wider shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Lunas
                                    </span>
                                @elseif($status === 'Partially Paid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 uppercase tracking-wider shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Sebagian
                                    </span>
                                @elseif($status === 'Overdue')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 uppercase tracking-wider shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Jatuh Tempo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase tracking-wider shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Dibayar
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                @php($firstInstallment = data_get($invoice, 'installment_summary.first_installment'))
                                @if($firstInstallment)
                                    <a href="{{ route('installment-plans.show', $firstInstallment) }}"
                                       class="inline-flex items-center justify-center gap-2 p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-sky-600 hover:bg-sky-50 hover:border-sky-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                                       title="Lihat Detail Cicilan">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        <span class="text-xs font-bold hidden sm:inline-block">Jadwal</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 font-bold">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Tidak Ada Data Cicilan</p>
                                    <p class="mt-1 text-sm text-slate-500">Belum ada invoice dengan metode pembayaran cicilan, atau sesuaikan filter pencarian.</p>
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
