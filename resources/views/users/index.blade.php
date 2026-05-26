<x-app-layout>
    <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold">Users</h1>
            <p class="text-sm text-gray-600">Kelola akun pengguna (Super Admin)</p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2">
                <input
                    name="q"
                    value="{{ $q ?? request('q') }}"
                    placeholder="Cari user (nama/email/role)..."
                    class="w-72 rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200/50"
                />
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Cari</button>
                @if (trim((string) ($q ?? request('q'))) !== '')
                    <a href="{{ route('users.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Reset</a>
                @endif
            </form>

            <a href="{{ route('users.create') }}" class="inline-flex items-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
                Tambah User
            </a>
        </div>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-emerald-50 text-emerald-900">
                <tr>
                    <th class="text-left font-medium px-4 py-3">Nama</th>
                    <th class="text-left font-medium px-4 py-3">Email</th>
                    <th class="text-left font-medium px-4 py-3">Role</th>
                    <th class="text-left font-medium px-4 py-3">Dibuat</th>
                    <th class="text-right font-medium px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-emerald-100">
                @forelse ($users as $u)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $u->name }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $u->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs border border-emerald-200 bg-emerald-50/40 text-emerald-900">
                                {{ $u->role?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $u->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('users.edit', $u) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-amber-200 bg-amber-50/40 text-amber-900 hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-200/60"
                                   title="Edit" aria-label="Edit">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus user ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-red-200 bg-red-50 text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-200/60 disabled:cursor-not-allowed disabled:opacity-60"
                                            title="Hapus" aria-label="Hapus" @disabled(auth()->id() === $u->id)>
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4h8v2" />
                                            <path d="M6 6l1 16h10l1-16" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-8 text-center text-gray-500" colspan="5">Belum ada user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</x-app-layout>
