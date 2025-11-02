<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                        Event<span class="text-purple-500">Pro</span>
                    </a>
                </div>

                {{-- NAVIGASI DESKTOP --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @php
                            $user = Auth::user();
                        @endphp

                        {{-- LINK KHUSUS ADMIN (Akses Tertinggi) --}}
                        @if($user->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="font-bold text-red-700 hover:text-red-600 hover:border-red-400 focus:border-red-700">
                                {{ __('Admin Panel') }}
                            </x-nav-link>
                            {{-- Admin juga bisa mengakses Semua Event --}}
                            <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.index') && !request()->routeIs('events.create')" class="text-gray-700 hover:text-indigo-600 hover:border-indigo-400 focus:border-indigo-700">
                                {{ __('Semua Event') }}
                            </x-nav-link>
                        @endif

                        {{-- LINK KHUSUS ORGANIZER (Bukan Admin) --}}
                        @if($user->isOrganizer() && !$user->isAdmin())
                            {{-- FOKUS UTAMA ORGANIZER: Manajemen Event --}}
                            <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.index')" class="font-bold text-indigo-700 hover:text-indigo-600 hover:border-indigo-400 focus:border-indigo-700">
                                {{ __('Manajemen Event') }}
                            </x-nav-link>
                            
                            {{-- TAMBAHAN: Link Langsung Buat Event (Aksi Utama Organizer) --}}
                            @can('create_event')
                                <x-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')" class="text-green-600 hover:text-green-700 hover:border-green-400 focus:border-green-700 font-semibold">
                                    {{ __('+ Buat Event') }}
                                </x-nav-link>
                            @endcan
                        @endif
                        
                        {{-- LINK KHUSUS ATTENDEE (Bukan Admin/Organizer) --}}
                        @if($user->isAttendee() && !$user->isOrganizer() && !$user->isAdmin())
                            {{-- FOKUS UTAMA ATTENDEE: Dashboard Umum --}}
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold text-blue-700 hover:text-blue-600 hover:border-blue-400 focus:border-blue-700">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.index')" class="text-gray-700 hover:text-indigo-600 hover:border-indigo-400 focus:border-indigo-700">
                                {{ __('Cari Event') }}
                            </x-nav-link>
                            
                            {{-- BARU: LINK TIKET SAYA UNTUK ATTENDEE --}}
                            <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')" class="text-gray-700 hover:text-indigo-700 hover:border-indigo-400 focus:border-indigo-700 font-semibold">
                                {{ __('Tiket Saya') }}
                            </x-nav-link>
                            
                        @endif
                    @else
                        {{-- Tampilan Default untuk Guest (Belum Login) --}}
                        <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                            {{ __('Beranda') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-700 bg-gray-100 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150 shadow-inner">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Role: {{ ucfirst(Auth::user()->role->display_name ?? 'Guest') }}
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

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- NAVIGASI RESPONSIVE (MOBILE) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @php
                    $user = Auth::user();
                @endphp
                
                {{-- LINK KHUSUS ADMIN (Mobile) --}}
                @if($user->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-red-600 font-bold">
                        {{ __('Admin Panel') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.index')">
                        {{ __('Semua Event') }}
                    </x-responsive-nav-link>
                @endif

                {{-- LINK KHUSUS ORGANIZER (Mobile) --}}
                @if($user->isOrganizer() && !$user->isAdmin())
                    {{-- FOKUS UTAMA ORGANIZER: Manajemen Event --}}
                    <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.index')" class="text-indigo-600 font-bold">
                        {{ __('Manajemen Event') }}
                    </x-responsive-nav-link>
                    
                    {{-- TAMBAHAN: Link Langsung Buat Event --}}
                    @can('create_event')
                        <x-responsive-nav-link :href="route('events.create')" :active="request()->routeIs('events.create')" class="text-green-600 font-semibold">
                            {{ __('+ Buat Event Baru') }}
                        </x-responsive-nav-link>
                    @endcan
                @endif
                
                {{-- LINK KHUSUS ATTENDEE (Mobile) --}}
                @if($user->isAttendee() && !$user->isOrganizer() && !$user->isAdmin())
                    {{-- FOKUS UTAMA ATTENDEE: Dashboard Umum --}}
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-blue-600 font-bold">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.index')">
                        {{ __('Cari Event') }}
                    </x-responsive-nav-link>
                    
                    {{-- BARU: LINK TIKET SAYA UNTUK ATTENDEE (MOBILE) --}}
                    <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')" class="text-indigo-600 font-semibold">
                        {{ __('Tiket Saya') }}
                    </x-responsive-nav-link>
                    
                @endif

            @else
                {{-- Tampilan Default untuk Guest (Belum Login) --}}
                <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                    {{ __('Beranda') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil Pengguna') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600 hover:bg-red-50">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>