<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Gudang Hub') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-amber-50 text-gray-900">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-emerald-900 border-r border-emerald-950/20 p-4 hidden md:block">
            @php
                $role = auth()->user()->role?->slug;
                $navBase = 'block px-3 py-2 rounded-md transition-colors';
                $navActive = 'bg-emerald-800 text-white font-medium';
                $navIdle = 'text-emerald-100 hover:bg-emerald-800/60 hover:text-white';
            @endphp

            <div class="mb-6 pb-4 border-b border-emerald-800/70">
                <div class="text-lg font-semibold leading-tight text-white">Gudang Hub</div>
                <div class="text-xs text-emerald-200">Inventory System</div>
            </div>

            <nav class="space-y-5 text-sm">
                @if (in_array($role, ['admin', 'super-admin'], true))
                    <div class="space-y-1">
                        <div class="px-3 text-xs font-medium uppercase tracking-wide text-emerald-200">Ringkasan</div>
                        <a href="{{ route('dashboard') }}" class="{{ $navBase }} {{ request()->routeIs('dashboard') ? $navActive : $navIdle }}">Dashboard</a>
                    </div>
                @endif

                <div class="space-y-1">
                    <div class="px-3 text-xs font-medium uppercase tracking-wide text-emerald-200">Master</div>
                    <a href="{{ route('items.index') }}" class="{{ $navBase }} {{ request()->routeIs('items.*') ? $navActive : $navIdle }}">Barang</a>
                    <a href="{{ route('categories.index') }}" class="{{ $navBase }} {{ request()->routeIs('categories.*') ? $navActive : $navIdle }}">Kategori</a>
                </div>

                <div class="space-y-1">
                    <div class="px-3 text-xs font-medium uppercase tracking-wide text-emerald-200">Transaksi</div>
                    <a href="{{ route('transactions.index') }}" class="{{ $navBase }} {{ request()->routeIs('transactions.*') ? $navActive : $navIdle }}">Transaksi</a>

                    @if (in_array($role, ['admin', 'super-admin'], true))
                        <a href="{{ route('mutations.index') }}" class="{{ $navBase }} {{ request()->routeIs('mutations.*') ? $navActive : $navIdle }}">Mutasi Stok</a>
                    @endif
                </div>

                @if ($role === 'super-admin')
                    <div class="space-y-1">
                        <div class="px-3 text-xs font-medium uppercase tracking-wide text-emerald-200">Super Admin</div>
                        <a href="{{ route('users.index') }}" class="{{ $navBase }} {{ request()->routeIs('users.*') ? $navActive : $navIdle }}">Users</a>
                        <a href="{{ route('activity-logs.index') }}" class="{{ $navBase }} {{ request()->routeIs('activity-logs.*') ? $navActive : $navIdle }}">Activity Log</a>
                    </div>
                @endif
            </nav>
        </aside>

        <div class="flex-1">
            <header class="bg-white/80 backdrop-blur border-b border-emerald-100">
                <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                    <div class="leading-tight">
                        <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ auth()->user()->role?->name }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-emerald-800 hover:underline" type="submit">Logout</button>
                    </form>
                </div>
            </header>

            <main class="max-w-6xl mx-auto px-4 py-6">
                @if (session('success'))
                    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md p-3">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
