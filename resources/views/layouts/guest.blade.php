<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EventPro') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-12 sm:pt-0 bg-gray-100">
            
            {{-- LOGO AREA --}}
            <div class="mb-8 text-center">
                <a href="/">
                    <x-application-logo/> 
                </a>
            </div>

            {{-- FORM CONTAINER --}}
            <div class="w-full sm:max-w-md px-10 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border-l-8 border-indigo-600">
                {{ $slot }}
            </div>
            
            {{-- TAULAN REGISTER  --}}
            @if (Route::has('register') && !request()->routeIs('register'))
                <div class="mt-6 text-center text-sm">
                    <p class="text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 underline transition duration-150 ease-in-out">
                            Daftar Sekarang
                        </a>
                    </p>
                </div>
            @endif
            
        </div>
        
    </body>
</html>