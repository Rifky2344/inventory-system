<x-app-layout>
    <div class="mb-8 p-6 sm:p-8 rounded-[2rem] bg-white/60 backdrop-blur-xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Log Aktivitas Sistem</h1>
                </div>
                <p class="text-sm text-slate-500 font-medium">Lacak riwayat login, perubahan data (CRUD), dan rekam jejak aktivitas operasional di seluruh sistem.</p>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="mb-6 bg-white/70 backdrop-blur-xl border border-white/60 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative z-20">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-grow">
                <label class="sr-only" for="q">Cari Aktivitas Log</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input
                        id="q"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari aksi, nama user, alamat IP, atau entitas terkait..."
                        class="w-full rounded-2xl border border-slate-200 bg-white/50 py-3.5 pl-12 pr-4 text-slate-700 font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all shadow-sm"
                    />
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button type="submit" class="rounded-2xl bg-slate-800 px-6 py-3.5 text-sm font-bold text-white hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-500/20 transition-all focus:outline-none focus:ring-4 focus:ring-slate-500/30">
                    Cari Log
                </button>
                @if(request('q'))
                    <a href="{{ route('activity-logs.index') }}" class="p-3.5 rounded-2xl border border-slate-200 bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20" title="Reset Pencarian">
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
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Waktu Eksekusi</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Pengguna</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Tindakan (Action)</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Objek / Modul (Subject)</th>
                        <th class="px-6 py-5 font-extrabold text-slate-500 uppercase tracking-wider text-[11px]">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse ($logs as $log)
                        <tr class="transition-colors hover:bg-slate-50/60 group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0 border border-slate-200">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="font-bold text-sm">{{ $log->created_at?->format('d M Y') }} <span class="text-slate-400 ml-1">{{ $log->created_at?->format('H:i:s') }}</span></span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($log->user)
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 border border-indigo-200 flex items-center justify-center shrink-0 text-indigo-700 font-bold text-xs shadow-sm">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-700">{{ $log->user->name }}</span>
                                            <span class="text-[10px] font-medium text-slate-400">{{ optional($log->user->role)->name ?? 'User' }}</span>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <span class="font-bold text-slate-400 italic">Sistem / Guest</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $actionStyle = 'bg-slate-100 text-slate-700 border-slate-200';
                                    $actionIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                                    
                                    $lowerAction = strtolower($log->action);
                                    if(str_contains($lowerAction, 'create') || str_contains($lowerAction, 'store') || str_contains($lowerAction, 'add') || str_contains($lowerAction, 'login')) {
                                        $actionStyle = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                        $actionIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>';
                                    } elseif (str_contains($lowerAction, 'update') || str_contains($lowerAction, 'edit') || str_contains($lowerAction, 'modify')) {
                                        $actionStyle = 'bg-amber-100 text-amber-700 border-amber-200';
                                        $actionIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>';
                                    } elseif (str_contains($lowerAction, 'delete') || str_contains($lowerAction, 'destroy') || str_contains($lowerAction, 'remove')) {
                                        $actionStyle = 'bg-rose-100 text-rose-700 border-rose-200';
                                        $actionIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>';
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold {{ $actionStyle }} border shadow-sm tracking-wide">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $actionIcon !!}</svg>
                                    {{ $log->action }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if($log->subject_type)
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                            {{ class_basename($log->subject_type) }}
                                        </span>
                                        @if($log->subject_id)
                                            <span class="text-xs font-bold font-mono text-indigo-500">#{{ substr($log->subject_id, 0, 8) }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 font-bold text-xs">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($log->ip_address)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                        <span class="font-mono text-xs font-bold text-slate-600 bg-slate-50 px-2 py-1 rounded border border-slate-200">{{ $log->ip_address }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-bold text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 mb-4 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-600 text-base">Log Aktivitas Kosong</p>
                                    <p class="mt-1 text-sm text-slate-500">Sistem belum mencatat aktivitas apa pun atau sesuaikan filter pencarian.</p>
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
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
