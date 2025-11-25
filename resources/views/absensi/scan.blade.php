<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Scan QR Code Absensi</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Scan kartu siswa untuk absensi masuk/pulang</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Scanner Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Scanner QR Code</h3>
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-qrcode text-green-600 text-sm"></i>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border-2 border-dashed border-gray-300">
                        <div id="reader" class="w-full max-w-md mx-auto"></div>
                    </div>

                    <div class="mt-4 text-center">
                        <p id="scan-status" class="text-sm text-gray-600 bg-blue-50 rounded-lg py-2 px-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            Arahkan kamera ke QR Code kartu siswa
                        </p>
                    </div>

                    <!-- Manual Input Fallback -->
                    <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-yellow-800">Scanner bermasalah?</p>
                                <p class="text-xs text-yellow-600 mt-1">Gunakan input manual untuk absensi</p>
                                <a href="{{ route('absensi.manual') }}" class="inline-block mt-2 text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg transition-colors duration-200">
                                    Input Manual
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Hasil Scan Terakhir</h3>
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-blue-600 text-sm"></i>
                        </div>
                    </div>

                    <!-- Result Container -->
                    <div id="result-container" class="hidden border-2 border-green-200 rounded-xl p-4 sm:p-6 bg-green-50">
                        <div class="flex items-center mb-4">
                            <div id="result-icon" class="w-12 h-12 rounded-full flex items-center justify-center text-white text-xl mr-4">
                                <!-- Icon will be injected here -->
                            </div>
                            <div class="flex-1">
                                <h4 id="result-title" class="text-base sm:text-lg font-bold text-gray-800"></h4>
                                <p id="result-timestamp" class="text-xs sm:text-sm text-gray-600"></p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Nama Siswa:</span>
                                <span id="result-nama" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Kelas:</span>
                                <span id="result-kelas" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span id="result-status" class="text-xs font-medium px-3 py-1 rounded-full"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="empty-state" class="text-center py-8 sm:py-12 text-gray-400">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-qrcode text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-500">Belum ada data scan</p>
                        <p class="text-xs text-gray-400 mt-1">Hasil scan akan muncul di sini</p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-sign-in-alt text-green-600 text-xs"></i>
                            </div>
                            <p class="text-lg font-bold text-gray-900" id="total-masuk">0</p>
                            <p class="text-xs text-gray-600">Masuk Hari Ini</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-sign-out-alt text-blue-600 text-xs"></i>
                            </div>
                            <p class="text-lg font-bold text-gray-900" id="total-pulang">0</p>
                            <p class="text-xs text-gray-600">Pulang Hari Ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html5QrCode = new Html5Qrcode("reader");
            let isProcessing = false;
            let totalMasuk = 0;
            let totalPulang = 0;

            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                if (isProcessing) return;
                isProcessing = true;

                // Update scan status
                document.getElementById('scan-status').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses: ' + decodedText;
                document.getElementById('scan-status').className = 'text-sm text-blue-600 bg-blue-50 rounded-lg py-2 px-4';

                // Play beep sound
                const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                audio.play().catch(e => console.log('Audio play failed', e));

                fetch('{{ route("api.absensi.barcode") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nisn: decodedText })
                })
                .then(response => response.json())
                .then(data => {
                    displayResult(data);

                    if (data.status === 'success') {
                        if (data.action === 'check_in') totalMasuk++;
                        if (data.action === 'check_out') totalPulang++;
                        updateStats();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            background: '#f0f9ff',
                            color: '#0c4a6e'
                        });
                    } else if (data.status === 'info') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Informasi',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            background: '#f0f9ff',
                            color: '#0c4a6e'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan koneksi.',
                    });
                })
                .finally(() => {
                    document.getElementById('scan-status').innerHTML = '<i class="fas fa-info-circle mr-2"></i>Arahkan kamera ke QR Code kartu siswa';
                    document.getElementById('scan-status').className = 'text-sm text-gray-600 bg-blue-50 rounded-lg py-2 px-4';

                    // Add delay before next scan to prevent double scanning
                    setTimeout(() => {
                        isProcessing = false;
                    }, 2000);
                });
            };

            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_QR_CODE]
            };

            // Start scanning
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    var cameraId = devices[0].id;
                    // Try to find back camera
                    for (var i = 0; i < devices.length; i++) {
                        if (devices[i].label.toLowerCase().includes('back')) {
                            cameraId = devices[i].id;
                            break;
                        }
                    }

                    html5QrCode.start(cameraId, config, qrCodeSuccessCallback)
                    .catch(err => {
                        console.error("Error starting scanner", err);
                        document.getElementById('scan-status').innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Gagal memulai kamera: ' + err;
                        document.getElementById('scan-status').className = 'text-sm text-red-600 bg-red-50 rounded-lg py-2 px-4';
                    });
                } else {
                    document.getElementById('scan-status').innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Kamera tidak ditemukan';
                    document.getElementById('scan-status').className = 'text-sm text-red-600 bg-red-50 rounded-lg py-2 px-4';
                }
            }).catch(err => {
                console.error("Error getting cameras", err);
                document.getElementById('scan-status').innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Gagal mengakses kamera';
                document.getElementById('scan-status').className = 'text-sm text-red-600 bg-red-50 rounded-lg py-2 px-4';
            });

            function displayResult(data) {
                const container = document.getElementById('result-container');
                const emptyState = document.getElementById('empty-state');
                const icon = document.getElementById('result-icon');
                const title = document.getElementById('result-title');
                const timestamp = document.getElementById('result-timestamp');
                const nama = document.getElementById('result-nama');
                const kelas = document.getElementById('result-kelas');
                const status = document.getElementById('result-status');

                container.classList.remove('hidden');
                emptyState.classList.add('hidden');

                if (data.status === 'success' || data.status === 'info') {
                    const resultData = data.data;
                    nama.innerText = resultData.nama;
                    kelas.innerText = resultData.kelas;

                    if (data.action === 'check_in' || (data.status === 'info' && resultData.waktu_masuk)) {
                        title.innerText = "Absen Masuk Berhasil";
                        timestamp.innerText = "Waktu: " + resultData.waktu_masuk;
                        status.innerText = resultData.status_masuk.toUpperCase();

                        if (resultData.status_masuk === 'hadir') {
                            icon.className = "w-12 h-12 rounded-full flex items-center justify-center text-white text-xl mr-4 bg-green-500";
                            icon.innerHTML = '<i class="fas fa-check"></i>';
                            status.className = "text-xs font-medium px-3 py-1 rounded-full bg-green-100 text-green-800";
                        } else {
                            icon.className = "w-12 h-12 rounded-full flex items-center justify-center text-white text-xl mr-4 bg-yellow-500";
                            icon.innerHTML = '<i class="fas fa-clock"></i>';
                            status.className = "text-xs font-medium px-3 py-1 rounded-full bg-yellow-100 text-yellow-800";
                        }
                    } else if (data.action === 'check_out') {
                        title.innerText = "Absen Pulang Berhasil";
                        timestamp.innerText = "Waktu: " + resultData.waktu_pulang;
                        status.innerText = resultData.status_pulang.toUpperCase();

                        icon.className = "w-12 h-12 rounded-full flex items-center justify-center text-white text-xl mr-4 bg-blue-500";
                        icon.innerHTML = '<i class="fas fa-sign-out-alt"></i>';
                        status.className = "text-xs font-medium px-3 py-1 rounded-full bg-blue-100 text-blue-800";
                    }
                } else {
                    title.innerText = "Scan Gagal";
                    timestamp.innerText = "Waktu: " + new Date().toLocaleTimeString();
                    nama.innerText = "-";
                    kelas.innerText = "-";
                    status.innerText = "GAGAL";

                    icon.className = "w-12 h-12 rounded-full flex items-center justify-center text-white text-xl mr-4 bg-red-500";
                    icon.innerHTML = '<i class="fas fa-times"></i>';
                    status.className = "text-xs font-medium px-3 py-1 rounded-full bg-red-100 text-red-800";
                }
            }

            function updateStats() {
                document.getElementById('total-masuk').textContent = totalMasuk;
                document.getElementById('total-pulang').textContent = totalPulang;
            }

            // Initial stats update
            updateStats();
        });
    </script>
    @endpush

    <style>
        #reader {
            border: none !important;
            padding: 0 !important;
        }

        #reader__dashboard_section {
            display: none !important;
        }

        #reader__scan_region {
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
</x-app-layout>
