<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">Detail Absensi</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Informasi absensi pegawai pada tanggal {{ $attendance->check_in_time->format('d M Y') }}</p>
            </div>
            <a href="{{ route('attendances.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white/60 backdrop-blur-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-200/60 hover:bg-emerald-50 hover:border-emerald-200 hover:shadow-emerald-500/10 transition-all duration-300 hover:scale-105">
                <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] overflow-hidden relative p-8">
            <div class="flex flex-col items-center">
                <div class="w-64 h-64 rounded-3xl overflow-hidden shadow-lg border-4 border-white mb-6">
                    <img src="{{ Storage::url($attendance->photo_path) }}" alt="Foto Absensi" class="w-full h-full object-cover">
                </div>
                
                <h3 class="text-2xl font-bold text-slate-800">{{ $attendance->user->name }}</h3>
                <span class="inline-flex items-center px-4 py-1.5 mt-2 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                    {{ $attendance->user->role->name ?? '-' }}
                </span>
                
                <div class="mt-8 grid grid-cols-2 gap-6 w-full max-w-md">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal</p>
                        <p class="font-semibold text-slate-700">{{ $attendance->check_in_time->format('d M Y') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Waktu</p>
                        <p class="font-semibold text-slate-700">{{ $attendance->check_in_time->format('H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
