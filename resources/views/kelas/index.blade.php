<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Kelas</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola semua kelas dan informasi siswa</p>
            </div>
            <a href="{{ route('kelas.create') }}"
               class="bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                <i class="fas fa-plus text-sm"></i>
                <span>Tambah Kelas</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Kelas -->
                <div class="bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold">{{ $kelas->total() }}</p>
                            <p class="text-green-100 text-sm">Total Kelas</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-door-open text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Kelas X -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $kelas->where('tingkat', '10')->count() }}</p>
                            <p class="text-gray-600 text-sm">Kelas X</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-1 text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Kelas XI -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-emerald-600">{{ $kelas->where('tingkat', '11')->count() }}</p>
                            <p class="text-gray-600 text-sm">Kelas XI</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-2 text-emerald-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Kelas XII -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-teal-600">{{ $kelas->where('tingkat', '12')->count() }}</p>
                            <p class="text-gray-600 text-sm">Kelas XII</p>
                        </div>
                        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-3 text-teal-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classes Grid -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kelas</h3>
                        <div class="text-sm text-gray-600">
                            Total {{ $kelas->total() }} kelas
                        </div>
                    </div>
                </div>

                <!-- Classes Cards for Mobile / Grid for Desktop -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($kelas as $item)
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2 group">
                            <!-- Class Header -->
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <span class="text-white font-bold text-lg">{{ $item->tingkat }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-users mr-1 text-xs"></i>
                                            {{ $item->siswas_count }}
                                        </span>
                                    </div>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $item->nama_kelas }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->jurusan }}</p>
                            </div>

                            <!-- Class Info -->
                            <div class="p-6">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600 flex items-center">
                                            <i class="fas fa-graduation-cap text-gray-400 mr-2"></i>
                                            Tingkat
                                        </span>
                                        <span class="font-medium text-gray-900">Kelas {{ $item->tingkat }}</span>
                                    </div>

                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600 flex items-center">
                                            <i class="fas fa-book text-gray-400 mr-2"></i>
                                            Jurusan
                                        </span>
                                        <span class="font-medium text-gray-900">{{ $item->jurusan }}</span>
                                    </div>

                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600 flex items-center">
                                            <i class="fas fa-user-graduate text-gray-400 mr-2"></i>
                                            Siswa
                                        </span>
                                        <span class="font-medium text-gray-900">{{ $item->siswas_count }} orang</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                                    <a href="{{ route('kelas.show', $item) }}"
                                       class="text-green-600 hover:text-green-800 transition-colors duration-200 flex items-center text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </a>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('kelas.edit', $item) }}"
                                           class="text-emerald-600 hover:text-emerald-800 transition-colors duration-200 p-1 rounded-lg hover:bg-emerald-50"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('kelas.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200 p-1 rounded-lg hover:bg-red-50"
                                                    title="Hapus">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="md:col-span-2 lg:col-span-3 xl:col-span-4">
                            <div class="text-center py-12">
                                <div class="text-gray-400 mb-4">
                                    <i class="fas fa-door-open text-6xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Kelas</h4>
                                <p class="text-gray-600 mb-6">Mulai dengan membuat kelas pertama Anda</p>
                                <a href="{{ route('kelas.create') }}"
                                   class="bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl inline-flex items-center space-x-2">
                                    <i class="fas fa-plus"></i>
                                    <span>Tambah Kelas Pertama</span>
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($kelas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $kelas->firstItem() }} hingga {{ $kelas->lastItem() }} dari {{ $kelas->total() }} kelas
                        </div>
                        <div class="flex space-x-2">
                            {{ $kelas->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-bar text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Rata-rata Siswa per Kelas</p>
                            <p class="text-lg font-bold text-gray-900">
                                @php
                                    $totalSiswa = $kelas->sum('siswas_count');
                                    $avgSiswa = $kelas->count() > 0 ? round($totalSiswa / $kelas->count(), 1) : 0;
                                @endphp
                                {{ $avgSiswa }} siswa
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Seluruh Siswa</p>
                            <p class="text-lg font-bold text-gray-900">{{ $totalSiswa }} siswa</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-star text-teal-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kelas Terbanyak</p>
                            <p class="text-lg font-bold text-gray-900">
                                @php
                                    $mostStudents = $kelas->sortByDesc('siswas_count')->first();
                                @endphp
                                {{ $mostStudents ? $mostStudents->nama_kelas : '-' }}
                            </p>
                        </div>
                    </div>
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
            gap: 4px;
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

        .class-card {
            transition: all 0.3s ease;
        }

        .class-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</x-app-layout>
