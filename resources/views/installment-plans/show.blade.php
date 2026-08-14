<x-app-layout>
    @if(!$invoice)
        <div class="max-w-2xl mx-auto mt-10">
            <div class="rounded-3xl border border-amber-200 bg-amber-50/80 backdrop-blur-xl p-10 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-amber-200">
                    <svg class="w-10 h-10 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-amber-900 mb-3">Data Tidak Ditemukan</h2>
                <p class="text-amber-700 font-medium mb-8">Rencana cicilan atau invoice yang Anda cari tidak tersedia, mungkin telah dihapus atau ID tidak valid.</p>
                <a href="{{ route('installment-plans.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-3.5 text-sm font-bold text-white hover:from-amber-600 hover:to-amber-700 transition-all shadow-lg shadow-amber-500/30 hover:shadow-xl hover:scale-105">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Cicilan
                </a>
            </div>
        </div>
    @else
    
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent flex items-center gap-3">
                    Jadwal Termin {{ $invoice->invoice_number }}
                </h1>
                <p class="mt-2 text-sm text-slate-500 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Pelanggan: <span class="font-bold text-slate-700">{{ optional($invoice->customer)->name ?? 'Data Pelanggan Terhapus' }}</span>
                </p>
            </div>
            
            <a href="{{ route('installment-plans.index') }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 text-sm font-bold text-slate-600">
                <svg class="w-5 h-5 text-slate-400 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-slate-100 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex flex-col gap-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Tagihan</p>
                    <p class="text-xl font-extrabold text-slate-800 font-mono tracking-tight">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-indigo-50 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex flex-col gap-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Termin</p>
                    <p class="text-xl font-extrabold text-indigo-600 flex items-baseline gap-1">
                        {{ $invoice->installments->count() }} <span class="text-xs font-bold text-indigo-400">Kali</span>
                    </p>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-50 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex flex-col gap-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Dana Masuk (Terbayar)</p>
                    <p class="text-xl font-extrabold text-emerald-600 font-mono tracking-tight">Rp {{ number_format($invoice->installments->sum('paid_amount'), 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-rose-50 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex flex-col gap-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sisa Hutang</p>
                    <p class="text-xl font-extrabold text-rose-600 font-mono tracking-tight">Rp {{ number_format($invoice->installments->sum('remaining_amount'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Installment Details -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
                    <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-sky-400 via-indigo-400 to-purple-400"></div>
                    
                    <div class="p-8 pb-6 flex items-center justify-between border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Rincian Jadwal Termin
                        </h3>
                    </div>

                    <div class="p-8 space-y-6 bg-slate-50/30">
                        @if($invoice->installments->count() > 0)
                            @foreach($invoice->installments as $installment)
                                @php
                                    $isOverdue = now()->toDateString() > $installment->due_date->toDateString() && $installment->status !== 'Paid';
                                    $statusStyle = '';
                                    if ($installment->status === 'Paid') $statusStyle = 'border-emerald-200 bg-emerald-50/50 hover:border-emerald-300';
                                    elseif ($installment->status === 'Partially Paid') $statusStyle = 'border-indigo-200 bg-indigo-50/50 hover:border-indigo-300';
                                    elseif ($isOverdue) $statusStyle = 'border-rose-300 bg-rose-50 hover:border-rose-400 shadow-[0_0_15px_rgba(244,63,94,0.1)]';
                                    else $statusStyle = 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-md';
                                @endphp
                                
                                <div class="rounded-2xl border {{ $statusStyle }} transition-all duration-300 overflow-hidden group">
                                    <div class="p-5 sm:p-6 grid grid-cols-2 sm:grid-cols-6 gap-4 sm:gap-6 items-center">
                                        <!-- Termin # -->
                                        <div class="col-span-2 sm:col-span-1 flex flex-col items-center justify-center py-2 sm:border-r border-slate-200/50">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Termin</span>
                                            <span class="text-3xl font-extrabold text-slate-700 group-hover:scale-110 transition-transform">#{{ $installment->installment_number }}</span>
                                        </div>

                                        <div class="col-span-2 sm:col-span-5 grid grid-cols-2 sm:grid-cols-5 gap-4">
                                            <!-- Due Date -->
                                            <div class="col-span-1 sm:col-span-1">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jatuh Tempo</p>
                                                <p class="text-sm font-bold {{ $isOverdue ? 'text-rose-600 bg-rose-100/50 px-2 py-0.5 rounded inline-block border border-rose-200' : 'text-slate-700' }}">
                                                    {{ $installment->due_date->format('d/m/Y') }}
                                                </p>
                                            </div>

                                            <!-- Nominal -->
                                            <div class="col-span-1 sm:col-span-1">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nominal</p>
                                                <p class="text-sm font-extrabold text-slate-800 font-mono">Rp {{ number_format($installment->nominal, 0, ',', '.') }}</p>
                                            </div>

                                            <!-- Paid -->
                                            <div class="col-span-1 sm:col-span-1">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Telah Dibayar</p>
                                                <p class="text-sm font-extrabold text-emerald-600 font-mono">Rp {{ number_format($installment->paid_amount, 0, ',', '.') }}</p>
                                            </div>

                                            <!-- Remaining -->
                                            <div class="col-span-1 sm:col-span-1">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sisa</p>
                                                <p class="text-sm font-extrabold font-mono {{ $installment->remaining_amount > 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                                                    Rp {{ number_format($installment->remaining_amount, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            <!-- Status -->
                                            <div class="col-span-2 sm:col-span-1 flex items-center sm:justify-end">
                                                @if($installment->status === 'Paid')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wider shadow-sm">Lunas</span>
                                                @elseif($installment->status === 'Partially Paid')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 uppercase tracking-wider shadow-sm">Sebagian</span>
                                                @elseif($isOverdue)
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 uppercase tracking-wider shadow-sm animate-pulse">Jatuh Tempo</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase tracking-wider shadow-sm">Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    @if($installment->nominal > 0)
                                        @php $percent = min(100, round(($installment->paid_amount / $installment->nominal) * 100)); @endphp
                                        <div class="h-2 w-full bg-slate-200/50 relative overflow-hidden">
                                            <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-emerald-400 to-emerald-500 transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="py-10 flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center border border-slate-200 mb-4 shadow-inner">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </div>
                                <p class="font-bold text-slate-600 text-base">Tidak Ada Rencana Cicilan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Action CTA -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] shadow-xl p-8 text-white relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-gradient-to-br from-emerald-500/30 to-teal-500/30 blur-3xl pointer-events-none"></div>
                    
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Tindakan Kasir
                    </h3>

                    @if($invoice->installments->where('status', '!=', 'Paid')->count() > 0)
                        <p class="text-sm text-slate-300 font-medium mb-6 leading-relaxed">Catat penerimaan pembayaran baru untuk tagihan ini guna memperbarui status termin cicilan.</p>
                        <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl text-sm font-bold text-slate-900 bg-gradient-to-r from-emerald-400 to-emerald-300 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/40 hover:scale-[1.02] transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Input Pembayaran Cicilan
                        </a>
                    @else
                        <div class="p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-center">
                            <svg class="w-8 h-8 text-emerald-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-bold text-emerald-300">Seluruh termin telah lunas.</p>
                        </div>
                    @endif
                </div>

                <!-- Payment History Sidebar -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Log Pembayaran Terakhir
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        @if($invoice->payments->count() > 0)
                            <div class="space-y-4">
                                @foreach($invoice->payments->take(5) as $payment)
                                    <div class="flex items-start gap-3 pb-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-sm font-extrabold text-slate-800 font-mono">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                <p class="text-[10px] font-bold text-slate-400">{{ $payment->payment_date->format('d M') }}</p>
                                            </div>
                                            <p class="text-[11px] font-medium text-slate-500 line-clamp-1">Via: {{ $payment->payment_method }} 
                                                @if($payment->installment_number) | Termin: #{{ $payment->installment_number }} @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 text-center py-4 font-medium">Belum ada transaksi pembayaran masuk.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
