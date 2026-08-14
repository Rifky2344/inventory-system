<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-teal-600 to-indigo-600 bg-clip-text text-transparent flex items-center gap-3">
                    {{ $invoice->invoice_number }}
                    @if($invoice->payment_status === 'Paid')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">LUNAS</span>
                    @elseif($invoice->payment_status === 'Partially Paid')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">SEBAGIAN</span>
                    @elseif($invoice->payment_status === 'Overdue')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">JATUH TEMPO</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">BELUM DIBAYAR</span>
                    @endif
                </h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Detail informasi invoice, rincian barang, dan riwayat pembayaran.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('invoices.index') }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 text-sm font-bold text-slate-600">
                    <svg class="w-5 h-5 text-slate-400 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
                @if ($invoice->payment_status === 'Unpaid' || $invoice->payment_status === 'Partially Paid')
                    <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 text-white shadow-lg shadow-teal-500/25 hover:shadow-teal-500/40 hover:scale-[1.02] transition-all duration-300 text-sm font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Input Pembayaran
                    </a>
                @endif
            </div>
        </div>

        <!-- Quick Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center gap-4 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-teal-50 rounded-full blur-2xl"></div>
                <div class="w-14 h-14 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 border border-teal-100 shrink-0 relative z-10">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Pembayaran</p>
                    <p class="text-xl font-extrabold text-slate-800">{{ statusLabel($invoice->payment_status) }}</p>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center gap-4 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 rounded-full blur-2xl"></div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100 shrink-0 relative z-10">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Terbayar</p>
                    <p class="text-xl font-extrabold text-emerald-600 font-mono tracking-tight">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center gap-4 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-50 rounded-full blur-2xl"></div>
                <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100 shrink-0 relative z-10">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sisa Piutang</p>
                    <p class="text-xl font-extrabold text-rose-600 font-mono tracking-tight">Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Main Content -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <!-- Customer & Invoice Metadata -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
                    <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-teal-400 via-sky-400 to-indigo-400"></div>
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-100 to-sky-100 flex items-center justify-center border-2 border-white shadow-sm shrink-0">
                                <svg class="w-7 h-7 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tagihan Kepada</h2>
                                <a href="{{ route('customers.show', $invoice->customer) }}" class="text-2xl font-extrabold text-slate-800 hover:text-teal-600 transition-colors">
                                    {{ $invoice->customer->name }}
                                </a>
                                <p class="text-sm font-medium text-slate-500 mt-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> {{ $invoice->customer->contact }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Terbit</p>
                                <p class="text-sm font-bold text-slate-700">{{ $invoice->invoice_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jatuh Tempo</p>
                                <p class="text-sm font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-lg inline-block border border-rose-100">{{ $invoice->due_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Transaksi</p>
                                @if($invoice->type === 'out')
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">Barang Keluar</span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-50 text-teal-600 border border-teal-100">Barang Masuk</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Gudang</p>
                                @if($invoice->status === 'completed')
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1 w-max"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Selesai Diproses</span>
                                @elseif($invoice->status === 'processing')
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100 flex items-center gap-1 w-max"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Dalam Proses Gudang</span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200 flex items-center gap-1 w-max"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Menunggu Antrean</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sales Person</p>
                                <p class="text-sm font-bold text-slate-700 flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[10px]">{{ substr($invoice->sales->name, 0, 1) }}</span>
                                    {{ $invoice->sales->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Dibuat Oleh</p>
                                <p class="text-sm font-bold text-slate-700">{{ $invoice->creator->name }}</p>
                            </div>
                        </div>

                        @if($invoice->note)
                            <div class="mt-6 p-4 rounded-xl bg-amber-50 border border-amber-100">
                                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Catatan Khusus</p>
                                <p class="text-sm font-medium text-amber-800">{{ $invoice->note }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Line Items -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    <div class="p-8 pb-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Rincian Pembelian
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50/50 border-y border-slate-100">
                                <tr>
                                    <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Nama Barang</th>
                                    <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Kuantitas</th>
                                    <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Harga Satuan</th>
                                    <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Subtotal Line</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($invoice->items as $item)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-8 py-4">
                                            <div class="font-bold text-slate-700">{{ $item->item->name }}</div>
                                            @if($item->is_processed)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full mt-1 border border-emerald-100">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    SUDAH DIPROSES
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-4 text-right font-medium text-slate-600">{{ $item->qty }}</td>
                                        <td class="px-8 py-4 text-right font-mono text-slate-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="px-8 py-4 text-right font-bold font-mono text-slate-800 tracking-tight">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-8 bg-slate-50/50 border-t border-slate-100">
                        <div class="flex flex-col gap-3 max-w-sm ml-auto">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-bold text-slate-500">Subtotal Barang</span>
                                <span class="font-bold font-mono text-slate-700">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-px w-full bg-slate-200"></div>
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-teal-600 text-lg">Grand Total</span>
                                <span class="text-2xl font-extrabold font-mono text-teal-700 tracking-tight">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pembayaran -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    <div class="p-8 pb-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Log Riwayat Pembayaran
                        </h3>
                    </div>

                    @if($invoice->payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50/50 border-y border-slate-100">
                                    <tr>
                                        <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Tanggal</th>
                                        <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Nomor Transaksi</th>
                                        <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Metode / Ref</th>
                                        <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Nominal Masuk</th>
                                        <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">PIC Kasir</th>
                                        <th class="px-8 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($invoice->payments as $payment)
                                        <tr class="hover:bg-slate-50/60 transition-colors">
                                            <td class="px-8 py-4 font-medium text-slate-700 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $payment->payment_date->format('d M Y') }}
                                            </td>
                                            <td class="px-8 py-4 font-bold text-slate-600 font-mono">{{ $payment->payment_number ?: '-' }}</td>
                                            <td class="px-8 py-4 font-bold text-slate-600">
                                                {{ $payment->payment_method }}
                                                @if($payment->notes)
                                                    <span class="block text-xs font-normal text-slate-400 font-mono mt-1">{{ $payment->notes }}</span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-4 text-right font-extrabold font-mono text-emerald-600 tracking-tight bg-emerald-50/50">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            <td class="px-8 py-4 font-medium text-slate-600">{{ $payment->creator->name }}</td>
                                            <td class="px-8 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    @if($payment->payment_proof_path)
                                                        <a href="{{ Storage::url($payment->payment_proof_path) }}" target="_blank" class="p-1.5 rounded-lg text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 transition-colors focus:outline-none" title="Lihat Bukti Pembayaran">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                        </a>
                                                    @endif
                                                    <button type="button" onclick="alert('Detail Pembayaran:\nNomor: {{ $payment->payment_number ?: '-' }}\nTanggal: {{ $payment->payment_date->format('d M Y') }}\nMetode: {{ $payment->payment_method }}\nNominal: Rp {{ number_format($payment->amount, 0, ',', '.') }}\nKasir: {{ $payment->creator->name }}')" class="p-1.5 rounded-lg text-sky-500 hover:text-sky-700 hover:bg-sky-50 transition-colors focus:outline-none" title="Detail Pembayaran">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    </button>
                                                    @if (auth()->user()->role?->slug === 'admin' || auth()->user()->role?->slug === 'super-admin')
                                                        <form method="POST" action="{{ route('payments.destroy', $payment) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus pembayaran ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1.5 rounded-lg text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition-colors focus:outline-none" title="Batalkan Pembayaran">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-8 py-10 flex flex-col items-center justify-center text-slate-400 border-t border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-100 mb-3 shadow-inner">
                                <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <p class="font-bold text-slate-600 text-sm">Belum ada transaksi pembayaran</p>
                        </div>
                    @endif
                </div>

                <!-- Installments (if any) -->
                @if($invoice->installments->count() > 0)
                    <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                        <div class="p-8 pb-4 flex items-center justify-between">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Tabel Rencana Cicilan (Termin)
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50/50 border-y border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center w-16">Termin</th>
                                        <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Target Nominal</th>
                                        <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Sisa Tagihan</th>
                                        <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Jatuh Tempo</th>
                                        <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($invoice->installments as $installment)
                                        <tr class="hover:bg-slate-50/60 transition-colors">
                                            <td class="px-6 py-4 text-center">
                                                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-extrabold flex items-center justify-center mx-auto border border-indigo-100">{{ $installment->installment_number }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-extrabold font-mono text-slate-700 tracking-tight">Rp {{ number_format($installment->nominal, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-right font-bold font-mono text-rose-500">
                                                @if($installment->remaining_amount <= 0)
                                                    <span class="text-slate-400">-</span>
                                                @else
                                                    Rp {{ number_format($installment->remaining_amount, 0, ',', '.') }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 font-medium text-slate-600">{{ $installment->due_date->format('d M Y') }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @if($installment->status === 'Paid')
                                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wider">Lunas</span>
                                                @elseif($installment->status === 'Partially Paid')
                                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 uppercase tracking-wider">Sebagian</span>
                                                @elseif($installment->status === 'Overdue')
                                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 uppercase tracking-wider">Jatuh Tempo</span>
                                                @else
                                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase tracking-wider">Belum Dibayar</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Sidebar Metadata -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Admin Actions -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Tindakan Administratif
                    </h3>

                    <div class="flex flex-col gap-3">
                        @if ($invoice->payment_status === 'Unpaid')
                            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini secara permanen? Seluruh data terkait akan hilang.')" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-rose-50 text-rose-600 font-bold hover:bg-rose-100 hover:text-rose-700 transition-colors border border-rose-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus Invoice
                                </button>
                            </form>
                        @else
                            <div class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                <p class="text-xs font-medium text-slate-500 leading-relaxed">Invoice yang sudah memiliki riwayat pembayaran tidak dapat dihapus untuk menjaga integritas data keuangan.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <div class="flex flex-col gap-2 text-xs font-medium text-slate-400">
                            <div class="flex justify-between items-center">
                                <span>Record ID</span>
                                <span class="font-mono text-slate-500">{{ $invoice->id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Terdata pada</span>
                                <span class="text-slate-500">{{ $invoice->created_at->format('d M Y H:i') }}</span>
                            </div>
                            @if($invoice->updated_at !== $invoice->created_at)
                                <div class="flex justify-between items-center">
                                    <span>Pembaruan Terakhir</span>
                                    <span class="text-slate-500">{{ $invoice->updated_at->format('d M Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
