<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Input Transaksi Pembayaran</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Catat dana masuk dan perbarui status pelunasan invoice pelanggan.</p>
            </div>
            
            <a href="{{ route('payments.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-emerald-50 hover:border-emerald-200 hover:shadow-emerald-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative mb-8">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400"></div>
            
            <div class="p-8 sm:p-10 border-b border-slate-100">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Pencarian Referensi Invoice
                </h3>

                <form method="GET" action="{{ route('payments.create') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3">
                        <div class="relative">
                            <select id="invoice_id" name="invoice_id" class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm appearance-none cursor-pointer">
                                <option value="" disabled {{ request('invoice_id') === null ? 'selected' : '' }}>-- Pilih invoice yang perlu dibayar --</option>
                                @foreach(($availableInvoices ?? collect()) as $availableInvoice)
                                    <option value="{{ $availableInvoice->id }}" @selected(optional($invoice)->id === $availableInvoice->id)>
                                        {{ $availableInvoice->invoice_number }} - {{ $availableInvoice->customer->name }} (Sisa: Rp {{ number_format($availableInvoice->remaining_amount, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-800 px-6 py-3.5 text-sm font-bold text-white hover:bg-slate-700 shadow-lg shadow-slate-500/20 hover:shadow-xl transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Muat Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($invoice)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column: Form -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
                        
                        <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="p-8 sm:p-10">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}" />

                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-8 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Formulir Transaksi Pembayaran
                            </h3>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Payment Date -->
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                                        <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" required
                                            class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm" />
                                        @error('payment_date')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                                    </div>

                                    <!-- Payment Method -->
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Metode Transfer <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <select name="payment_method" required
                                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm appearance-none cursor-pointer">
                                                <option value="" disabled {{ old('payment_method') === null ? 'selected' : '' }}>-- Pilih Metode --</option>
                                                <option value="Cash" {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>Tunai / Cash</option>
                                                <option value="Transfer Bank" {{ old('payment_method') === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                                <option value="Giro" {{ old('payment_method') === 'Giro' ? 'selected' : '' }}>Giro Bilyet</option>
                                                <option value="Lainnya" {{ old('payment_method') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                            </div>
                                        </div>
                                        @error('payment_method')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Nominal Dana Masuk <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-extrabold text-sm">Rp</div>
                                        <input type="number" name="amount" step="1" min="0" value="{{ old('amount', $invoice->remaining_amount) }}" required
                                            class="w-full rounded-2xl border border-slate-200/70 bg-emerald-50/30 py-4 pl-12 pr-4 text-emerald-800 font-extrabold text-lg focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm" />
                                    </div>
                                    <div class="mt-2 flex items-center justify-between">
                                        <p class="text-xs font-medium text-slate-500">Isikan jumlah pasti dana yang ditransfer oleh pelanggan.</p>
                                        <p class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">Maksimal: Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</p>
                                    </div>
                                    @error('amount')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                                </div>

                                <!-- Installment Number -->
                                @if(optional($invoice)->is_installment)
                                    <div class="p-5 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                                        <label class="block text-sm font-bold text-indigo-900 mb-2">Alokasi Termin Cicilan <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <select name="installment_number" required
                                                class="w-full rounded-xl border border-indigo-200 bg-white py-3 pl-4 pr-10 text-indigo-800 font-bold focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 transition-all shadow-sm appearance-none cursor-pointer">
                                                <option value="" disabled {{ old('installment_number') === null ? 'selected' : '' }}>-- Tentukan Cicilan Ke-Berapa --</option>
                                                @foreach(($availableInstallments ?? collect()) as $installment)
                                                    <option value="{{ $installment->installment_number }}" {{ old('installment_number') == $installment->installment_number ? 'selected' : '' }}>
                                                        Cicilan #{{ $installment->installment_number }} | Tempo: {{ $installment->due_date->format('d/m/Y') }} | Sisa: Rp {{ number_format($installment->remaining_amount, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-xs text-indigo-500 font-medium flex items-start gap-1">
                                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Pilih urutan termin yang akan dilunasi oleh pelanggan.
                                        </p>
                                        @error('installment_number')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                                    </div>
                                @else
                                    <input type="hidden" name="installment_number" value="" />
                                @endif

                                <!-- Notes -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Keterangan / Referensi Bank</label>
                                    <textarea name="notes" rows="2" placeholder="Catat nomor referensi transfer bank, kode giro, dll..."
                                        class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm resize-none">{{ old('notes') }}</textarea>
                                    @error('notes')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                                </div>

                                <!-- Payment Proof -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Pembayaran (Opsional)</label>
                                    <input type="file" name="payment_proof" accept="image/png,image/jpeg,image/webp,application/pdf"
                                        class="w-full rounded-2xl border border-slate-200/70 bg-white py-3 px-4 text-sm text-slate-700 font-medium file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all shadow-sm cursor-pointer" />
                                    <p class="mt-2 text-xs font-medium text-slate-500">Format: JPG, PNG, WEBP, atau PDF. Maksimal 2MB.</p>
                                    @error('payment_proof')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="mt-10 pt-6 flex flex-col-reverse sm:flex-row items-center justify-end gap-4 border-t border-slate-100/80">
                                <a href="{{ route('invoices.show', $invoice) }}" class="w-full sm:w-auto px-6 py-3.5 text-center rounded-2xl text-sm font-bold text-slate-500 bg-slate-50/50 hover:bg-slate-100 hover:text-slate-800 transition-all focus:outline-none focus:ring-4 focus:ring-slate-100">
                                    Batal
                                </a>
                                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 shadow-lg shadow-emerald-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/40 focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                                    Simpan Transaksi Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Context Information -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    <!-- Invoice Summary Card -->
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] shadow-xl p-8 text-white relative overflow-hidden">
                        <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-gradient-to-br from-teal-500/30 to-emerald-500/30 blur-3xl pointer-events-none"></div>
                        
                        <div class="relative z-10">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2 border-b border-slate-700/80 pb-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Ringkasan Tagihan
                            </h3>

                            <div class="space-y-5">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Referensi</p>
                                    <p class="text-base font-extrabold text-teal-400 font-mono">{{ $invoice->invoice_number }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Debitur / Pelanggan</p>
                                    <p class="text-base font-bold text-white">{{ $invoice->customer->name }}</p>
                                </div>

                                <div class="h-px w-full bg-slate-700/80 my-2"></div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-300">Total Tagihan</span>
                                    <span class="text-sm font-bold text-slate-100 font-mono">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-300">Dana Terbayar</span>
                                    <span class="text-sm font-bold text-emerald-400 font-mono">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex items-center justify-between mt-2 pt-4 border-t border-slate-700/80">
                                    <span class="text-base font-bold text-rose-400">Sisa Hutang</span>
                                    <span class="text-xl font-extrabold text-white font-mono tracking-tight">Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History Preview Card -->
                    <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Log Pembayaran Terdahulu
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            @if($invoice->payments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($invoice->payments->take(3) as $payment)
                                        <div class="flex items-start gap-3 pb-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
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
                                <div class="mt-2 text-center">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 uppercase tracking-wider transition-colors">Lihat Semua Riwayat</a>
                                </div>
                            @else
                                <div class="py-6 flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-100 mb-3 shadow-inner">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-xs">Belum ada pembayaran sama sekali</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State / Instruction -->
            <div class="max-w-3xl mx-auto">
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-12 text-center overflow-hidden relative">
                    <div class="absolute -bottom-24 -right-24 h-64 w-64 rounded-full bg-gradient-to-br from-emerald-100/50 to-teal-100/50 blur-3xl pointer-events-none"></div>
                    <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-gradient-to-br from-blue-100/50 to-cyan-100/50 blur-3xl pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[2rem] flex items-center justify-center mb-6 shadow-inner border border-emerald-100/50">
                            <svg class="w-12 h-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-800 mb-3">Pilih Dokumen Invoice Terlebih Dahulu</h2>
                        <p class="text-slate-500 font-medium max-w-md mx-auto mb-8 leading-relaxed">
                            Gunakan kotak pencarian di atas untuk memilih invoice pelanggan yang akan dilunasi. Sistem hanya akan menampilkan invoice dengan status belum lunas atau cicilan yang berjalan.
                        </p>
                        
                        <div class="flex items-center gap-4">
                            <a href="{{ route('invoices.index', ['status' => 'Unpaid']) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white border border-slate-200 px-6 py-3.5 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm focus:outline-none focus:ring-4 focus:ring-slate-500/10">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                Lihat Daftar Tagihan Berjalan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
