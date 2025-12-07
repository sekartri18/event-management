<header x-data="{ open: false }" class="sticky top-0 bg-white border-b border-gray-200 shadow-sm z-20 w-full sm:w-[calc(100%-16rem)] sm:ml-64">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- Kiri: Judul Halaman / Mobile Toggle --}}
            <div class="flex items-center">
                <button @click="$dispatch('toggle-sidebar')" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                {{-- Judul Halaman di Desktop --}}
                @hasSection('header')
                    <div class="hidden sm:block">
                        @yield('header')
                    </div>
                @endhasSection
            </div>

            {{-- Kanan: Dropdown User Profile --}}
            <div class="flex items-center ms-6">
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
        </div>
    </div>
</header>