<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-rose-600 to-fuchsia-600 bg-clip-text text-transparent">Edit Pengguna</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Ubah informasi profil, role, atau reset kata sandi untuk <span class="font-bold text-slate-700">{{ $user->name }}</span>.</p>
            </div>
            
            <a href="{{ route('users.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-rose-50 hover:border-rose-200 hover:shadow-rose-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-rose-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-rose-400 via-fuchsia-400 to-indigo-400"></div>
            
            <form method="POST" action="{{ route('users.update', $user) }}" class="p-8 sm:p-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Basic Info -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Informasi Profil
                        </h3>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all shadow-sm" />
                            @error('name')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 px-4 text-slate-700 font-medium focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all shadow-sm" />
                            @error('email')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Peran / Hak Akses <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select id="role_id" name="role_id" required
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-10 text-slate-700 font-medium focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all shadow-sm appearance-none cursor-pointer">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('role_id')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Right Column: Security Info -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Kredensial Login (Opsional)
                        </h3>

                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-100 mb-2">
                            <p class="text-xs text-amber-700 font-medium leading-relaxed flex items-start gap-2">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Biarkan field kata sandi kosong jika Anda tidak ingin mereset/mengganti kata sandi pengguna ini.</span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi Baru</label>
                            <div class="relative group">
                                <input id="password" name="password" type="password" placeholder="••••••••"
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-12 text-slate-700 font-medium focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all shadow-sm" />
                                <button type="button" data-toggle-password data-target="password"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 focus:outline-none transition-colors p-1.5 rounded-lg hover:bg-rose-50">
                                    <span data-toggle-label class="sr-only">Show</span>
                                    <svg data-icon-hide class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg data-icon-show class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.293M3 3l3.29 3.29M3 3l3.29 3.29" /></svg>
                                </button>
                            </div>
                            @error('password')<p class="mt-2 text-xs font-bold text-rose-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Kata Sandi</label>
                            <div class="relative group">
                                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••"
                                    class="w-full rounded-2xl border border-slate-200/70 bg-white py-3.5 pl-4 pr-12 text-slate-700 font-medium focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all shadow-sm" />
                                <button type="button" data-toggle-password data-target="password_confirmation"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 focus:outline-none transition-colors p-1.5 rounded-lg hover:bg-rose-50">
                                    <span data-toggle-label class="sr-only">Show</span>
                                    <svg data-icon-hide class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg data-icon-show class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.293M3 3l3.29 3.29M3 3l3.29 3.29" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 flex items-center justify-end gap-4 border-t border-slate-100/80">
                    <a href="{{ route('users.index') }}" class="px-6 py-3.5 rounded-2xl text-sm font-bold text-slate-500 bg-slate-50/50 hover:bg-slate-100 hover:text-slate-800 transition-all focus:outline-none focus:ring-4 focus:ring-slate-100">
                        Batalkan
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-rose-500 to-fuchsia-600 hover:from-rose-600 hover:to-fuchsia-700 shadow-lg shadow-rose-500/25 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-rose-500/40 focus:outline-none focus:ring-4 focus:ring-rose-500/30">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
