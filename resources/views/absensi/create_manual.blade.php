<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Input Absensi Manual</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Input data absensi siswa secara manual</p>
            </div>
            <a href="{{ route('absensi.scan') }}"
               class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 sm:px-4 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-colors duration-200 flex items-center justify-center sm:justify-start space-x-2">
                <i class="fas fa-qrcode text-xs sm:text-sm"></i>
                <span>Scan QR Code</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Form Input Absensi Manual</h3>
                        </div>

                        <div class="p-4 sm:p-6">
                            <form method="POST" action="{{ route('absensi.storeManual') }}">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                    <!-- Siswa -->
                                    <div class="md:col-span-2">
                                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-user-graduate mr-2 text-gray-400"></i>
                                            Pilih Siswa
                                        </label>
                                        <div class="relative">
                                            <select id="siswa_id" name="siswa_id"
                                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 appearance-none bg-white"
                                                    required>
                                                <option value="">-- Pilih Siswa --</option>
                                                @foreach($siswas as $siswa)
                                                    <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                                        {{ $siswa->nama }} - {{ $siswa->kelas->nama_lengkap }} ({{ $siswa->nisn }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                                <i class="fas fa-user-graduate"></i>
                                            </div>
                                        </div>
                                        @error('siswa_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal -->
                                    <div>
                                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                            Tanggal Absensi
                                        </label>
                                        <div class="relative">
                                            <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', date('Y-m-d')) }}"
                                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200"
                                                   required>
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        @error('tanggal')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-clipboard-check mr-2 text-gray-400"></i>
                                            Status Kehadiran
                                        </label>
                                        <div class="relative">
                                            <select id="status" name="status"
                                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 appearance-none bg-white"
                                                    required>
                                                <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                                <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                                <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                <option value="alfa" {{ old('status') == 'alfa' ? 'selected' : '' }}>Alfa</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                                <i class="fas fa-clipboard-check"></i>
                                            </div>
                                        </div>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="md:col-span-2">
                                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-sticky-note mr-2 text-gray-400"></i>
                                            Keterangan (Opsional)
                                        </label>
                                        <div class="relative">
                                            <textarea id="keterangan" name="keterangan" rows="4"
                                                      class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200"
                                                      placeholder="Masukkan keterangan tambahan jika diperlukan">{{ old('keterangan') }}</textarea>
                                            <div class="pointer-events-none absolute top-3 left-3 text-gray-400">
                                                <i class="fas fa-sticky-note"></i>
                                            </div>
                                        </div>
                                        @error('keterangan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                                    <a href="{{ route('absensi.index') }}"
                                       class="w-full sm:w-auto text-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center space-x-2">
                                        <i class="fas fa-arrow-left"></i>
                                        <span>Kembali ke Data Absensi</span>
                                    </a>
                                    <button type="submit"
                                            class="w-full sm:w-auto bg-[#1FAE59] hover:bg-green-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                                        <i class="fas fa-save"></i>
                                        <span>Simpan Absensi</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="space-y-4 sm:space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Statistik Hari Ini</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-green-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Hadir</span>
                                </div>
                                <span class="text-lg font-bold text-green-600">0</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-yellow-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Terlambat</span>
                                </div>
                                <span class="text-lg font-bold text-yellow-600">0</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user-clock text-blue-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Izin</span>
                                </div>
                                <span class="text-lg font-bold text-blue-600">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('absensi.scan') }}"
                               class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-qrcode text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Scan QR Code</p>
                                    <p class="text-xs text-gray-600">Gunakan scanner untuk absensi cepat</p>
                                </div>
                            </a>

                            <a href="{{ route('absensi.index') }}"
                               class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-list text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Lihat Data Absensi</p>
                                    <p class="text-xs text-gray-600">Lihat semua riwayat absensi</p>
                                </div>
                            </a>

                            <a href="{{ route('siswa.index') }}"
                               class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Data Siswa</p>
                                    <p class="text-xs text-gray-600">Kelola data siswa</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Help Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Panduan</h3>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <p>Pilih siswa dari daftar yang tersedia</p>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <p>Pastikan tanggal sesuai dengan hari absensi</p>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <p>Pilih status kehadiran yang sesuai</p>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <p>Isi keterangan jika diperlukan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Input Manual Terbaru</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-history text-3xl mb-3"></i>
                        <p class="text-sm">Belum ada data input manual hari ini</p>
                        <p class="text-xs mt-1">Data akan muncul setelah melakukan input</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update tanggal otomatis ke hari ini
            const tanggalInput = document.getElementById('tanggal');
            if (!tanggalInput.value) {
                tanggalInput.value = new Date().toISOString().split('T')[0];
            }

            // Add real-time form validation
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '' && this.hasAttribute('required')) {
                        this.classList.add('border-red-300');
                    } else {
                        this.classList.remove('border-red-300');
                    }
                });
            });

            // Quick status selection
            const statusSelect = document.getElementById('status');
            statusSelect.addEventListener('change', function() {
                const statusColor = {
                    'hadir': 'green',
                    'terlambat': 'yellow',
                    'izin': 'blue',
                    'sakit': 'orange',
                    'alfa': 'red'
                };

                const color = statusColor[this.value];
                if (color) {
                    this.className = `block w-full pl-10 pr-3 py-3 border border-${color}-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 appearance-none bg-white`;
                }
            });
        });
    </script>
</x-app-layout>
