<x-app-layout>
    <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold">Activity Log</h1>
            <p class="text-sm text-gray-600">Login, CRUD, dan aktivitas transaksi</p>
        </div>

        <form method="GET" action="{{ route('activity-logs.index') }}" class="flex items-center gap-2">
            <input
                name="q"
                value="{{ $q ?? request('q') }}"
                placeholder="Cari log (aksi/user/ip/subject)..."
                class="w-80 rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200/50"
            />
            <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Cari</button>
            @if (trim((string) ($q ?? request('q'))) !== '')
                <a href="{{ route('activity-logs.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-emerald-50 text-emerald-900">
                <tr>
                    <th class="text-left font-medium px-4 py-3">Waktu</th>
                    <th class="text-left font-medium px-4 py-3">User</th>
                    <th class="text-left font-medium px-4 py-3">Aksi</th>
                    <th class="text-left font-medium px-4 py-3">IP</th>
                    <th class="text-left font-medium px-4 py-3">Subject</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-emerald-100">
                @forelse ($logs as $log)
                    <tr>
                        <td class="px-4 py-3">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $log->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3 font-medium">{{ $log->action }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $log->ip_address ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $log->subject_type ? class_basename($log->subject_type) : '-' }}
                            {{ $log->subject_id ? ('#'.substr($log->subject_id, 0, 8)) : '' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-gray-500" colspan="5">Belum ada log.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
</x-app-layout>
