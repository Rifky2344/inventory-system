<x-auth-layout>
    <div class="mb-10 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mx-auto mb-6 shadow-inner border border-indigo-100">
            <svg class="w-8 h-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h2 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Reset Kata Sandi</h2>
        <p class="text-sm text-slate-500 font-medium mt-2 leading-relaxed">Silakan buat kata sandi baru untuk mengamankan akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}" />

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2" for="email">Alamat Email Terdaftar</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus readonly
                    class="w-full rounded-2xl border border-slate-200/70 bg-slate-50/50 py-4 pl-12 pr-4 text-slate-500 font-medium cursor-not-allowed shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2" for="password">Kata Sandi Baru</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" name="password" type="password" required placeholder="••••••••"
                    class="w-full rounded-2xl border border-slate-200/70 bg-white/50 py-4 pl-12 pr-12 text-slate-700 font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-sm" />
                <button type="button" data-toggle-password data-target="password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 focus:outline-none transition-colors p-1.5 rounded-lg hover:bg-indigo-50">
                    <span data-toggle-label class="sr-only">Show</span>
                    <svg data-icon-hide class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg data-icon-show class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.293M3 3l3.29 3.29M3 3l3.29 3.29" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="••••••••"
                    class="w-full rounded-2xl border border-slate-200/70 bg-white/50 py-4 pl-12 pr-12 text-slate-700 font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-sm" />
                <button type="button" data-toggle-password data-target="password_confirmation" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 focus:outline-none transition-colors p-1.5 rounded-lg hover:bg-indigo-50">
                    <span data-toggle-label class="sr-only">Show</span>
                    <svg data-icon-hide class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg data-icon-show class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.293M3 3l3.29 3.29M3 3l3.29 3.29" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl text-base font-bold text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-indigo-500/40 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Konfirmasi Reset Kata Sandi
            </button>
        </div>
    </form>
</x-auth-layout>
