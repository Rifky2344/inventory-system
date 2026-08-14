<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('payments.index') }}" class="p-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-700 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Detail Pembayaran</h1>
                </div>
                <p class="mt-2 text-sm text-slate-500 font-medium">Informasi lengkap rincian transaksi pembayaran dan bukti transfer.</p>
            </div>

            @if (auth()->user()->role?->slug === 'admin' || auth()->user()->role?->slug === 'super-admin')
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus pembayaran ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-white border border-rose-200 px-5 py-3 font-bold text-rose-600 shadow-sm transition-all duration-300 hover:bg-rose-50 hover:border-rose-300 focus:outline-none focus:ring-4 focus:ring-rose-500/10">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus Transaksi</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-800">{{ $payment->payment_number ?: 'TRX-' . substr($payment->id, 0, 8) }}</h2>
                            <p class="text-sm font-medium text-slate-500 mt-1">Tanggal Bayar: {{ $payment->payment_date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ref. Invoice</p>
                        <a href="{{ route('invoices.show', $payment->invoice) }}" class="inline-flex items-center gap-2 text-base font-extrabold font-mono text-emerald-600 hover:text-emerald-700 transition-colors bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                            {{ $payment->invoice->invoice_number }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pelanggan</p>
                        <p class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            {{ optional($payment->invoice->customer)->name ?? 'Unknown' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Metode Pembayaran</p>
                        @php
                            $methodColors = [
                                'Cash' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Transfer Bank' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Giro' => 'bg-purple-50 text-purple-700 border-purple-200'
                            ];
                            $mColor = $methodColors[$payment->payment_method] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold {{ $mColor }} border shadow-sm">
                            {{ $payment->payment_method }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nominal</p>
                        <p class="text-2xl font-extrabold text-slate-800 font-mono tracking-tight">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Catatan</p>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 text-sm font-medium">
                            {{ $payment->notes ?: 'Tidak ada catatan' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Foto Bukti -->
        <div class="space-y-6">
            <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-800">Bukti Transaksi</h3>
                </div>
                <div class="p-6 text-center">
                    @if($payment->payment_proof_path)
                        <a href="{{ Storage::url($payment->payment_proof_path) }}" target="_blank" class="block group relative rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm transition-all hover:border-emerald-300 hover:shadow-lg">
                            <img src="{{ Storage::url($payment->payment_proof_path) }}" alt="Bukti Pembayaran" class="w-full h-auto object-cover max-h-64 transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                <span class="bg-white text-slate-800 text-sm font-bold px-4 py-2 rounded-xl shadow-lg flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                    Perbesar Gambar
                                </span>
                            </div>
                        </a>
                    @else
                        <div class="py-12 px-6 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="font-bold text-slate-500 text-sm">Tidak ada bukti yang dilampirkan</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-extrabold text-slate-800">Sistem</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kasir / Penerima</p>
                        <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ optional($payment->creator)->name ?? 'System' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Waktu Input</p>
                        <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $payment->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
