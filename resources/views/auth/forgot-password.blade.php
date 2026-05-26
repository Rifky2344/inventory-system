<x-auth-layout>
    <h2 class="text-lg font-semibold mb-2">Forgot Password</h2>
    <p class="text-sm text-gray-600 mb-4">Masukkan email, kami akan kirim link reset password.</p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                class="w-full rounded-md border border-gray-300 focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" />
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full inline-flex justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
            Send Reset Link
        </button>

        <a class="block text-center text-sm text-gray-600 hover:underline" href="{{ route('login') }}">Kembali ke login</a>
    </form>
</x-auth-layout>
