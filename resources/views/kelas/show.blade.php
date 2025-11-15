<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Detail Kelas</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap kelas {{ $kelas->nama_lengkap }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('kelas.edit', $kelas) }}"
                   class="bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <i class="fas fa-edit text-sm"></i>
                    <span>Edit Kelas</span>
                </a>
                <a href="{{ route('kelas.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Class Info Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#1FAE59] to-green-500 px-6 py-6 text-white">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">{{ $kelas->tingkat }}</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold">{{ $kelas->nama_kelas }}</h3>
                                    <p class="text-green-100 text-lg">{{ $kelas->nama_lengkap }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Informasi Kelas -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2 flex items-center">
                                        <i class="fas fa-info-circle text-green-500 mr-2"></i>
                                        Informasi Kelas
                                    </h4>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Nama Kelas</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $kelas->nama_kelas }}</span>
                                        </div>

                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Tingkat</span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-graduation-cap mr-1"></i>
                                                Kelas {{ $kelas->tingkat }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Jurusan</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $kelas->jurusan }}</span>
                                        </div>

                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Nama Lengkap</span>
                                            <span class="text-sm font-semibold text-green-600">{{ $kelas->nama_lengkap }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistik -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2 flex items-center">
                                        <i class="fas fa-chart-bar text-emerald-500 mr-2"></i>
                                        Statistik
                                    </h4>

                                    <div class="space-y-3">
                                        <div class="text-center p-4 bg-green-50 rounded-xl">
                                            <p class="text-3xl font-bold text-green-600">{{ $kelas->siswas->count() }}</p>
                                            <p class="text-sm text-green-800">Total Siswa</p>
                                        </div>

                                        <div class="text-center p-4 bg-emerald-50 rounded-xl">
                                            <p class="text-2xl font-bold text-emerald-600">{{ $kelas->siswas->where('status_aktif', true)->count() }}</p>
                                            <p class="text-sm text-emerald-800">Siswa Aktif</p>
                                        </div>

                                        <div class="text-center p-4 bg-teal-50 rounded-xl">
                                            <p class="text-2xl font-bold text-teal-600">{{ $kelas->siswas->where('status_aktif', false)->count() }}</p>
                                            <p class="text-sm text-teal-800">Siswa Tidak Aktif</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Siswa -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-users text-green-500 mr-2"></i>
                                    Daftar Siswa
                                </h4>
                                <span class="text-sm text-gray-600">{{ $kelas->siswas->count() }} siswa</span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($kelas->siswas->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NISN</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Siswa</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($kelas->siswas as $siswa)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $siswa->nisn }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-3">
                                                        <span class="text-xs font-bold text-white">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</span>
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $siswa->status_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                                    {{ $siswa->status_aktif_text }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <a href="{{ route('siswa.show', $siswa) }}"
                                                   class="text-green-600 hover:text-green-800 transition-colors duration-200 flex items-center text-sm font-medium">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 mb-3">
                                    <i class="fas fa-user-graduate text-4xl"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">Belum ada siswa</p>
                                <p class="text-gray-400 text-sm mt-1">Tidak ada siswa yang terdaftar di kelas ini</p>
                                <a href="{{ route('siswa.create') }}"
                                   class="inline-block mt-4 bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5">
                                    Tambah Siswa
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bolt text-green-500 mr-2"></i>
                            Quick Actions
                        </h4>
                        <div class="space-y-3">
                            <a href="{{ route('kelas.edit', $kelas) }}"
                               class="w-full flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-xl transition-colors duration-200 group">
                                <div class="w-8 h-8 bg-[#1FAE59] rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-edit text-white text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Edit Kelas</span>
                            </a>

                            <a href="{{ route('siswa.create') }}?kelas_id={{ $kelas->id }}"
                               class="w-full flex items-center p-3 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors duration-200 group">
                                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-plus text-white text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Tambah Siswa</span>
                            </a>

                            <form action="{{ route('kelas.destroy', $kelas) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full flex items-center p-3 bg-red-50 hover:bg-red-100 rounded-xl transition-colors duration-200 group">
                                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-trash text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">Hapus Kelas</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-2xl shadow-lg p-6 text-white">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <h5 class="font-semibold mb-2">Informasi</h5>
                            <p class="text-green-100 text-sm">Kelas dibuat: {{ $kelas->created_at->diffForHumans() }}</p>
                            <p class="text-green-100 text-sm mt-1">Terakhir diupdate: {{ $kelas->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .student-row {
            transition: all 0.3s ease;
        }

        .student-row:hover {
            transform: translateX(4px);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: scale(1.05);
        }
    </style>
</x-app-layout>
