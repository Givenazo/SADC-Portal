@php
    $user = Auth::check() ? Auth::user() : null;
    $userRole = $user?->role?->name ?? null;
    $userCountry = $user?->country?->name ?? null;
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                @if ($user)
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        @if($userRole === 'Admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Admin Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('User Management') }}
                            </x-nav-link>
                            <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.index')">
                                {{ __('Breaking News') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.videos.index')" :active="request()->routeIs('admin.videos.*')">
                                {{ __('Uploaded Videos') }}
                            </x-nav-link>
                            <x-nav-link :href="route('contact.info')" :active="request()->routeIs('contact.info')">
                                {{ __('Contact Information') }}
                            </x-nav-link>
                        @endif
                        @if($userRole !== 'Admin')
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Member Dashboard') }}
                            </x-nav-link>

                            <x-nav-link :href="route('videos.create')" :active="request()->routeIs('videos.create')">
                                {{ __('Upload a Video') }}
                            </x-nav-link>

                            <x-nav-link :href="route('videos.index')" :active="request()->routeIs('videos.index')">
                                {{ __('My Uploads') }}
                            </x-nav-link>

                            <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 cursor-not-allowed">
                                {{ __('Add News') }}
                            </span>
                            <x-nav-link :href="route('contact.info')" :active="request()->routeIs('contact.info')">
                                {{ __('Contact Information') }}
                            </x-nav-link>
                        @endif
                    </div>
                @endif
            </div>

            @if ($user)
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <div class="flex items-center">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 relative">
                                    <div>{{ $user->name }}</div>
                                    @if($userCountry)
                                        <div class="ml-1">({{ $userCountry }})</div>
                                    @endif
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    @php
                                        $country = $user->country;
                                        $countryName = $country?->name ?? null;
                                        $countryIsoCodes = [
                                            'Angola' => 'ao',
                                            'Botswana' => 'bw',
                                            'Comoros' => 'km',
                                            'DRCCongo' => 'cd',
                                            'Eswatini' => 'sz',
                                            'Lesotho' => 'ls',
                                            'Madagascar' => 'mg',
                                            'Malawi' => 'mw',
                                            'Mauritius' => 'mu',
                                            'Mozambique' => 'mz',
                                            'Namibia' => 'na',
                                            'Seychelles' => 'sc',
                                            'SouthAfrica' => 'za',
                                            'Tanzania' => 'tz',
                                            'Zambia' => 'zm',
                                            'Zimbabwe' => 'zw',
                                        ];
                                        $iso = $countryName ? ($countryIsoCodes[$countryName] ?? null) : null;
                                    @endphp
                                    @if($iso)
                                        <span class="ms-3 flex items-center justify-center h-7 w-7 bg-white border border-gray-300 rounded-md shadow-sm" style="min-width:1.75rem; min-height:1.75rem;">
                                            <img src="https://flagcdn.com/20x15/{{ $iso }}.png" alt="{{ $countryName }} Flag" class="h-5 w-5 object-contain" />
                                        </span>
                                    @else
                                        <span class="ms-3 flex items-center justify-center h-7 w-7 bg-gray-100 border border-gray-300 rounded-md shadow-sm" style="min-width:1.75rem; min-height:1.75rem;">
                                            <span class="text-gray-400 text-xs">--</span>
                                        </span>
                                    @endif
                                </button>
                            </div>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    @if ($user)
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                @if($userRole === 'Admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Admin Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('User Management') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('news.index')" :active="request()->routeIs('news.index')">
                        {{ __('Manage News') }}
                    </x-responsive-nav-link>
                @endif
                @if($userRole !== 'Admin')
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Member Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('videos.create')" :active="request()->routeIs('videos.create')">
                        {{ __('Upload Video') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('news.create')" :active="request()->routeIs('news.create')">
                        {{ __('Add News') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('videos.index')" :active="request()->routeIs('videos.index')">
                        {{ __('My Uploads') }}
                    </x-responsive-nav-link>
                @endif
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    @endif
</nav>
