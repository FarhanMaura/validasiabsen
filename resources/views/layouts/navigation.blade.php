<nav class="bg-white lg:hidden border-b border-gray-200">
    <div class="flex justify-between items-center h-16 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <img src="{{ asset ('logo.png') }}" class=""/>
            <span class="text-lg font-bold text-gray-900">{{ config('app.name', 'Laravel') }}</span>
        </a>

        <button id="mobile-menu-button" class="p-2 rounded-md text-gray-600 hover:text-[#1FAE59] hover:bg-gray-100 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div id="mobile-menu" class="hidden border-t border-gray-200 bg-white shadow-lg">
        <div class="px-2 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-gray-900 hover:bg-green-50 hover:text-[#1FAE59] transition-colors {{ request()->routeIs('dashboard') ? 'bg-green-50 text-[#1FAE59] font-medium' : '' }}">
                <i class="fas fa-chart-line w-5 mr-3"></i>Dashboard
            </a>

            @if(auth()->user()->role === 'tu')
                <a href="{{ route('siswa.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-900 hover:bg-green-50 hover:text-[#1FAE59] transition-colors {{ request()->routeIs('siswa.*') ? 'bg-green-50 text-[#1FAE59] font-medium' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>Data Siswa
                </a>
                <a href="{{ route('kelas.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-900 hover:bg-green-50 hover:text-[#1FAE59] transition-colors {{ request()->routeIs('kelas.*') ? 'bg-green-50 text-[#1FAE59] font-medium' : '' }}">
                    <i class="fas fa-door-open w-5 mr-3"></i>Data Kelas
                </a>
                <a href="{{ route('absensi.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-900 hover:bg-green-50 hover:text-[#1FAE59] transition-colors {{ request()->routeIs('absensi.*') ? 'bg-green-50 text-[#1FAE59] font-medium' : '' }}">
                    <i class="fas fa-clipboard-check w-5 mr-3"></i>Absensi
                </a>
            @else
                <a href="{{ route('siswa.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-900 hover:bg-green-50 hover:text-[#1FAE59] transition-colors {{ request()->routeIs('siswa.index') ? 'bg-green-50 text-[#1FAE59] font-medium' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>Data Siswa
                </a>
                <a href="{{ route('absensi.index') }}" class="flex items-center px-3 py-2 rounded-md text-gray-900 hover:bg-green-50 hover:text-[#1FAE59] transition-colors {{ request()->routeIs('absensi.index') ? 'bg-green-50 text-[#1FAE59] font-medium' : '' }}">
                    <i class="fas fa-clipboard-check w-5 mr-3"></i>Absensi
                </a>
            @endif
        </div>

        <div class="border-t border-gray-200 px-2 py-3">
            <div class="px-3 py-2">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
            <div class="mt-2 space-y-1">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-green-50 hover:text-[#1FAE59] transition-colors">
                    <i class="fas fa-user w-5 mr-3"></i>Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-green-50 hover:text-[#1FAE59] transition-colors text-left">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="flex min-h-screen bg-gray-50">
    <div class="hidden lg:flex lg:w-64 lg:flex-col bg-[#1FAE59] text-white shadow-xl">
        <div class="flex items-center h-16 px-4 border-b border-green-600">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <img src=" {{ asset ('logo.png') }}" class="h-8 w-auto text-white" />
                <span class="text-lg font-bold">{{ config('app.name', 'Laravel') }}</span>
            </a>
        </div>

        <div class="p-4 border-b border-green-600">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md">
                    <span class="text-[#1FAE59] font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                </div>
                <div>
                    <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-green-100 capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-3 rounded-lg hover:bg-green-600 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-green-600 shadow-md' : '' }}">
                <i class="fas fa-chart-line w-5 mr-3"></i>Dashboard
            </a>

            @if(auth()->user()->role === 'tu')
                <a href="{{ route('siswa.index') }}" class="flex items-center px-3 py-3 rounded-lg hover:bg-green-600 transition-all duration-200 {{ request()->routeIs('siswa.*') ? 'bg-green-600 shadow-md' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>Data Siswa
                </a>
                <a href="{{ route('kelas.index') }}" class="flex items-center px-3 py-3 rounded-lg hover:bg-green-600 transition-all duration-200 {{ request()->routeIs('kelas.*') ? 'bg-green-600 shadow-md' : '' }}">
                    <i class="fas fa-door-open w-5 mr-3"></i>Data Kelas
                </a>
                <a href="{{ route('absensi.index') }}" class="flex items-center px-3 py-3 rounded-lg hover:bg-green-600 transition-all duration-200 {{ request()->routeIs('absensi.*') ? 'bg-green-600 shadow-md' : '' }}">
                    <i class="fas fa-clipboard-check w-5 mr-3"></i>Absensi
                </a>
            @else
                <a href="{{ route('siswa.index') }}" class="flex items-center px-3 py-3 rounded-lg hover:bg-green-600 transition-all duration-200 {{ request()->routeIs('siswa.index') ? 'bg-green-600 shadow-md' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>Data Siswa
                </a>
                <a href="{{ route('absensi.index') }}" class="flex items-center px-3 py-3 rounded-lg hover:bg-green-600 transition-all duration-200 {{ request()->routeIs('absensi.index') ? 'bg-green-600 shadow-md' : '' }}">
                    <i class="fas fa-clipboard-check w-5 mr-3"></i>Absensi
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-green-600 space-y-1">
            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-600 transition-all duration-200">
                <i class="fas fa-user w-5 mr-3"></i>Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg hover:bg-green-600 transition-all duration-200 text-left">
                    <i class="fas fa-sign-out-alt w-5 mr-3"></i>Log Out
                </button>
            </form>
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        <main class="flex-1 p-4 bg-white">
            @if (isset($header))
                <div class="mb-4">
                    <h1 class="text-xl font-bold text-gray-900">{{ $header }}</h1>
                </div>
            @endif

            <div class="bg-white">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });

            mobileMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>
