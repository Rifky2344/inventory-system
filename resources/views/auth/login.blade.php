<x-auth-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Selamat Datang Kembali</h2>
        <p class="text-sm text-slate-500 font-medium mt-2">Silakan masuk ke akun Anda untuk melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2" for="email">Alamat Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="contoh@perusahaan.com"
                    class="w-full rounded-2xl border border-slate-200/70 bg-white/50 py-4 pl-12 pr-4 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-bold text-slate-700" for="password">Kata Sandi</label>
            </div>
            
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" name="password" type="password" required placeholder="••••••••"
                    class="w-full rounded-2xl border border-slate-200/70 bg-white/50 py-4 pl-12 pr-12 text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm" />
                
                <button type="button" data-toggle-password data-target="password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500 focus:outline-none transition-colors p-1.5 rounded-lg hover:bg-emerald-50">
                    <span data-toggle-label class="sr-only">Show</span>
                    <svg data-icon-hide class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg data-icon-show class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.293M3 3l3.29 3.29M3 3l3.29 3.29" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-2">
            <label class="inline-flex items-center gap-3 cursor-pointer group">
                <div class="relative flex items-center justify-center">
                    <input type="checkbox" name="remember" value="1" class="peer sr-only" />
                    <div class="w-5 h-5 border-2 border-slate-300 rounded-md peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all group-hover:border-emerald-400"></div>
                    <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline transition-colors" href="{{ route('password.request') }}">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl text-base font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 shadow-lg shadow-emerald-500/25 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-emerald-500/40 focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Masuk ke Sistem
            </button>
        </div>
    </form>
</x-auth-layout>
