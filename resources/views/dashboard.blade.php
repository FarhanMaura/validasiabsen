<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="mx-auto">
            <!-- Welcome Section -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                            <p class="text-green-100">Sistem Manajemen Sekolah - {{ now()->format('l, d F Y') }}</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-school text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Siswa -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Siswa</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                                <p class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-users mr-1"></i>Semua Kelas
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Kelas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Kelas</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalKelas }}</p>
                                <p class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-door-open mr-1"></i>Aktif
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chalkboard text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hadir Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Hadir Hari Ini</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $hadirHariIni + $terlambatHariIni }}</p>
                                <p class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>Dari {{ $totalSiswa }} Siswa
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-emerald-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Persentase Kehadiran -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Kehadiran</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    @php
                                        $persentase = $totalSiswa > 0 ? round((($hadirHariIni + $terlambatHariIni) / $totalSiswa) * 100) : 0;
                                    @endphp
                                    {{ $persentase }}%
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-chart-line mr-1"></i>Hari Ini
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts & Quick Actions Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Grafik Absensi Hari Ini -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Statistik Absensi Hari Ini</h3>
                            <div class="flex space-x-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                    <i class="fas fa-sync-alt mr-1"></i>Real-time
                                </span>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="absensiHariIniChart"></canvas>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">{{ $hadirHariIni }}</p>
                                <p class="text-xs text-green-800">Hadir Tepat</p>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <p class="text-2xl font-bold text-yellow-600">{{ $terlambatHariIni }}</p>
                                <p class="text-xs text-yellow-800">Terlambat</p>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded-lg">
                                <p class="text-2xl font-bold text-red-600">{{ $tidakHadirHariIni }}</p>
                                <p class="text-xs text-red-800">Tidak Hadir</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Status -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    @if(auth()->user()->role === 'tu')
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions ⚡</h3>
                        <div class="space-y-3">
                            <a href="{{ route('siswa.create') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-plus text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Tambah Siswa</p>
                                    <p class="text-xs text-gray-600">Input data siswa baru</p>
                                </div>
                            </a>

                            <a href="{{ route('siswa.export.template') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-xl transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-file-export text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Template Import</p>
                                    <p class="text-xs text-gray-600">Download template Excel</p>
                                </div>
                            </a>

                            <a href="{{ route('absensi.rekap') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-chart-bar text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Rekap Absensi</p>
                                    <p class="text-xs text-gray-600">Lihat laporan lengkap</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Status Sistem -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Sistem 🟢</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium">Server</span>
                                </div>
                                <span class="text-xs text-green-600 font-medium">Online</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium">Database</span>
                                </div>
                                <span class="text-xs text-green-600 font-medium">Connected</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium">Siswa Terdaftar</span>
                                </div>
                                <span class="text-xs text-blue-600 font-medium">{{ $totalSiswa }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-emerald-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium">Kelas Aktif</span>
                                </div>
                                <span class="text-xs text-emerald-600 font-medium">{{ $totalKelas }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Grafik Absensi Bulan Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Trend Absensi Bulan Ini</h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                            {{ now()->format('F Y') }}
                        </span>
                    </div>
                    <div class="h-64">
                        <canvas id="absensiBulanIniChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru 📋</h3>
                        <i class="fas fa-bell text-gray-400"></i>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Sistem berjalan normal</p>
                                <p class="text-xs text-gray-600">Semua fitur berfungsi dengan baik</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Data siswa terupdate</p>
                                <p class="text-xs text-gray-600">{{ $totalSiswa }} siswa terdaftar</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-purple-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-chart-line text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Laporan harian siap</p>
                                <p class="text-xs text-gray-600">Absensi hari ini telah tercatat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Absensi Hari Ini - Donut dengan animasi
            const ctxHariIni = document.getElementById('absensiHariIniChart').getContext('2d');
            new Chart(ctxHariIni, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir Tepat', 'Terlambat', 'Tidak Hadir'],
                    datasets: [{
                        data: [{{ $hadirHariIni }}, {{ $terlambatHariIni }}, {{ $tidakHadirHariIni }}],
                        backgroundColor: [
                            '#10B981',
                            '#F59E0B',
                            '#EF4444'
                        ],
                        borderWidth: 3,
                        borderColor: '#FFFFFF',
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Chart Absensi Bulan Ini - Bar dengan gradient
            const ctxBulanIni = document.getElementById('absensiBulanIniChart').getContext('2d');

            // Create gradient
            const gradientHadir = ctxBulanIni.createLinearGradient(0, 0, 0, 300);
            gradientHadir.addColorStop(0, '#10B981');
            gradientHadir.addColorStop(1, '#34D399');

            const gradientTerlambat = ctxBulanIni.createLinearGradient(0, 0, 0, 300);
            gradientTerlambat.addColorStop(0, '#F59E0B');
            gradientTerlambat.addColorStop(1, '#FBBF24');

            new Chart(ctxBulanIni, {
                type: 'bar',
                data: {
                    labels: ['Hadir', 'Terlambat'],
                    datasets: [{
                        label: 'Jumlah Kehadiran',
                        data: [{{ $hadirBulanIni }}, {{ $terlambatBulanIni }}],
                        backgroundColor: [
                            gradientHadir,
                            gradientTerlambat
                        ],
                        borderWidth: 0,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Add hover effects to cards
            const cards = document.querySelectorAll('.bg-white');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1FAE59 0%, #10B981 100%);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .pulse-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
    @endpush
</x-app-layout>
