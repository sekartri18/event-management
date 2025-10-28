@props(['header'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans antialiased bg-gray-50">
        {{-- AlpineJS x-data untuk mengelola status sidebar (untuk tampilan mobile) --}}
        <div x-data="{ sidebarOpen: window.innerWidth >= 640 }" class="min-h-screen flex"> 
            
            {{-- =================================================== --}}
            {{-- 1. Sidebar (Fixed Left Navigation) --}}
            {{-- =================================================== --}}
            @include('layouts.admin-sidebar') 

            {{-- =================================================== --}}
            {{-- 2. Main Content Area (Header + Body) --}}
            {{-- sm:ml-64 akan mendorong konten ke kanan selebar sidebar --}}
            {{-- =================================================== --}}
            <div :class="{ 'sm:ml-64': sidebarOpen, 'sm:ml-0': !sidebarOpen }" class="flex-1 flex flex-col transition-all duration-300"> 
                
                {{-- A. Top Navigation Bar (Profile Dropdown & Mobile Toggle) --}}
                <header class="sticky top-0 bg-white border-b border-gray-100 shadow-sm z-20">
                    <div class="flex justify-between h-16 px-4 sm:px-6 lg:px-8">
                        
                        {{-- Mobile Menu Toggle Button (Sembunyi di layar besar) --}}
                        <div class="flex items-center">
                            <button @click="sidebarOpen = ! sidebarOpen" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': sidebarOpen, 'inline-flex': ! sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! sidebarOpen, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            {{-- Page Heading (Untuk tampilan mobile di header atas) --}}
                            @isset($header)
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight ml-4 hidden sm:block">
                                    {{ $header }}
                                </h2>
                            @endisset
                        </div>

                        {{-- Settings Dropdown User Profile --}}
                        <div class="flex items-center ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-700 bg-red-100 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150 shadow-inner border-red-300">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1 text-red-600">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Role: {{ Auth::user()->role->display_name ?? 'Guest' }}
                                    </div>
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profil Pengguna') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();"
                                                class="text-red-600 hover:bg-red-50">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                {{-- B. Page Content (Slot Content) --}}
                <main class="flex-1 p-4 sm:p-0">
                    {{ $slot }}
                </main>
            </div>
            
            {{-- Mobile Overlay untuk menutup sidebar saat di klik --}}
            <div x-show="sidebarOpen && window.innerWidth < 640" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black opacity-50 sm:hidden"></div>
        </div>
    </body>
</html>