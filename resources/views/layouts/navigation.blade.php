<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 0.375rem;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #374151;
        }

        .dropdown-content a:hover {
            background-color: #f9fafb;
        }

        .dropdown-content form {
            margin: 0;
        }

        .dropdown-content button {
            width: 100%;
            text-align: left;
            padding: 0.5rem 1rem;
            border: none;
            background: none;
            cursor: pointer;
            color: #374151;
        }

        .dropdown-content button:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            @if(auth()->user()->role === 'tu')
                                <!-- Menu untuk TU -->
                                <x-nav-link :href="route('siswa.index')" :active="request()->routeIs('siswa.*')">
                                    {{ __('Data Siswa') }}
                                </x-nav-link>
                                <x-nav-link :href="route('kelas.index')" :active="request()->routeIs('kelas.*')">
                                    {{ __('Data Kelas') }}
                                </x-nav-link>
                                <x-nav-link :href="route('absensi.index')" :active="request()->routeIs('absensi.*')">
                                    {{ __('Absensi') }}
                                </x-nav-link>
                            @else
                                <!-- Menu untuk Kepsek -->
                                <x-nav-link :href="route('siswa.index')" :active="request()->routeIs('siswa.index')">
                                    {{ __('Data Siswa') }}
                                </x-nav-link>
                                <x-nav-link :href="route('absensi.index')" :active="request()->routeIs('absensi.index')">
                                    {{ __('Absensi') }}
                                </x-nav-link>
                            @endif
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <div class="dropdown relative">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>

                            <div class="dropdown-content">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Profile') }}
                                </a>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Hamburger Menu for Mobile -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="sm:hidden hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    @if(auth()->user()->role === 'tu')
                        <x-responsive-nav-link :href="route('siswa.index')" :active="request()->routeIs('siswa.*')">
                            {{ __('Data Siswa') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('kelas.index')" :active="request()->routeIs('kelas.*')">
                            {{ __('Data Kelas') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('absensi.index')" :active="request()->routeIs('absensi.*')">
                            {{ __('Absensi') }}
                        </x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link :href="route('siswa.index')" :active="request()->routeIs('siswa.index')">
                            {{ __('Data Siswa') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('absensi.index')" :active="request()->routeIs('absensi.index')">
                            {{ __('Absensi') }}
                        </x-responsive-nav-link>
                    @endif
                </div>

                <!-- Mobile Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        <div class="font-medium text-sm text-gray-500 capitalize">{{ Auth::user()->role }}</div>
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
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- JavaScript for Mobile Menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');

                    // Toggle hamburger icon
                    const icon = mobileMenuButton.querySelector('svg path');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                    } else {
                        icon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                    }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (mobileMenu && !mobileMenu.contains(event.target) &&
                    mobileMenuButton && !mobileMenuButton.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    const icon = mobileMenuButton.querySelector('svg path');
                    icon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
