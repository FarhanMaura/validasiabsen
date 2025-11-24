<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan QR Code Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-4">Scanner</h3>
                            <div id="reader" width="600px"></div>
                            <div class="mt-4 text-center">
                                <p id="scan-status" class="text-sm text-gray-500">Arahkan kamera ke QR Code kartu siswa.</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium mb-4">Hasil Scan Terakhir</h3>
                            <div id="result-container" class="hidden border rounded-lg p-4 bg-gray-50">
                                <div class="flex items-center mb-4">
                                    <div id="result-icon" class="w-12 h-12 rounded-full flex items-center justify-center text-white text-2xl mr-4">
                                        <!-- Icon will be injected here -->
                                    </div>
                                    <div>
                                        <h4 id="result-title" class="text-lg font-bold text-gray-800"></h4>
                                        <p id="result-timestamp" class="text-sm text-gray-500"></p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nama:</span>
                                        <span id="result-nama" class="font-medium text-gray-900"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Kelas:</span>
                                        <span id="result-kelas" class="font-medium text-gray-900"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span id="result-status" class="font-medium px-2 py-1 rounded text-xs"></span>
                                    </div>
                                </div>
                            </div>
                            <div id="empty-state" class="text-center py-12 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <p>Belum ada data scan.</p>
                            </div>
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

            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                if (isProcessing) return;
                isProcessing = true;

                // Play beep sound
                const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                audio.play().catch(e => console.log('Audio play failed', e));

                document.getElementById('scan-status').innerText = "Memproses: " + decodedText;

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
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else if (data.status === 'info') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Info',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
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
                    document.getElementById('scan-status').innerText = "Arahkan kamera ke barcode kartu siswa.";
                    // Add delay before next scan to prevent double scanning
                    setTimeout(() => {
                        isProcessing = false;
                    }, 2000);
                });
            };

            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

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
                        document.getElementById('scan-status').innerText = "Gagal memulai kamera: " + err;
                    });
                } else {
                    document.getElementById('scan-status').innerText = "Kamera tidak ditemukan.";
                }
            }).catch(err => {
                console.error("Error getting cameras", err);
                document.getElementById('scan-status').innerText = "Gagal mengakses kamera.";
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
                        title.innerText = "Absen Masuk";
                        timestamp.innerText = resultData.waktu_masuk;
                        status.innerText = resultData.status_masuk.toUpperCase();
                        
                        if (resultData.status_masuk === 'hadir') {
                            icon.className = "w-12 h-12 rounded-full flex items-center justify-center text-white text-2xl mr-4 bg-green-500";
                            icon.innerHTML = '<i class="fas fa-check"></i>';
                            status.className = "font-medium px-2 py-1 rounded text-xs bg-green-100 text-green-800";
                        } else {
                            icon.className = "w-12 h-12 rounded-full flex items-center justify-center text-white text-2xl mr-4 bg-yellow-500";
                            icon.innerHTML = '<i class="fas fa-clock"></i>';
                            status.className = "font-medium px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800";
                        }
                    } else if (data.action === 'check_out') {
                        title.innerText = "Absen Pulang";
                        timestamp.innerText = resultData.waktu_pulang;
                        status.innerText = resultData.status_pulang.toUpperCase();
                        
                        icon.className = "w-12 h-12 rounded-full flex items-center justify-center text-white text-2xl mr-4 bg-blue-500";
                        icon.innerHTML = '<i class="fas fa-sign-out-alt"></i>';
                        status.className = "font-medium px-2 py-1 rounded text-xs bg-blue-100 text-blue-800";
                    }
                } else {
                    title.innerText = "Error";
                    timestamp.innerText = "-";
                    nama.innerText = "-";
                    kelas.innerText = "-";
                    status.innerText = "GAGAL";
                    
                    icon.className = "w-12 h-12 rounded-full flex items-center justify-center text-white text-2xl mr-4 bg-red-500";
                    icon.innerHTML = '<i class="fas fa-times"></i>';
                    status.className = "font-medium px-2 py-1 rounded text-xs bg-red-100 text-red-800";
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
