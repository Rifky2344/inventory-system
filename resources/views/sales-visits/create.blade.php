<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-violet-600 to-fuchsia-600 bg-clip-text text-transparent">Tambah Kunjungan Sales</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Catat hasil pertemuan dengan pelanggan untuk pemantauan performa.</p>
            </div>
            <a href="{{ route('sales-visits.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-violet-50 hover:border-violet-200 hover:shadow-violet-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-violet-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] overflow-hidden relative">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-violet-400 via-fuchsia-400 to-pink-400"></div>
            
            <form method="POST" action="{{ route('sales-visits.store') }}" class="p-8 sm:p-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Kolom Kiri -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Informasi Target
                        </h3>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pelanggan yang Dikunjungi <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="customer_id" required
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all shadow-sm appearance-none cursor-pointer">
                                    <option value="" disabled {{ old('customer_id') === null ? 'selected' : '' }}>-- Pilih Pelanggan --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->city }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                </div>
                            </div>
                            @error('customer_id')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                            <input type="date" name="visit_date" value="{{ old('visit_date', now()->toDateString()) }}" required
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all shadow-sm" />
                            @error('visit_date')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Status Follow Up <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="follow_up_status" required
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all shadow-sm appearance-none cursor-pointer">
                                    <option value="" disabled {{ old('follow_up_status') === null ? 'selected' : '' }}>-- Tentukan Status --</option>
                                    <option value="Pending" {{ old('follow_up_status') === 'Pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                                    <option value="Completed" {{ old('follow_up_status') === 'Completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('follow_up_status')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Laporan & Catatan
                        </h3>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Hasil Kunjungan <span class="text-rose-500">*</span></label>
                            <textarea name="visit_result" rows="4" required placeholder="Jelaskan progres negosiasi, keluhan, atau permintaan pelanggan..."
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-4 px-4 text-slate-700 font-medium focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all shadow-sm resize-none">{{ old('visit_result') }}</textarea>
                            @error('visit_result')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Internal</label>
                            <textarea name="note" rows="3" placeholder="Informasi tambahan untuk tim internal (opsional)..."
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-4 px-4 text-slate-700 font-medium focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all shadow-sm resize-none">{{ old('note') }}</textarea>
                            @error('note')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 flex items-center justify-end gap-4 border-t border-slate-100/80">
                    <a href="{{ route('sales-visits.index') }}" class="px-6 py-3.5 rounded-2xl text-sm font-bold text-slate-500 bg-slate-50/50 hover:bg-slate-100 hover:text-slate-800 transition-all focus:outline-none focus:ring-4 focus:ring-slate-100">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-violet-500 to-fuchsia-500 hover:from-violet-600 hover:to-fuchsia-600 shadow-lg shadow-violet-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-violet-500/40 focus:outline-none focus:ring-4 focus:ring-violet-500/30">
                        Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
