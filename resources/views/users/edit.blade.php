<x-app-layout>
    <div class="mb-4">
        <h1 class="text-xl font-semibold">Edit User</h1>
        <p class="text-sm text-gray-600">Ubah data user, role, atau reset password</p>
    </div>

    <div class="bg-white border border-emerald-100 rounded-lg p-6 shadow-sm">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" for="name">Nama</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="role_id">Role</label>
                    <select id="role_id" name="role_id" required class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div></div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="password">Password (opsional)</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" class="w-full rounded-md border border-gray-300 pr-20 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                        <button type="button"
                            class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-9 w-9 items-center justify-center rounded-md border border-emerald-200 bg-emerald-50/40 text-emerald-900 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200/60"
                            data-toggle-password
                            data-target="password"
                            data-show-text="Lihat"
                            data-hide-text="Sembunyi"
                            aria-label="Lihat"
                            title="Lihat">
                            <svg class="h-4 w-4" data-icon-show viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg class="h-4 w-4 hidden" data-icon-hide viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 4l16 16" />
                            </svg>
                            <span class="sr-only" data-toggle-label>Lihat</span>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengganti password.</p>
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="password_confirmation">Konfirmasi Password</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-md border border-gray-300 pr-20 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                        <button type="button"
                            class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-9 w-9 items-center justify-center rounded-md border border-emerald-200 bg-emerald-50/40 text-emerald-900 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200/60"
                            data-toggle-password
                            data-target="password_confirmation"
                            data-show-text="Lihat"
                            data-hide-text="Sembunyi"
                            aria-label="Lihat"
                            title="Lihat">
                            <svg class="h-4 w-4" data-icon-show viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg class="h-4 w-4 hidden" data-icon-hide viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 4l16 16" />
                            </svg>
                            <span class="sr-only" data-toggle-label>Lihat</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('users.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm">Batal</a>
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
