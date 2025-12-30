<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RFID Checker - Validasi Absen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>

        /* Previous CSS content remains, adding new styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 800px; /* Widened for filters */
            width: 100%;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        /* Filter Section Styles */
        .filters {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            margin-bottom: 30px;
            background: #f3f4f6;
            padding: 16px;
            border-radius: 12px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            background: white;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .rfid-display {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 3px dashed #10b981;
            border-radius: 16px;
            padding: 60px 30px;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            transition: all 0.3s ease;
        }

        .rfid-display.waiting {
            border-color: #d1d5db;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }

        .rfid-display.success {
            animation: pulse 0.6s ease;
            border-color: #10b981;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .rfid-display.error {
            animation: shake 0.5s ease;
            border-color: #ef4444;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .rfid-icon {
            font-size: 64px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .rfid-uid {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }

        .rfid-status {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .student-info {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .student-info h3 {
            font-size: 20px;
            font-weight: 600;
            color: #059669;
            margin-bottom: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
            font-weight: 500;
        }

        .info-value {
            color: #1f2937;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .history {
            margin-top: 30px;
        }

        .history h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .history-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .history-item {
            background: #f9fafb;
            border-left: 4px solid #10b981;
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-item.unregistered {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }

        .history-uid {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #1f2937;
        }

        .history-time {
            font-size: 12px;
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }

        .badge-success {
            background: #d1fae5;
            color: #059669;
        }

        .badge-warning {
            background: #fef3c7;
            color: #d97706;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #10b981;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .container {
                padding: 24px;
            }
            .filters {
                grid-template-columns: 1fr;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">
            <span>←</span> Kembali ke Dashboard
        </a>

        <div class="header">
            <h1>🔍 RFID Checker</h1>
            <p>Pilih alat dan kelas, lalu tap kartu untuk validasi</p>
        </div>

        <div class="filters">
            <div class="form-group">
                <label for="deviceSelect">📡 Alat / Device</label>
                <select id="deviceSelect" class="form-control">
                    <option value="">Semua Alat</option>
                    @if(request('device_ip'))
                        <option value="{{ request('device_ip') }}" selected>{{ request('device_ip') }} (Tersimpan)</option>
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="classSelect">🏫 Kelas</label>
                <select id="classSelect" class="form-control">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="studentSelect">👤 Siswa (Opsional)</label>
                <select id="studentSelect" class="form-control" disabled>
                    <option value="">-- Pilih Kelas Dahulu --</option>
                </select>
            </div>
        </div>

        <div class="rfid-display waiting" id="rfidDisplay">
            <div class="rfid-icon" id="rfidIcon">📡</div>
            <div class="rfid-uid" id="rfidUid">Menunggu kartu...</div>
            <div class="rfid-status" id="rfidStatus">Silakan tap kartu RFID Anda</div>
            
            <div class="student-info" id="studentInfo" style="display: none;"></div>
        </div>

        <!-- Hidden Alert for Student Match -->
        <div id="studentMatchAlert" style="display:none; background-color: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            <strong id="matchText"></strong>
        </div>

        <div class="action-buttons">
            <button class="btn btn-primary" id="linkBtn" style="display: none; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                🔗 Daftarkan Kartu
            </button>
            <button class="btn btn-primary" id="copyBtn" style="display: none;">
                📋 Copy UID
            </button>
            <button class="btn btn-secondary" onclick="clearDisplay()">
                🔄 Reset
            </button>
        </div>

        <div class="history">
            <h3>📜 Riwayat Scan (Live)</h3>
            <div class="history-list" id="historyList">
                <p style="text-align: center; color: #9ca3af; padding: 20px;">Belum ada riwayat scan</p>
            </div>
        </div>
    </div>

    <script>
        let pollingInterval;
        let lastRfidUid = null;
        let history = JSON.parse(localStorage.getItem('rfidHistory') || '[]');
        
        // Pass data from Backend
        const styles = @json($kelas);
        const allStudents = @json($siswas);

        // Elements
        const deviceSelect = document.getElementById('deviceSelect');
        const classSelect = document.getElementById('classSelect');
        const studentSelect = document.getElementById('studentSelect');
        const copyBtn = document.getElementById('copyBtn');
        const linkBtn = document.getElementById('linkBtn');

        // Initial Setup
        window.addEventListener('load', () => {
            renderHistory();
            startPolling();
        });

        // Event Listeners for Filters
        classSelect.addEventListener('change', () => {
            const classId = classSelect.value;
            studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            
            if (classId) {
                studentSelect.disabled = false;
                const filteredStudents = allStudents.filter(s => s.kelas_id == classId);
                
                // Sort by name
                filteredStudents.sort((a, b) => a.nama.localeCompare(b.nama));
                
                filteredStudents.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = student.nama;
                    studentSelect.appendChild(option);
                });
            } else {
                studentSelect.disabled = true;
                studentSelect.innerHTML = '<option value="">-- Pilih Kelas Dahulu --</option>';
            }
        });

        function startPolling() {
            // Poll every 1 second
            pollingInterval = setInterval(checkForRFID, 1000);
        }

        async function checkForRFID() {
            try {
                // Determine Query Params
                let url = '{{ route("api.rfid.latest") }}';
                const selectedDevice = deviceSelect.value;
                if (selectedDevice) {
                    url += `?device_ip=${selectedDevice}`;
                }

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) return;

                const data = await response.json();
                
                // Update Device Dropdown (Active Devices)
                if (data.active_devices) {
                    updateDeviceOptions(data.active_devices);
                }

                if (data.status === 'success' && data.scans && data.scans.length > 0) {
                    // Get the most recent scan
                    const latestScan = data.scans[0];
                    
                    // Display if different from last
                    if (latestScan.rfid_uid !== lastRfidUid) {
                        displayRFID(latestScan);
                    }
                }
            } catch (error) {
                console.error('Error polling:', error);
            }
        }

        function updateDeviceOptions(activeIps) {
            const currentVal = deviceSelect.value;
            // Only update if options changed count to avoid flickering (simplified)
            // Ideally we check content.
            
            // Keep "All" and "Current"
            const uniqueIps = new Set(activeIps);
            if(currentVal) uniqueIps.add(currentVal);

            // Rebuild options if size differs or just once? 
            // To prevent UI glitching, we check if the list includes new IPs.
            
            // Simplified: Just add missing IPs
            Array.from(uniqueIps).forEach(ip => {
                if (!deviceSelect.querySelector(`option[value="${ip}"]`)) {
                    const option = document.createElement('option');
                    option.value = ip;
                    option.textContent = `Alat (${ip})`;
                    deviceSelect.appendChild(option);
                }
            });
        }

        function displayRFID(data) {
            const display = document.getElementById('rfidDisplay');
            const icon = document.getElementById('rfidIcon');
            const uid = document.getElementById('rfidUid');
            const status = document.getElementById('rfidStatus');
            const studentInfo = document.getElementById('studentInfo');
            const matchAlert = document.getElementById('studentMatchAlert');

            // Rate Limit Display
            if (data.rfid_uid === lastRfidUid && Date.now() - (window.lastScanTime || 0) < 3000) {
                return;
            }
            lastRfidUid = data.rfid_uid;
            window.lastScanTime = Date.now();

            // Setup Details
            display.className = 'rfid-display';
            uid.textContent = data.rfid_uid;
            matchAlert.style.display = 'none';

            // Reset Buttons
            copyBtn.style.display = 'none';
            linkBtn.style.display = 'none';

            if (data.status === 'success') {
                // Registered
                display.classList.add('success');
                icon.textContent = '✅';
                status.textContent = 'Kartu Terdaftar';
                
                // Logic: Check if matches selected student (if any)
                const selectedStudentId = studentSelect.value;
                // Note: data.data.nama is returned. We don't have ID in common response.
                // But we can match Name if we trust unique names, or just show info.
                
                studentInfo.style.display = 'block';
                studentInfo.innerHTML = `
                    <h3>📚 Informasi Siswa</h3>
                    <div class="info-row">
                        <span class="info-label">Nama:</span>
                        <span class="info-value">${data.data.nama}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kelas:</span>
                        <span class="info-value">${data.data.kelas}</span>
                    </div>
                `;

                addToHistory(data.rfid_uid, data.data.nama, true);
            } else {
                // Unregistered
                display.classList.add('error');
                icon.textContent = '❌';
                status.textContent = 'Kartu Belum Terdaftar';
                studentInfo.style.display = 'none';

                // Show Copy Button by default
                copyBtn.style.display = 'flex';

                // Check Validation: If Student Selected
                if (studentSelect.value) {
                     const selectedName = studentSelect.options[studentSelect.selectedIndex].text;
                     
                     // Show Alert
                     matchAlert.style.display = 'block';
                     matchAlert.innerHTML = `Kartu ini belum terdaftar. Tekan tombol di bawah untuk mendaftarkan ke <strong>${selectedName}</strong>`;
                     
                     // Show Link Button
                     linkBtn.style.display = 'flex';
                     linkBtn.innerHTML = `🔗 Daftarkan ke ${selectedName.split(' ')[0]}`; // Short name
                     linkBtn.onclick = () => linkCardToStudent(data.rfid_uid, studentSelect.value);
                     
                     // Hide Copy Button to reduce clutter/confusion if we want them to link
                     copyBtn.style.display = 'none'; 
                }

                addToHistory(data.rfid_uid, null, false);
            }

            playBeep();
        }

        async function linkCardToStudent(uid, studentId) {
            if (!confirm('Apakah Anda yakin ingin mendaftarkan kartu ini ke siswa yang dipilih?')) return;

            try {
                linkBtn.disabled = true;
                linkBtn.textContent = 'Mendaftarkan...';

                const response = await fetch('{{ route("api.rfid.link") }}', {
                    method: 'POST',
                    headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        rfid_uid: uid,
                        siswa_id: studentId
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Berhasil! Kartu telah didaftarkan.');
                    
                    // Force display update to "Success"
                    const display = document.getElementById('rfidDisplay');
                    const icon = document.getElementById('rfidIcon');
                    const status = document.getElementById('rfidStatus');
                    const studentInfo = document.getElementById('studentInfo');
                    const matchAlert = document.getElementById('studentMatchAlert');

                    display.className = 'rfid-display success';
                    icon.textContent = '✅';
                    status.textContent = 'Kartu Terdaftar (Baru)';
                    matchAlert.style.display = 'none';
                    linkBtn.style.display = 'none';

                    studentInfo.style.display = 'block';
                    studentInfo.innerHTML = `
                        <h3>📚 Informasi Siswa</h3>
                        <div class="info-row">
                            <span class="info-label">Nama:</span>
                            <span class="info-value">${result.student.nama}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value">Baru Didaftarkan</span>
                        </div>
                    `;
                    
                    // Add to history as success
                    addToHistory(uid, result.student.nama, true);

                    // Reset selection to prevent accidental overwrite
                    // studentSelect.value = ""; 
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan saat mendaftarkan kartu.');
            } finally {
                linkBtn.disabled = false;
                linkBtn.textContent = '🔗 Daftarkan Kartu';
            }
        }
        
        function clearDisplay() {
            const display = document.getElementById('rfidDisplay');
            const icon = document.getElementById('rfidIcon');
            const uid = document.getElementById('rfidUid');
            const status = document.getElementById('rfidStatus');
            const studentInfo = document.getElementById('studentInfo');
            const matchAlert = document.getElementById('studentMatchAlert');

            display.className = 'rfid-display waiting';
            icon.textContent = '📡';
            uid.textContent = 'Menunggu kartu...';
            status.textContent = 'Silakan tap kartu RFID Anda';
            studentInfo.style.display = 'none';
            matchAlert.style.display = 'none';
            
            copyBtn.style.display = 'none';
            linkBtn.style.display = 'none';
            
            lastRfidUid = null;
        }

        function addToHistory(uid, name, isRegistered) {
            const timestamp = new Date().toLocaleTimeString('id-ID');
            const item = { uid, name, isRegistered, timestamp };
            
            history.unshift(item);
            if (history.length > 10) history.pop();
            
            localStorage.setItem('rfidHistory', JSON.stringify(history));
            renderHistory();
        }

        function renderHistory() {
            const historyList = document.getElementById('historyList');
            
            if (history.length === 0) {
                historyList.innerHTML = '<p style="text-align: center; color: #9ca3af; padding: 20px;">Belum ada riwayat scan</p>';
                return;
            }

            historyList.innerHTML = history.map(item => `
                <div class="history-item ${item.isRegistered ? '' : 'unregistered'}">
                    <div>
                        <div class="history-uid">${item.uid}</div>
                        ${item.name ? `<div style="font-size: 13px; color: #6b7280; margin-top: 4px;">${item.name}</div>` : '<div style="font-size: 13px; color: #f59e0b; margin-top: 4px;">Belum Terdaftar</div>'}
                    </div>
                    <div style="text-align: right;">
                        <span class="badge ${item.isRegistered ? 'badge-success' : 'badge-warning'}">
                            ${item.isRegistered ? '✓' : '⚠'}
                        </span>
                        <div class="history-time" style="margin-top: 4px;">${item.timestamp}</div>
                    </div>
                </div>
            `).join('');
        }

        // Copy Button Logic
        document.getElementById('copyBtn').addEventListener('click', () => {
            const uid = document.getElementById('rfidUid').textContent;
            navigator.clipboard.writeText(uid).then(() => {
                const btn = document.getElementById('copyBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '✅ Berhasil!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 2000);
            });
        });

        function playBeep() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            } catch (e) {
                console.log('Audio not supported');
            }
        }
    </script>
</body>
</html>
