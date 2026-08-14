<x-app-layout>
    <div class="max-w-6xl mx-auto">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent">Profil Pelanggan</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Detail informasi kontak dan rekam jejak aktivitas klien.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('customers.index') }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-slate-50 hover:border-slate-300 transition-all duration-300 text-sm font-bold text-slate-600">
                    <svg class="w-5 h-5 text-slate-400 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Direktori
                </a>
                
                <a href="{{ route('customers.edit', $customer) }}" class="group flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 shadow-[0_4px_20px_rgba(245,158,11,0.3)] border border-transparent hover:shadow-amber-500/40 hover:scale-105 transition-all duration-300 text-sm font-bold text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Profil
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            
            <!-- Left Column: Customer Info -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Main Profile Card -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
                    <div class="h-24 bg-gradient-to-r from-sky-400 to-indigo-500 w-full relative">
                        <div class="absolute -bottom-10 inset-x-0 flex justify-center">
                            <div class="w-24 h-24 rounded-[1.5rem] bg-white p-1.5 shadow-lg border-2 border-white/50 backdrop-blur-md">
                                <div class="w-full h-full rounded-2xl bg-gradient-to-br from-sky-50 to-indigo-50 border border-sky-100 flex items-center justify-center text-4xl font-extrabold text-sky-600 shadow-inner">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-14 pb-8 px-6 flex flex-col items-center text-center">
                        <h2 class="text-2xl font-extrabold text-slate-800 mb-1">{{ $customer->name }}</h2>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-sky-50 text-sky-600 text-xs font-bold border border-sky-100 mb-6">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Klien Terdaftar
                        </span>
                        
                        <div class="w-full grid grid-cols-2 gap-4 border-t border-slate-100 pt-6 mt-2">
                            <div class="flex flex-col items-center p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Invoice</span>
                                <span class="text-xl font-extrabold text-indigo-600">{{ $customer->invoices->count() }}</span>
                            </div>
                            <div class="flex flex-col items-center p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Visit Sales</span>
                                <span class="text-xl font-extrabold text-sky-600">{{ $customer->visits->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Address Card -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        Informasi Detail
                    </h3>
                    
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Telepon / Kontak</span>
                                <span class="font-bold font-mono text-slate-700 tracking-wide mt-0.5">{{ $customer->contact }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Email</span>
                                <span class="font-bold text-slate-700 mt-0.5 break-all">{{ $customer->email ?: 'Belum ditambahkan' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Penanggung Jawab (PIC)</span>
                                <span class="font-bold text-slate-700 mt-0.5">{{ $customer->pic ?: 'Belum ditentukan' }}</span>
                            </div>
                        </div>

                        <div class="h-px w-full bg-slate-100 my-2"></div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0 mt-1">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi & Alamat</span>
                                <span class="font-bold text-slate-800 text-sm leading-relaxed mb-1">{{ $customer->city }}, {{ $customer->province }}</span>
                                <span class="text-sm font-medium text-slate-600 leading-relaxed">{{ $customer->address }}</span>
                            </div>
                        </div>

                        @if($customer->note)
                            <div class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-100/80 shadow-inner">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block">Catatan Khusus</span>
                                <p class="text-sm font-medium text-slate-700 leading-relaxed">{{ $customer->note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Transactions & History -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                
                <!-- Invoices Section -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex-grow flex flex-col relative">
                    <!-- Decorative Graphic -->
                    <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-gradient-to-br from-indigo-100/50 to-purple-100/50 blur-3xl mix-blend-multiply pointer-events-none"></div>

                    <div class="p-8 border-b border-slate-100 flex items-center justify-between relative z-10">
                        <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Invoice Terbaru
                        </h2>
                        <a href="{{ route('invoices.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 text-sm font-bold transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Buat Invoice
                        </a>
                    </div>
                    
                    <div class="flex-grow p-0 relative z-10">
                        @if($customer->invoices->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead>
                                        <tr class="bg-slate-50/50">
                                            <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">No. Invoice</th>
                                            <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Tanggal</th>
                                            <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Total</th>
                                            <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100/80">
                                        @foreach($customer->invoices->take(5) as $invoice)
                                            <tr class="transition-colors hover:bg-slate-50/60 group">
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('invoices.show', $invoice) }}" class="font-bold font-mono tracking-tight text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1">
                                                        {{ $invoice->invoice_number }}
                                                        <svg class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 font-medium text-slate-600">{{ $invoice->invoice_date->format('d M Y') }}</td>
                                                <td class="px-6 py-4 text-right font-extrabold text-slate-800">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($invoice->payment_status === 'Paid')
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            {{ statusLabel($invoice->payment_status) }}
                                                        </span>
                                                    @elseif($invoice->payment_status === 'Partially Paid')
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                            {{ statusLabel($invoice->payment_status) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                            {{ statusLabel($invoice->payment_status) }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center p-12 text-slate-400">
                                <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="font-bold text-slate-600">Belum Ada Invoice</p>
                                <p class="text-sm mt-1 text-slate-500 text-center max-w-xs">Pelanggan ini belum memiliki riwayat transaksi / invoice.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sales Visits Section -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex-grow flex flex-col">
                    <div class="p-8 border-b border-slate-100">
                        <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Riwayat Kunjungan Sales
                        </h2>
                    </div>

                    <div class="flex-grow p-0">
                        @if($customer->visits->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead>
                                        <tr class="bg-slate-50/50">
                                            <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Tanggal</th>
                                            <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Sales Person</th>
                                            <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Hasil Kunjungan</th>
                                            <th class="px-6 py-4 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-center">Status F/U</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100/80">
                                        @foreach($customer->visits->take(10) as $visit)
                                            <tr class="transition-colors hover:bg-slate-50/60">
                                                <td class="px-6 py-4">
                                                    <span class="font-bold text-slate-700">{{ $visit->visit_date->format('d M Y') }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">
                                                            {{ substr($visit->sales->name, 0, 1) }}
                                                        </div>
                                                        <span class="font-medium text-slate-600">{{ $visit->sales->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <p class="text-sm font-medium text-slate-700 truncate max-w-[200px]" title="{{ $visit->visit_result }}">
                                                        {{ $visit->visit_result }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($visit->follow_up_status === 'Completed')
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            {{ statusLabel($visit->follow_up_status) }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                            {{ statusLabel($visit->follow_up_status) }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center p-12 text-slate-400">
                                <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="font-bold text-slate-600">Belum Ada Kunjungan</p>
                                <p class="text-sm mt-1 text-slate-500 text-center max-w-xs">Tidak ada riwayat kunjungan sales yang tercatat untuk pelanggan ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
