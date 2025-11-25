<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Data Guru & Staff</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola data pengguna sistem</p>
            </div>
            <a href="{{ route('users.create') }}" class="bg-[#1FAE59] hover:bg-green-600 text-white px-3 py-2 sm:px-4 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-colors duration-200 flex items-center justify-center sm:justify-start space-x-2">
                <i class="fas fa-plus text-xs sm:text-sm"></i>
                <span>Tambah User</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                <!-- Total Users -->
                <div class="bg-[#1FAE59] rounded-xl shadow-lg p-3 sm:p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-lg sm:text-xl font-bold">{{ $users->total() }}</p>
                            <p class="text-green-100 text-xs sm:text-sm">Total User</p>
                        </div>
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <i class="fas fa-users text-sm sm:text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- Guru -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-lg sm:text-xl font-bold text-green-600">
                                {{ $users->where('role', 'guru')->count() }}
                            </p>
                            <p class="text-gray-600 text-xs sm:text-sm">Guru</p>
                        </div>
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <i class="fas fa-chalkboard-teacher text-green-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- Tata Usaha -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-lg sm:text-xl font-bold text-blue-600">
                                {{ $users->where('role', 'tu')->count() }}
                            </p>
                            <p class="text-gray-600 text-xs sm:text-sm">Tata Usaha</p>
                        </div>
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <i class="fas fa-user-tie text-blue-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-lg sm:text-xl font-bold text-emerald-600">
                                {{ $users->count() }}
                            </p>
                            <p class="text-gray-600 text-xs sm:text-sm">Aktif</p>
                        </div>
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-2">
                            <i class="fas fa-user-check text-emerald-600 text-sm sm:text-base"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Mobile Card View -->
            <div class="block md:hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">Daftar Pengguna</h3>
                            <div class="text-xs text-gray-600">
                                {{ $users->total() }} user
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @forelse($users as $user)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</h4>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->role === 'guru' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200">
                                <a href="{{ route('users.edit', $user) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-gray-400">User Aktif</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <div class="text-gray-400 mb-3">
                                <i class="fas fa-users text-3xl"></i>
                            </div>
                            <p class="text-gray-500 text-base font-medium">Belum ada data user</p>
                            <p class="text-gray-400 text-xs mt-1">Mulai dengan menambahkan user baru</p>
                            <a href="{{ route('users.create') }}" class="inline-block mt-3 bg-[#1FAE59] hover:bg-green-600 text-white px-3 py-2 rounded-lg text-xs font-medium transition-colors duration-200">
                                Tambah User Pertama
                            </a>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination Mobile -->
                    @if($users->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col items-center gap-2">
                            <div class="text-xs text-gray-700">
                                Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }}
                            </div>
                            <div class="flex justify-center">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Pengguna</h3>
                            <div class="text-sm text-gray-600">
                                Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">
                                        Nama
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-xs font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->role === 'guru' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            <i class="fas {{ $user->role === 'guru' ? 'fa-chalkboard-teacher' : 'fa-user-tie' }} mr-1 text-xs"></i>
                                            {{ strtoupper($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('users.edit', $user) }}"
                                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-2 rounded-lg hover:bg-blue-50"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors duration-200 p-2 rounded-lg hover:bg-red-50"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-xs text-gray-400 px-2">User Aktif</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 mb-3">
                                            <i class="fas fa-users text-4xl"></i>
                                        </div>
                                        <p class="text-gray-500 text-lg font-medium">Belum ada data user</p>
                                        <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan user baru</p>
                                        <a href="{{ route('users.create') }}" class="inline-block mt-4 bg-[#1FAE59] hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                            Tambah User Pertama
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Desktop -->
                    @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} dari {{ $users->total() }} hasil
                            </div>
                            <div class="flex space-x-2">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
            justify-content: center;
            gap: 4px;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
            min-width: 36px;
            text-align: center;
        }

        .pagination li a {
            color: #6B7280;
            background: white;
            border: 1px solid #E5E7EB;
        }

        .pagination li a:hover {
            background: #F3F4F6;
            border-color: #D1D5DB;
        }

        .pagination li span {
            color: #374151;
            background: #F3F4F6;
            border: 1px solid #D1D5DB;
        }

        .pagination li.active span {
            background: #1FAE59;
            color: white;
            border-color: #1FAE59;
        }

        @media (max-width: 640px) {
            .pagination li a,
            .pagination li span {
                padding: 4px 8px;
                font-size: 11px;
                min-width: 32px;
            }
        }
    </style>
</x-app-layout>
