<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Absensi</h2>
                <p class="text-sm text-gray-600 mt-1">Pantau kehadiran siswa secara real-time</p>
            </div>
            @if(auth()->user()->role === 'tu')
            <a href="{{ route('absensi.export') }}?{{ http_build_query(request()->query()) }}"
               class="bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                <i class="fas fa-file-export text-sm"></i>
                <span>Export CSV</span>
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Absensi -->
                <div class="bg-gradient-to-r from-[#1FAE59] to-green-500 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold">{{ $absensi->total() }}</p>
                            <p class="text-green-100 text-sm">Total Absensi</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Hadir -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $absensi->where('status_masuk', 'hadir')->count() }}
                            </p>
                            <p class="text-gray-600 text-sm">Hadir Tepat</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Terlambat -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $absensi->where('status_masuk', 'terlambat')->count() }}
                            </p>
                            <p class="text-gray-600 text-sm">Terlambat</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Tidak Hadir -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-red-600">
                                {{ $absensi->where('status_masuk', 'tidak_hadir')->count() }}
                            </p>
                            <p class="text-gray-600 text-sm">Tidak Hadir</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-day text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Dipilih</p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ request('tanggal', date('Y-m-d')) ? \Carbon\Carbon::parse(request('tanggal', date('Y-m-d')))->translatedFormat('d F Y') : 'Hari Ini' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-percentage text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Persentase Hadir</p>
                            <p class="text-lg font-bold text-gray-900">
                                @php
                                    $total = $absensi->count();
                                    $hadir = $absensi->where('status_masuk', 'hadir')->count();
                                    $persentase = $total > 0 ? round(($hadir / $total) * 100) : 0;
                                @endphp
                                {{ $persentase }}%
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Rata-rata Kehadiran</p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ $persentase }}% hari ini
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-filter text-green-500 mr-2"></i>
                        Filter Data
                    </h3>
                    <span class="text-sm text-gray-600">{{ $absensi->total() }} data ditemukan</span>
                </div>

                <form action="{{ route('absensi.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200">
                        </div>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-door-open text-gray-400"></i>
                            </div>
                            <select name="kelas_id" id="kelas_id"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $item)
                                    <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status_masuk" class="block text-sm font-medium text-gray-700 mb-2">Status Masuk</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-sign-in-alt text-gray-400"></i>
                            </div>
                            <select name="status_masuk" id="status_masuk"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200">
                                <option value="">Semua Status</option>
                                <option value="hadir" {{ request('status_masuk') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="terlambat" {{ request('status_masuk') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="tidak_hadir" {{ request('status_masuk') == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Button -->
                    <div class="flex items-end">
                        <button type="submit"
                                class="bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl w-full flex items-center justify-center space-x-2">
                            <i class="fas fa-search"></i>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Rekap Absensi</h3>
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $absensi->firstItem() ?? 0 }}-{{ $absensi->lastItem() ?? 0 }} dari {{ $absensi->total() }} data
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
                                        <span>Tanggal</span>
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
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu Masuk</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Masuk</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu Pulang</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($absensi as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->tanggal->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $item->tanggal->translatedFormat('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-bold text-white">{{ strtoupper(substr($item->siswa->nama, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->siswa->nama }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->siswa->nisn }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-door-open mr-1"></i>
                                        {{ $item->siswa->kelas->nama_lengkap }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->waktu_masuk ? \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') : '-' }}
                                    </div>
                                    @if($item->waktu_masuk)
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->waktu_masuk)->format('d/m') }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $item->status_masuk_color }}-100 text-{{ $item->status_masuk_color }}-800">
                                        <i class="fas fa-{{ $item->status_masuk_icon }} mr-1 text-xs"></i>
                                        {{ ucfirst($item->status_masuk) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->waktu_pulang ? \Carbon\Carbon::parse($item->waktu_pulang)->format('H:i') : '-' }}
                                    </div>
                                    @if($item->waktu_pulang)
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->waktu_pulang)->format('d/m') }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $item->status_pulang_color }}-100 text-{{ $item->status_pulang_color }}-800">
                                        <i class="fas fa-{{ $item->status_pulang_icon }} mr-1 text-xs"></i>
                                        {{ ucfirst(str_replace('_', ' ', $item->status_pulang)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 mb-3">
                                        <i class="fas fa-clipboard-list text-4xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">Tidak ada data absensi</p>
                                    <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau tanggal untuk melihat data</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($absensi->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $absensi->firstItem() }} hingga {{ $absensi->lastItem() }} dari {{ $absensi->total() }} hasil
                        </div>
                        <div class="flex space-x-2">
                            {{ $absensi->links() }}
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

        .attendance-row {
            transition: all 0.3s ease;
        }

        .attendance-row:hover {
            transform: translateX(4px);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add real-time filter updates
            const filterInputs = document.querySelectorAll('#tanggal, #kelas_id, #status_masuk');
            let filterTimeout;

            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    clearTimeout(filterTimeout);
                    filterTimeout = setTimeout(() => {
                        // Add loading state
                        const submitBtn = document.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memfilter...</span>';
                        submitBtn.disabled = true;

                        // Submit form after short delay
                        setTimeout(() => {
                            document.querySelector('form').submit();
                        }, 500);
                    }, 800);
                });
            });

            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(4px)';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</x-app-layout>
