<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Gudang Hub') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-amber-50 text-gray-900">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold leading-tight text-emerald-950">Gudang Hub</h1>
                <p class="text-sm text-emerald-800">Warehouse inventory management</p>
            </div>

            <div class="bg-white border border-emerald-100 rounded-lg p-6">
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md p-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md p-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md p-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-emerald-900/60">&copy; {{ date('Y') }} Gudang Hub</p>
        </div>
    </div>
</body>
</html>
