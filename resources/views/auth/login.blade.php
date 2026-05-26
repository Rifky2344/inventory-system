<x-auth-layout>
    <h2 class="text-lg font-semibold mb-4">Login</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
        </div>

        <div>
            <label class="block text-sm font-medium mb-1" for="password">Password</label>
            <div class="relative">
                <input id="password" name="password" type="password" required
                    class="w-full rounded-md border border-gray-300 pr-12 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
                <button type="button"
                    class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-8 w-8 items-center justify-center"
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
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="remember" value="1" class="rounded border-gray-300" />
                Remember me
            </label>

            <a class="text-sm text-emerald-800 hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="w-full inline-flex justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
            Sign in
        </button>
    </form>
</x-auth-layout>
