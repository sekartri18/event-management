@php
    $primaryColor = 'indigo'; 
    $accentColor = 'red'; 
@endphp

{{-- Fixed Sidebar (Minimalis, Putih/Abu-abu Muda) --}}
<aside class="fixed top-0 left-0 h-full w-64 bg-white text-gray-800 shadow-xl z-30 transition-transform duration-300 transform -translate-x-full sm:translate-x-0">
    
    {{-- Logo / Judul Sidebar --}}
    <div class="flex items-center justify-center h-16 border-b border-gray-200 bg-gray-50">
        <span class="text-2xl font-extrabold tracking-wider text-{{ $primaryColor }}-600">
            Admin<span class="text-gray-900">Panel</span>
        </span>
    </div>

    {{-- Navigasi Sidebar --}}
    <nav class="flex flex-col p-4 space-y-1">
        
        {{-- Dashboard Admin --}}
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
            class="group flex items-center w-full py-2 px-3 text-base font-medium rounded-lg transition duration-150 ease-in-out border-none 
                text-gray-700 hover:bg-gray-100 hover:text-{{ $primaryColor }}-600" 
            activeClass="bg-{{ $primaryColor }}-50 font-extrabold text-{{ $primaryColor }}-600 border-l-4 border-{{ $primaryColor }}-600">
            <span class="mr-3 text-lg">›</span> {{ __('Dashboard') }}
        </x-nav-link>

        {{-- Kelola Pengguna --}}
        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index') || request()->routeIs('admin.users.edit')"
            class="group flex items-center w-full py-2 px-3 text-base font-medium rounded-lg transition duration-150 ease-in-out border-none 
                text-gray-700 hover:bg-gray-100 hover:text-{{ $primaryColor }}-600"
            activeClass="bg-{{ $primaryColor }}-50 font-extrabold text-{{ $primaryColor }}-600 border-l-4 border-{{ $primaryColor }}-600">
            <span class="mr-3 text-lg">›</span> {{ __('Kelola Pengguna') }}
        </x-nav-link>

        {{-- Semua Event (Akses Admin) --}}
        {{-- Menggunakan rute events.index, tapi karena Admin, kita cek apakah bukan rute admin.dashboard --}}
        <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*') && !request()->routeIs('admin.dashboard')"
            class="group flex items-center w-full py-2 px-3 text-base font-medium rounded-lg transition duration-150 ease-in-out border-none 
                text-gray-700 hover:bg-gray-100 hover:text-{{ $primaryColor }}-600"
            activeClass="bg-{{ $primaryColor }}-50 font-extrabold text-{{ $primaryColor }}-600 border-l-4 border-{{ $primaryColor }}-600">
            <span class="mr-3 text-lg">›</span> {{ __('Semua Event') }}
        </x-nav-link>
        
        {{-- Divider --}}
        <div class="h-px bg-gray-200 my-2"></div>
        
        {{-- Link Profil --}}
        <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')"
            class="group flex items-center w-full py-2 px-3 text-base font-medium rounded-lg transition duration-150 ease-in-out border-none 
                text-gray-700 hover:bg-gray-100 hover:text-{{ $primaryColor }}-600"
            activeClass="bg-{{ $primaryColor }}-50 font-extrabold text-{{ $primaryColor }}-600 border-l-4 border-{{ $primaryColor }}-600">
            <span class="mr-3 text-lg">⚙</span> {{ __('Pengaturan Profil') }}
        </x-nav-link>

    </nav>
    
    {{-- Logout Link (Ditempatkan di bawah) --}}
    <div class="absolute bottom-0 w-full p-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full py-2 px-3 bg-{{ $accentColor }}-600 hover:bg-{{ $accentColor }}-700 rounded-lg text-white font-semibold transition duration-150 ease-in-out">
                <span class="mr-2">✕</span> {{ __('Logout') }}
            </button>
        </form>
    </div>
</aside>