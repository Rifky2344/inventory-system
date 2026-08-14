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
<body class="min-h-screen font-sans antialiased text-slate-800 bg-slate-50 selection:bg-emerald-200 selection:text-emerald-900 overflow-x-hidden relative">
    
    <!-- Modern Global Background -->
    <div class="fixed inset-0 z-[-1] pointer-events-none">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-100/50 via-slate-50 to-teal-100/50"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-200/30 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-teal-200/30 blur-[120px]"></div>
    </div>

    @php
        $role = auth()->user()?->role?->slug;
    @endphp

    <div class="flex min-h-screen relative">
        
        <!-- Desktop Sidebar (Glassmorphism Light) -->
        <aside class="hidden lg:flex w-72 shrink-0 flex-col justify-between bg-white/70 backdrop-blur-xl border-r border-white/60 shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-30 transition-all duration-300">
            <div class="h-full flex flex-col overflow-y-auto overflow-x-hidden no-scrollbar">
                <!-- Brand logo -->
                <div class="px-8 py-8 flex items-center gap-4 sticky top-0 bg-white/40 backdrop-blur-md z-10 border-b border-slate-100/50">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 text-white font-bold text-xl shrink-0 ring-4 ring-white">
                        G
                    </div>
                    <div class="min-w-0">
                        <h2 class="truncate text-xl font-bold tracking-tight text-slate-800 leading-none">{{ config('app.name', 'Gudang Hub') }}</h2>
                        <span class="block mt-1 text-[10px] font-bold tracking-[0.2em] text-emerald-600 uppercase">Inventory ERP</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="p-5 space-y-1.5 flex-1">
                    @php
                        $navItemClass = "group flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all duration-300 font-semibold text-sm ";
                        $navActive = "bg-gradient-to-r from-emerald-50 to-teal-50/50 text-emerald-700 shadow-sm border border-emerald-100/50";
                        $navInactive = "text-slate-500 hover:text-emerald-600 hover:bg-slate-50/80 hover:scale-[1.02]";
                    @endphp

                    @if(in_array($role, ['admin', 'super-admin'], true))
                        <a href="{{ route('dashboard') }}" class="{{ $navItemClass }} {{ request()->routeIs('dashboard') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                            <span class="truncate">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('warehouse-orders.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('warehouse-orders.index') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                            <span class="truncate">Dashboard</span>
                        </a>
                    @endif

                    <!-- Kepegawaian Section -->
                    <div class="pt-6 pb-2 px-4 text-[10px] font-extrabold tracking-[0.2em] text-slate-400 uppercase">Kepegawaian</div>
                    <a href="{{ route('attendances.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('attendances.*') ? $navActive : $navInactive }}">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="truncate">Absensi</span>
                    </a>

                    @if(in_array($role, ['staff-gudang', 'admin', 'super-admin'], true))
                        <!-- Stok & Inventaris Section -->
                        <div class="pt-6 pb-2 px-4 text-[10px] font-extrabold tracking-[0.2em] text-slate-400 uppercase">Stok & Inventaris</div>
                        
                        <a href="{{ route('categories.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('categories.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                            <span class="truncate">Kategori</span>
                        </a>
                        <a href="{{ route('items.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('items.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span class="truncate">Items</span>
                        </a>
                        <a href="{{ route('mutations.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('mutations.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2"/></svg>
                            <span class="truncate">Mutasi Stok</span>
                        </a>
                        <a href="{{ route('warehouse-orders.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('warehouse-orders.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="truncate">Daftar Pesanan</span>
                        </a>
                    @endif

                    @if(in_array($role, ['sales', 'admin', 'super-admin'], true))
                        <!-- ERP & Keuangan Section -->
                        <div class="pt-6 pb-2 px-4 text-[10px] font-extrabold tracking-[0.2em] text-slate-400 uppercase">ERP & Keuangan</div>
                        
                        <a href="{{ route('customers.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('customers.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="truncate">Customers</span>
                        </a>
                        <a href="{{ route('sales-visits.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('sales-visits.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate">Sales Visits</span>
                        </a>
                        <a href="{{ route('invoices.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('invoices.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="truncate">Invoices</span>
                        </a>
                        @if(in_array($role, ['admin', 'super-admin'], true))
                            <a href="{{ route('payments.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('payments.*') ? $navActive : $navInactive }}">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="truncate">Payments</span>
                            </a>
                            <a href="{{ route('installment-plans.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('installment-plans.*') ? $navActive : $navInactive }}">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="truncate">Installments</span>
                            </a>
                        @endif
                    @endif

                    @if($role === 'super-admin')
                        <!-- Sistem Section -->
                        <div class="pt-6 pb-2 px-4 text-[10px] font-extrabold tracking-[0.2em] text-slate-400 uppercase">Sistem</div>
                        
                        <a href="{{ route('users.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('users.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span class="truncate">Manajemen User</span>
                        </a>
                        <a href="{{ route('activity-logs.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('activity-logs.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="truncate">Activity Log</span>
                        </a>
                    @endif
                </nav>
            </div>
            
            <!-- Quick footer profile -->
            <div class="p-5 border-t border-slate-100/50 bg-slate-50/30 sticky bottom-0">
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/60 shadow-sm border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center font-bold text-white shadow-inner shrink-0 ring-2 ring-white overflow-hidden">
                        @if(auth()->user()?->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()?->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="min-w-0 overflow-hidden flex-1">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()?->name }}</p>
                        <p class="text-xs font-semibold text-emerald-600 truncate uppercase tracking-wider">{{ auth()->user()?->role?->name ?? 'User' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Backdrop -->
        <div id="mobile-sidebar-backdrop" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm hidden transition-opacity duration-300"></div>

        <!-- Mobile Sidebar -->
        <aside id="mobile-sidebar" class="fixed top-0 bottom-0 left-0 z-50 hidden w-72 bg-white text-slate-800 p-6 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col justify-between shadow-2xl overflow-y-auto">
            <div>
                <!-- Brand logo & Close button -->
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-lg ring-2 ring-emerald-100">
                            G
                        </div>
                        <div class="min-w-0">
                            <h2 class="truncate text-base font-bold text-slate-800 leading-none">{{ config('app.name', 'Gudang Hub') }}</h2>
                            <span class="block mt-1 text-[10px] font-bold text-emerald-600 tracking-wider uppercase">Inventory ERP</span>
                        </div>
                    </div>
                    <button id="menu-close" class="p-2 rounded-xl bg-slate-100 text-slate-500 hover:text-slate-800 hover:bg-slate-200 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Navigation Mobile (same structure as Desktop) -->
                <nav class="space-y-1 text-sm font-medium">
                    <!-- Dashboard -->
                    @if(in_array($role, ['admin', 'super-admin'], true))
                        <a href="{{ route('dashboard') }}" class="{{ $navItemClass }} {{ request()->routeIs('dashboard') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('warehouse-orders.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('warehouse-orders.index') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    <!-- Kepegawaian -->
                    <div class="pt-6 pb-2 px-4 text-[10px] font-extrabold tracking-[0.2em] text-slate-400 uppercase">Kepegawaian</div>
                    <a href="{{ route('attendances.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('attendances.*') ? $navActive : $navInactive }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Absensi</span>
                    </a>

                    @if(in_array($role, ['staff-gudang', 'admin', 'super-admin'], true))
                        <!-- Stok & Inventaris -->
                        <div class="pt-6 pb-2 px-4 text-[10px] font-extrabold tracking-[0.2em] text-slate-400 uppercase">Stok & Inventaris</div>
                        <a href="{{ route('categories.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('categories.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                            <span>Kategori</span>
                        </a>
                        <a href="{{ route('items.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('items.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Items</span>
                        </a>
                        <a href="{{ route('mutations.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('mutations.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2"/></svg>
                            <span>Mutasi Stok</span>
                        </a>
                        <a href="{{ route('warehouse-orders.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('warehouse-orders.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Daftar Pesanan</span>
                        </a>
                    @endif

                    @if(in_array($role, ['sales', 'admin', 'super-admin'], true))
                        <!-- ERP & Keuangan -->
                        <div class="pt-6 pb-2 px-4 text-[10px] font-extrabold tracking-[0.2em] text-slate-400 uppercase">ERP & Keuangan</div>
                        <a href="{{ route('customers.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('customers.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Customers</span>
                        </a>
                        <a href="{{ route('sales-visits.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('sales-visits.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Sales Visits</span>
                        </a>
                        <a href="{{ route('invoices.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('invoices.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Invoices</span>
                        </a>
                        @if(in_array($role, ['admin', 'super-admin'], true))
                            <a href="{{ route('payments.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('payments.*') ? $navActive : $navInactive }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Payments</span>
                            </a>
                            <a href="{{ route('installment-plans.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('installment-plans.*') ? $navActive : $navInactive }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Installments</span>
                            </a>
                        @endif
                    @endif

                    @if($role === 'super-admin')
                        <!-- Sistem -->
                        <div class="pt-6 pb-2 px-4 text-[10px] font-extrabold tracking-[0.2em] text-slate-400 uppercase">Sistem</div>
                        <a href="{{ route('users.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('users.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Manajemen User</span>
                        </a>
                        <a href="{{ route('activity-logs.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('activity-logs.*') ? $navActive : $navInactive }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Activity Log</span>
                        </a>
                    @endif
                </nav>
            </div>
            
            <div class="p-4 mt-6 border-t border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center font-bold text-white shadow-inner overflow-hidden">
                    @if(auth()->user()?->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr(auth()->user()?->name, 0, 1) }}
                    @endif
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">{{ auth()->user()?->name }}</p>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">{{ auth()->user()?->role?->name ?? 'User' }}</p>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 flex flex-col justify-between min-h-screen overflow-y-auto w-full z-10">
            <div class="max-w-[1600px] mx-auto w-full">
                
                <!-- Modern Top Header -->
                <header class="flex flex-wrap items-center justify-between gap-4 mb-8 bg-white/70 backdrop-blur-2xl border border-white/60 rounded-[2rem] px-5 sm:px-8 py-4 shadow-[0_4px_24px_rgba(0,0,0,0.02)]">
                    <div class="flex items-center gap-4 min-w-0">
                        <button id="menu-toggle" class="lg:hidden p-2.5 rounded-xl bg-slate-50 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-colors shadow-sm border border-slate-100 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div class="min-w-0">
                            <h1 class="truncate text-lg sm:text-2xl font-extrabold text-slate-800 tracking-tight">@yield('title', 'Dashboard')</h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-5 min-w-0 flex-wrap justify-end">
                        <a href="{{ route('profile.edit') }}" class="hidden sm:flex items-center gap-3 rounded-2xl border border-slate-100 bg-white/50 px-3 py-2 shadow-sm hover:bg-teal-50 hover:border-teal-100 transition-colors group cursor-pointer">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-700 flex items-center justify-center font-bold text-sm shadow-inner ring-1 ring-emerald-200/50 overflow-hidden group-hover:ring-teal-300 transition-all">
                                @if(auth()->user()?->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr(auth()->user()?->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="min-w-0 text-sm">
                                <p class="font-bold text-slate-700 group-hover:text-teal-700 transition-colors truncate pr-2">{{ auth()->user()?->name }}</p>
                            </div>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl border border-rose-100 bg-rose-50/50 px-5 py-2.5 text-sm font-bold text-rose-600 transition-all duration-300 hover:bg-rose-500 hover:text-white hover:border-rose-500 hover:shadow-lg hover:shadow-rose-500/20 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                    </div>
                </header>

                <main class="w-full">
                    {{ $slot ?? '' }}
                </main>
            </div>

            <footer class="mt-16 text-center lg:text-left text-xs font-semibold tracking-wide text-slate-400 border-t border-slate-200/60 pt-8 pb-4 max-w-[1600px] mx-auto w-full">
                &copy; {{ date('Y') }} {{ config('app.name', 'Gudang Hub') }}. Crafted with premium aesthetics.
            </footer>
        </div>
    </div>

    @stack('scripts')

    <!-- Toggle sidebar script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.getElementById('menu-toggle');
            const menuClose = document.getElementById('menu-close');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');

            function openSidebar() {
                if (!mobileSidebar || !backdrop) return;
                mobileSidebar.classList.remove('hidden');
                requestAnimationFrame(() => {
                    mobileSidebar.classList.remove('-translate-x-full');
                });
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                if (!mobileSidebar || !backdrop) return;
                mobileSidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                window.setTimeout(() => {
                    if (mobileSidebar.classList.contains('-translate-x-full')) {
                        mobileSidebar.classList.add('hidden');
                    }
                }, 300);
            }

            if (menuToggle) menuToggle.addEventListener('click', openSidebar);
            if (menuClose) menuClose.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeSidebar();
            });
            closeSidebar();
        });
    </script>
</body>
</html>
