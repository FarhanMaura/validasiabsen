<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Siswa</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data siswa dengan mudah dan efisien</p>
            </div>
            @if(auth()->user()->role === 'tu')
            <div class="flex space-x-3">
                <a href="{{ route('siswa.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <i class="fas fa-user-plus text-sm"></i>
                    <span>Tambah Siswa</span>
                </a>
                <a href="{{ route('siswa.export.template') }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <i class="fas fa-file-export text-sm"></i>
                    <span>Template Import</span>
                </a>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto">
            <!-- Search and Stats Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                <!-- Search Card -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <form action="{{ route('siswa.index') }}" method="GET" class="flex gap-4 items-end">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Cari berdasarkan NISN, Nama, atau Kelas..."
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                </div>
                            </div>
                            <button type="submit" class="bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                                Cari
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-2xl shadow-lg p-6 text-white">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <p class="text-2xl font-bold">{{ $siswas->total() }}</p>
                        <p class="text-sm text-green-100">Total Siswa</p>
                    </div>
                </div>
            </div>

            <!-- Import Section untuk TU -->
            @if(auth()->user()->role === 'tu')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Import Data Siswa</h3>
                        <p class="text-sm text-gray-600">Upload file Excel untuk import data siswa sekaligus</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-import text-blue-600"></i>
                    </div>
                </div>
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-400 transition-colors duration-200">
                            <input type="file" name="file" accept=".xlsx,.xls,.csv"
                                   class="hidden" id="fileInput" required>
                            <label for="fileInput" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Klik untuk upload file</p>
                                <p class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, .csv</p>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                        Import Data
                    </button>
                </form>
            </div>
            @endif

            <!-- Table Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Siswa</h3>
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $siswas->firstItem() ?? 0 }}-{{ $siswas->lastItem() ?? 0 }} dari {{ $siswas->total() }} siswa
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <span>NISN</span>
                                        <i class="fas fa-sort text-gray-400 text-xs"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-1">
                                        <span>Nama Siswa</span>
                                        <i class="fas fa-sort text-gray-400 text-xs"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Telepon Ortu</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">RFID UID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($siswas as $siswa)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $siswa->nisn }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-bold text-white">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</div>
                                            <div class="text-xs text-gray-500">{{ $siswa->alamat }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-door-open mr-1"></i>
                                        {{ $siswa->kelas->nama_lengkap }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-gray-400 mr-2 text-xs"></i>
                                        {{ $siswa->no_telepon_ortu }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($siswa->rfid_uid)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-id-card mr-1"></i>
                                        {{ $siswa->rfid_uid }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $siswa->status_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        {{ $siswa->status_aktif_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('siswa.show', $siswa) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-2 rounded-lg hover:bg-blue-50"
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->role === 'tu')
                                        <a href="{{ route('siswa.edit', $siswa) }}"
                                           class="text-green-600 hover:text-green-900 transition-colors duration-200 p-2 rounded-lg hover:bg-green-50"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 transition-colors duration-200 p-2 rounded-lg hover:bg-red-50"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 mb-3">
                                        <i class="fas fa-users text-4xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">Tidak ada data siswa</p>
                                    <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan siswa baru</p>
                                    @if(auth()->user()->role === 'tu')
                                    <a href="{{ route('siswa.create') }}" class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        Tambah Siswa Pertama
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($siswas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $siswas->firstItem() }} hingga {{ $siswas->lastItem() }} dari {{ $siswas->total() }} hasil
                        </div>
                        <div class="flex space-x-2">
                            {{ $siswas->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
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
    </style>
</x-app-layout>
