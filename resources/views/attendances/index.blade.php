<x-app-layout>
    <!-- Header -->
    <div class="mb-8 p-6 rounded-3xl bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">Histori Absensi</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Pantau rekam jejak kehadiran {{ in_array(auth()->user()->role?->slug, ['admin', 'super-admin']) ? 'semua pegawai' : 'Anda' }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if (in_array(auth()->user()->role?->slug, ['admin', 'super-admin']))
                    <a href="{{ route('attendances.export') }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-white border border-slate-200 px-5 py-3 font-semibold text-slate-700 shadow-sm transition-all duration-300 hover:scale-105 hover:shadow-md hover:border-emerald-200 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        <span>Export Excel</span>
                    </a>
                @endif
                <a href="{{ route('attendances.create') }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 px-5 py-3 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-emerald-500/40 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <span class="absolute inset-0 bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Absen Sekarang</span>
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-50/80 backdrop-blur-md p-4 text-sm text-emerald-800 border border-emerald-200/60 shadow-sm flex items-center gap-3">
            <div class="p-2 bg-emerald-100 rounded-xl text-emerald-600 shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter Panel -->
    <div class="mb-6 bg-white/70 backdrop-blur-xl border border-white/60 rounded-3xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <form method="GET" action="{{ route('attendances.index') }}" class="flex flex-col sm:flex-row flex-wrap items-end gap-3">

            {{-- Quick Period Buttons --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Periode Cepat</label>
                <div class="flex gap-2">
                    @foreach (['today' => 'Hari Ini', 'week' => '1 Minggu', 'month' => '1 Bulan'] as $val => $label)
                        <a href="{{ route('attendances.index', ['period' => $val]) }}"
                           class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all duration-200
                               {{ request('period') === $val
                                   ? 'bg-emerald-500 text-white border-emerald-500 shadow-lg shadow-emerald-500/30'
                                   : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-300 hover:text-emerald-600' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Divider --}}
            <div class="hidden sm:block w-px h-10 bg-slate-200 self-end mb-1"></div>

            {{-- Specific Date --}}
            <div class="flex flex-col gap-1.5">
                <label for="date" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Spesifik</label>
                <div class="flex gap-2 items-center">
                    <input type="date" id="date" name="date" value="{{ request('date') }}"
                           class="rounded-2xl border border-slate-200 bg-white/50 py-2.5 px-4 text-sm text-slate-700 font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-sm cursor-pointer">
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl bg-emerald-500 text-white text-sm font-bold hover:bg-emerald-600 transition-all shadow-md shadow-emerald-500/30">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari
                    </button>
                </div>
            </div>

            {{-- Reset --}}
            @if (request('period') || request('date'))
                <a href="{{ route('attendances.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl bg-slate-100 text-slate-600 text-sm font-semibold hover:bg-slate-200 transition-all self-end">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
            @endif

        </form>
    </div>

    <!-- Table -->
    <div class="bg-white/70 backdrop-blur-xl border border-white/50 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 font-bold text-slate-600 tracking-wide uppercase text-xs">Waktu Absen</th>
                        <th class="px-6 py-5 font-bold text-slate-600 tracking-wide uppercase text-xs">Nama Pegawai</th>
                        <th class="px-6 py-5 font-bold text-slate-600 tracking-wide uppercase text-xs">Role</th>
                        <th class="px-6 py-5 font-bold text-slate-600 tracking-wide uppercase text-xs text-center">Bukti Foto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($attendances as $attendance)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ $attendance->check_in_time->format('d M Y') }}</span>
                                    <span class="text-xs font-semibold text-emerald-600 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $attendance->check_in_time->format('H:i:s') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold shadow-sm shadow-emerald-500/20 ring-2 ring-white">
                                        {{ substr($attendance->user->name, 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-slate-700 group-hover:text-emerald-700 transition-colors">{{ $attendance->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 shadow-sm">
                                    {{ $attendance->user->role->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($attendance->photo_path)
                                    <a href="{{ route('attendances.show', $attendance->id) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl text-emerald-700 bg-emerald-50 hover:bg-emerald-100 hover:scale-105 transition-all duration-200 border border-emerald-200/60 shadow-sm ring-1 ring-black/5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-slate-400 font-semibold px-4 py-2 bg-slate-50 rounded-xl border border-slate-100 inline-block">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-100 flex items-center justify-center border border-slate-200/60 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Tidak ada data absensi</p>
                                    <p class="mt-1 text-sm text-slate-500">Coba ubah filter atau mulai absensi sekarang.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($attendances->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/40">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
