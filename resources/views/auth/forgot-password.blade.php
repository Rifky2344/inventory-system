<x-auth-layout>
    <div class="mb-10 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center mx-auto mb-6 shadow-inner border border-indigo-100">
            <svg class="w-8 h-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>
        <h2 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Lupa Kata Sandi?</h2>
        <p class="text-sm text-slate-500 font-medium mt-2 leading-relaxed">Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan tautan untuk mereset kata sandi Anda.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-grow text-sm font-bold text-emerald-800">{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2" for="email">Alamat Email Terdaftar</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="budi@contoh.com"
                    class="w-full rounded-2xl border border-slate-200/70 bg-white/50 py-4 pl-12 pr-4 text-slate-700 font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-sm" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl text-base font-bold text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-indigo-500/40 focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Tautan Reset
            </button>
        </div>

        <div class="text-center mt-6">
            <a class="inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors group" href="{{ route('login') }}">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Halaman Login
            </a>
        </div>
    </form>
</x-auth-layout>
