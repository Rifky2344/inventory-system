<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Gudang Hub') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased selection:bg-emerald-200 selection:text-emerald-900">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side: Branding & Aesthetics (Hidden on mobile) -->
        <div class="hidden md:flex md:w-1/2 lg:w-7/12 relative overflow-hidden bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 items-center justify-center p-12">
            <!-- Abstract background shapes -->
            <div class="absolute top-[-20%] left-[-10%] w-[70%] h-[70%] rounded-full bg-emerald-500/20 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] rounded-full bg-teal-500/20 blur-[100px]"></div>
            
            <!-- Glassmorphism Card -->
            <div class="relative z-10 w-full max-w-lg">
                <div class="bg-white/10 backdrop-blur-2xl border border-white/20 p-12 rounded-[2.5rem] shadow-2xl">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-3xl mb-8 shadow-lg shadow-emerald-500/30 ring-4 ring-white/20">
                        G
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4 leading-tight">
                        Platform <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-300">Enterprise</span> Anda.
                    </h1>
                    <p class="text-lg text-emerald-100/80 leading-relaxed font-medium">
                        Sistem manajemen pergudangan cerdas untuk memantau inventaris, penjualan, dan operasional bisnis Anda dari satu tempat.
                    </p>
                </div>

                <!-- Trusted by badges or aesthetic lines could go here -->
                <div class="mt-12 flex items-center gap-4 text-emerald-200/50">
                    <hr class="flex-1 border-emerald-500/30">
                    <span class="text-sm font-semibold tracking-[0.2em] uppercase">Gudang Hub ERP</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Auth Form -->
        <div class="w-full md:w-1/2 lg:w-5/12 flex items-center justify-center p-6 sm:p-12 bg-white relative">
            <!-- Mobile Background Pattern (Only visible on small screens) -->
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-50 via-white to-white md:hidden z-0"></div>

            <div class="w-full max-w-md relative z-10">
                <!-- Mobile Branding Header -->
                <div class="md:hidden flex flex-col items-center mb-10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-2xl mb-4 shadow-lg shadow-emerald-500/30 ring-4 ring-emerald-50">
                        G
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight text-center">Gudang Hub</h1>
                    <p class="text-emerald-600 font-semibold tracking-wider text-xs uppercase mt-2">Inventory Management</p>
                </div>

                <div class="mb-8 hidden md:block">
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Selamat Datang 👋</h2>
                    <p class="text-slate-500 mt-2">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 text-sm font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 text-sm font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Auth Content Injection -->
                <div class="bg-white md:bg-transparent p-6 md:p-0 rounded-3xl md:rounded-none shadow-xl shadow-slate-200/50 md:shadow-none border border-slate-100 md:border-none">
                    {{ $slot }}
                </div>

                <p class="mt-10 text-center text-xs font-semibold text-slate-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Gudang Hub') }}. Crafted with premium aesthetics.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
