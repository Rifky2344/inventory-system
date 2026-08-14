<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent">
                    {{ isset($customer) ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' }}
                </h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Lengkapi informasi profil, kontak, dan alamat pelanggan.</p>
            </div>
            <a href="{{ route('customers.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-sky-50 hover:border-sky-200 hover:shadow-sky-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-sky-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] overflow-hidden relative">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-sky-400 via-indigo-400 to-violet-400"></div>
            
            <form method="POST" action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}" class="p-8 sm:p-10">
                @csrf
                @if (isset($customer))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Informasi Utama -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Informasi Utama</h3>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Pelanggan / Perusahaan <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" required placeholder="Contoh: PT. Maju Jaya"
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm" />
                            @error('name')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Penanggung Jawab (PIC)</label>
                            <input type="text" name="pic" value="{{ old('pic', $customer->pic ?? '') }}" placeholder="Nama PIC (Opsional)"
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm" />
                            @error('pic')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Kontak <span class="text-rose-500">*</span></label>
                                <input type="tel" name="contact" value="{{ old('contact', $customer->contact ?? '') }}" required placeholder="0812..."
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm" />
                                @error('contact')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" placeholder="email@domain.com"
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm" />
                                @error('email')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Lokasi -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Informasi Lokasi</h3>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="3" required placeholder="Jalan, RT/RW, Gedung..."
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-4 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm resize-none">{{ old('address', $customer->address ?? '') }}</textarea>
                            @error('address')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Provinsi <span class="text-rose-500">*</span></label>
                                <input type="text" name="province" value="{{ old('province', $customer->province ?? '') }}" required placeholder="Jawa Barat"
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm" />
                                @error('province')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Kota/Kabupaten <span class="text-rose-500">*</span></label>
                                <input type="text" name="city" value="{{ old('city', $customer->city ?? '') }}" required placeholder="Bandung"
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm" />
                                @error('city')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan</label>
                            <textarea name="note" rows="2" placeholder="Informasi lain (Opsional)"
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all shadow-sm resize-none">{{ old('note', $customer->note ?? '') }}</textarea>
                            @error('note')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 flex items-center justify-end gap-4 border-t border-slate-100/80">
                    <a href="{{ route('customers.index') }}" class="px-6 py-3.5 rounded-2xl text-sm font-bold text-slate-500 bg-slate-50/50 hover:bg-slate-100 hover:text-slate-800 transition-all focus:outline-none focus:ring-4 focus:ring-slate-100">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-indigo-500 hover:from-sky-600 hover:to-indigo-600 shadow-lg shadow-sky-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-sky-500/40 focus:outline-none focus:ring-4 focus:ring-sky-500/30">
                        {{ isset($customer) ? 'Update Pelanggan' : 'Simpan Pelanggan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
