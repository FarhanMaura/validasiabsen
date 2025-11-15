<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Detail Siswa</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap data siswa</p>
            </div>
            @if(auth()->user()->role === 'tu')
            <div class="flex space-x-3">
                <a href="{{ route('siswa.edit', $siswa) }}"
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <i class="fas fa-edit text-sm"></i>
                    <span>Edit Siswa</span>
                </a>
                <a href="{{ route('siswa.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali</span>
                </a>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info Card -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#1FAE59] to-green-500 px-6 py-4 text-white">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">{{ $siswa->nama }}</h3>
                                    <p class="text-green-100">NISN: {{ $siswa->nisn }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Informasi Pribadi -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2 flex items-center">
                                        <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                                        Informasi Pribadi
                                    </h4>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">NISN</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $siswa->nisn }}</span>
                                        </div>

                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Nama Lengkap</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $siswa->nama }}</span>
                                        </div>

                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Kelas</span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-door-open mr-1"></i>
                                                {{ $siswa->kelas->nama_lengkap }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">Status</span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $siswa->status_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                <i class="fas fa-circle mr-1 text-xs"></i>
                                                {{ $siswa->status_aktif_text }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kontak & Lainnya -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-900 border-b pb-2 flex items-center">
                                        <i class="fas fa-address-card text-green-500 mr-2"></i>
                                        Kontak & Lainnya
                                    </h4>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">No. Telepon Ortu</span>
                                            <span class="text-sm font-semibold text-gray-900 flex items-center">
                                                <i class="fas fa-phone text-gray-400 mr-1 text-xs"></i>
                                                {{ $siswa->no_telepon_ortu }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600">RFID UID</span>
                                            @if($siswa->rfid_uid)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-id-card mr-1"></i>
                                                {{ $siswa->rfid_uid }}
                                            </span>
                                            @else
                                            <span class="text-sm text-gray-500">-</span>
                                            @endif
                                        </div>

                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <span class="text-sm font-medium text-gray-600 block mb-2">Alamat</span>
                                            <p class="text-sm text-gray-900 leading-relaxed">{{ $siswa->alamat }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Absensi Terbaru -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-clipboard-check text-orange-500 mr-2"></i>
                                    Riwayat Absensi Terbaru
                                </h4>
                                <span class="text-sm text-gray-600">{{ $siswa->absensi->count() }} catatan</span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($siswa->absensi->count() > 0)
                            <div class="space-y-3">
                                @foreach($siswa->absensi->take(5) as $absensi)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                            <i class="fas fa-calendar-day text-blue-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $absensi->tanggal->format('d F Y') }}
                                            </p>
                                            <p class="text-xs text-gray-600">
                                                @if($absensi->waktu_masuk)
                                                Masuk: {{ $absensi->waktu_masuk->format('H:i') }}
                                                @else
                                                Tidak hadir
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $absensi->status_masuk_color }}-100 text-{{ $absensi->status_masuk_color }}-800">
                                        <i class="fas fa-{{ $absensi->status_masuk_icon }} mr-1 text-xs"></i>
                                        {{ ucfirst($absensi->status_masuk) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>

                            @if($siswa->absensi->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                                    Lihat semua riwayat absensi →
                                </a>
                            </div>
                            @endif
                            @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 mb-3">
                                    <i class="fas fa-clipboard-list text-4xl"></i>
                                </div>
                                <p class="text-gray-500 text-lg font-medium">Belum ada data absensi</p>
                                <p class="text-gray-400 text-sm mt-1">Siswa belum memiliki catatan kehadiran</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Stats -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                            Statistik Singkat
                        </h4>
                        <div class="space-y-4">
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <p class="text-2xl font-bold text-blue-600">{{ $siswa->absensi->where('status_masuk', 'hadir')->count() }}</p>
                                <p class="text-sm text-blue-800">Hadir Tepat</p>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-xl">
                                <p class="text-2xl font-bold text-yellow-600">{{ $siswa->absensi->where('status_masuk', 'terlambat')->count() }}</p>
                                <p class="text-sm text-yellow-800">Terlambat</p>
                            </div>
                            <div class="text-center p-4 bg-red-50 rounded-xl">
                                <p class="text-2xl font-bold text-red-600">{{ $siswa->absensi->where('status_masuk', 'tidak_hadir')->count() }}</p>
                                <p class="text-sm text-red-800">Tidak Hadir</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    @if(auth()->user()->role === 'tu')
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bolt text-orange-500 mr-2"></i>
                            Quick Actions
                        </h4>
                        <div class="space-y-3">
                            <a href="{{ route('siswa.edit', $siswa) }}"
                               class="w-full flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors duration-200 group">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-edit text-white text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Edit Data</span>
                            </a>

                            <a href="#"
                               class="w-full flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-xl transition-colors duration-200 group">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-print text-white text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Cetak Laporan</span>
                            </a>

                            <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full flex items-center p-3 bg-red-50 hover:bg-red-100 rounded-xl transition-colors duration-200 group">
                                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-trash text-white text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">Hapus Siswa</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Info Card -->
                    <div class="bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-2xl shadow-lg p-6 text-white">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <h5 class="font-semibold mb-2">Informasi</h5>
                            <p class="text-green-100 text-sm">Data terakhir diperbarui: {{ $siswa->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .status-badge {
            transition: all 0.3s ease;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }

        .info-card {
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
        }
    </style>
</x-app-layout>
