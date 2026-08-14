<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-fuchsia-600 flex items-center justify-center text-white shadow-lg shadow-rose-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-rose-600 to-fuchsia-600 bg-clip-text text-transparent">Manajemen Pengguna</h1>
                </div>
                <p class="text-sm text-slate-500 font-medium">Kelola hak akses, role, dan data akun sistem (Khusus Super Admin).</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('users.create') }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-fuchsia-600 px-6 py-3.5 font-bold text-white shadow-lg shadow-rose-500/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-rose-500/40 focus:outline-none focus:ring-4 focus:ring-rose-500/30">
                    <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 flex items-center gap-3 shadow-sm" x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms>
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-grow text-sm font-bold text-emerald-800">{{ session('success') }}</div>
            <button @click="show = false" class="p-1 rounded-full text-emerald-500 hover:bg-emerald-200 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="mb-6 rounded-2xl bg-rose-50 p-4 border border-rose-200 flex items-center gap-3 shadow-sm" x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms>
            <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-grow text-sm font-bold text-rose-800">{{ session('error') }}</div>
            <button @click="show = false" class="p-1 rounded-full text-rose-500 hover:bg-rose-200 hover:text-rose-700 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Search Section -->
    <div class="mb-6 bg-white/70 backdrop-blur-xl border border-white/60 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative z-20">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-grow">
                <label class="sr-only" for="q">Cari Pengguna</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input
                        id="q"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Ketik nama, email, atau role..."
                        class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3.5 pl-12 pr-4 text-slate-700 font-medium focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all shadow-sm"
                    />
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button type="submit" class="rounded-2xl bg-slate-800 px-6 py-3.5 text-sm font-bold text-white hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-500/20 transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                    Cari Data
                </button>
                @if(request('q'))
                    <a href="{{ route('users.index') }}" class="p-3.5 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20" title="Reset Pencarian">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white/70 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Profil Pengguna</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Email Terdaftar</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Role / Akses</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Waktu Registrasi</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px] text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($users as $u)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 border border-slate-300 flex items-center justify-center shrink-0 text-slate-600 font-extrabold shadow-sm">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-base flex items-center gap-2">
                                            {{ $u->name }}
                                            @if(auth()->id() === $u->id)
                                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wider">Anda</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-600 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ $u->email }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $roleStyle = 'bg-slate-100 text-slate-700 border-slate-200';
                                    if(optional($u->role)->slug === 'super-admin') {
                                        $roleStyle = 'bg-fuchsia-100 text-fuchsia-700 border-fuchsia-200';
                                    } elseif (optional($u->role)->slug === 'admin') {
                                        $roleStyle = 'bg-indigo-100 text-indigo-700 border-indigo-200';
                                    } elseif (optional($u->role)->slug === 'sales') {
                                        $roleStyle = 'bg-sky-100 text-sky-700 border-sky-200';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold {{ $roleStyle }} border shadow-sm">
                                    {{ $u->role?->name ?? 'Tanpa Role' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="font-medium">{{ $u->created_at?->format('d M Y, H:i') }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $u) }}" class="p-2 rounded-xl border border-slate-200 bg-white text-amber-500 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20" title="Ubah Pengguna">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>

                                    @if(auth()->id() !== $u->id)
                                        <form method="POST" action="{{ route('users.destroy', $u) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $u->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl border border-slate-200 bg-white text-rose-500 hover:text-white hover:bg-rose-500 hover:border-rose-500 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20" title="Hapus Pengguna">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <div class="p-2 rounded-xl border border-slate-100 bg-slate-50 text-slate-300 cursor-not-allowed" title="Tidak dapat menghapus diri sendiri">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Pengguna Tidak Ditemukan</p>
                                    <p class="mt-1 text-sm text-slate-500">Belum ada akun terdaftar atau sesuaikan filter pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex-grow">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
