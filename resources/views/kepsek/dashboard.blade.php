<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kepala Sekolah') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="mx-auto">
            <!-- Welcome Section -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">Dashboard Kepala Sekolah 🎓</h1>
                            <p class="text-indigo-100">Selamat datang, {{ Auth::user()->name }} - {{ now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-tie text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Periode -->
            <div class="mb-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <form method="GET" action="{{ route('kepsek.dashboard') }}" class="flex items-center gap-4">
                        <div class="flex-1">
                            <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Filter Periode</label>
                            <input type="month" name="bulan" id="bulan" value="{{ $bulan }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="pt-6">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-filter mr-2"></i>Terapkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistik Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Siswa -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Siswa</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-users mr-1"></i>Siswa Aktif
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-graduate text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hadir Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Hadir Hari Ini</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $hadirHariIni }}</p>
                                <p class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>{{ $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100) : 0 }}% dari total
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Kehadiran -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Kehadiran Bulanan</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $rataRataKehadiran }}%</p>
                                <p class="text-xs {{ $rataRataKehadiran >= 85 ? 'text-green-600' : 'text-orange-600' }} mt-1">
                                    <i class="fas fa-chart-line mr-1"></i>{{ $rataRataKehadiran >= 85 ? 'Baik' : 'Perlu Perhatian' }}
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-percentage text-purple-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Terlambat -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Keterlambatan</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $totalTerlambatBulan }}</p>
                                <p class="text-xs text-orange-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i>Bulan ini
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tren Kehadiran & AI Rekomendasi -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Tren Kehadiran 30 Hari -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Tren Kehadiran 30 Hari Terakhir</h3>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">
                                <i class="fas fa-chart-line mr-1"></i>Trend Analysis
                            </span>
                        </div>
                        <div class="h-80">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- AI Rekomendasi Panel -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">AI Rekomendasi</h3>
                        <i class="fas fa-robot text-indigo-600 text-xl"></i>
                    </div>
                    
                    <div id="aiRecommendationContent" class="mb-4">
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-lightbulb text-4xl mb-3 text-gray-300"></i>
                            <p class="text-sm">Klik tombol di bawah untuk mendapatkan rekomendasi berbasis AI</p>
                        </div>
                    </div>

                    <button id="generateAIBtn" onclick="generateAIRecommendation()" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-medium">
                        <i class="fas fa-magic mr-2"></i>Generate Rekomendasi
                    </button>

                    <div id="aiSource" class="mt-3 text-xs text-gray-500 text-center hidden"></div>
                </div>
            </div>

            <!-- Siswa Sering Terlambat -->
            @if($siswaSringTerlambat->count() > 0)
            <div class="mb-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            Siswa Sering Terlambat (≥3x)
                        </h3>
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full font-medium">
                            {{ $siswaSringTerlambat->count() }} Siswa
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Terlambat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($siswaSringTerlambat as $index => $siswa)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $siswa->nisn }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $siswa->kelas->nama_lengkap }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                            {{ $siswa->jumlah_terlambat >= 5 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $siswa->jumlah_terlambat }}x
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $siswa->jumlah_terlambat >= 5 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $siswa->jumlah_terlambat >= 5 ? 'Perlu Tindakan' : 'Perhatian' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Statistik Per Kelas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Statistik Kehadiran Per Kelas</h3>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                        {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($kelasStats as $kelas)
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-800">{{ $kelas->nama_lengkap }}</h4>
                            <span class="px-2 py-1 text-xs rounded-full {{ $kelas->persentase_hadir >= 85 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $kelas->persentase_hadir }}%
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Hadir:</span>
                                <span class="font-medium text-green-600">{{ $kelas->total_hadir }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Terlambat:</span>
                                <span class="font-medium text-orange-600">{{ $kelas->total_terlambat }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $kelas->persentase_hadir }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Detail Statistik Bulanan -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-700 font-medium">Hadir</p>
                            <p class="text-2xl font-bold text-green-800">{{ $totalHadirBulan }}</p>
                        </div>
                        <i class="fas fa-check-circle text-3xl text-green-600"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-700 font-medium">Terlambat</p>
                            <p class="text-2xl font-bold text-orange-800">{{ $totalTerlambatBulan }}</p>
                        </div>
                        <i class="fas fa-clock text-3xl text-orange-600"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Izin</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $totalIzinBulan }}</p>
                        </div>
                        <i class="fas fa-file-alt text-3xl text-blue-600"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-700 font-medium">Sakit</p>
                            <p class="text-2xl font-bold text-purple-800">{{ $totalSakitBulan }}</p>
                        </div>
                        <i class="fas fa-notes-medical text-3xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Trend Chart
        const trendData = @json($trendKehadiran);
        
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: trendData.map(d => d.label),
                datasets: [
                    {
                        label: 'Hadir',
                        data: trendData.map(d => d.hadir),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Terlambat',
                        data: trendData.map(d => d.terlambat),
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Tidak Hadir',
                        data: trendData.map(d => d.tidak_hadir),
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // AI Recommendation Function
        function generateAIRecommendation() {
            const btn = document.getElementById('generateAIBtn');
            const content = document.getElementById('aiRecommendationContent');
            const source = document.getElementById('aiSource');
            
            // Show loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
            content.innerHTML = `
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-3"></div>
                    <p class="text-sm text-gray-600">Menganalisis data dan membuat rekomendasi...</p>
                </div>
            `;

            // Call API
            fetch('{{ route("kepsek.ai-recommendation") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    bulan: '{{ $bulan }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Format recommendations with markdown-like styling
                    const formattedRec = data.recommendations
                        .replace(/\*\*(.*?)\*\*/g, '<strong class="text-gray-900">$1</strong>')
                        .replace(/\n\n/g, '<br><br>')
                        .replace(/\n/g, '<br>');
                    
                    content.innerHTML = `
                        <div class="prose prose-sm max-w-none">
                            <div class="text-sm text-gray-700 leading-relaxed">
                                ${formattedRec}
                            </div>
                        </div>
                    `;
                    
                    source.textContent = `Sumber: ${data.source}`;
                    source.classList.remove('hidden');
                } else {
                    content.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                            <p class="text-sm">Gagal mendapatkan rekomendasi</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                        <p class="text-sm">Terjadi kesalahan saat mengambil rekomendasi</p>
                    </div>
                `;
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Refresh Rekomendasi';
            });
        }
    </script>
    @endpush
</x-app-layout>
