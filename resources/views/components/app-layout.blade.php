<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EventPro') }} - Event Management</title>

        <!-- Fonts: Menggunakan Inter untuk tampilan modern -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        @php
            $user = auth()->user();
            $isAdmin = $user && $user->isAdmin();
        @endphp

        @if($isAdmin)
            <!-- ADMIN LAYOUT WITH SIDEBAR -->
            <div class="min-h-screen flex">
                @include('layouts.admin-sidebar')
                
                <div class="flex-1 sm:ml-64">
                    @include('layouts.navigation')

                    @if(isset($header))
                        <header class="bg-white shadow-sm border-b border-gray-200">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @else
            <!-- NON-ADMIN LAYOUT (NO SIDEBAR) -->
            <div class="min-h-screen w-full">
                @include('layouts.navigation')

                @if(isset($header))
                    <header class="bg-white shadow-sm border-b border-gray-200">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>
        @endif
    </body>
</html>
