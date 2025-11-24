<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pelajar - {{ $siswa->nama }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            width: 350px;
            height: 220px;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: white;
            position: relative;
            overflow: hidden;
            padding: 20px;
            box-sizing: border-box;
        }
        .card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            display: flex;
            justify-content: space-between;
        }
        .info {
            flex: 1;
        }
        .name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        .detail {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 2px;
        }
        .qrcode-container {
            background: white;
            padding: 5px;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80px;
            height: 80px;
        }
        .print-btn {
            position: fixed;
            bottom: 20px;
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .print-btn:hover {
            background-color: #2563eb;
        }
        @media print {
            body {
                background-color: white;
                height: auto;
                display: block;
            }
            .card {
                margin: 0;
                box-shadow: none;
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="school-name">SMK NEGERI 1 CONTOH</div>
            <!-- Logo placeholder -->
            <div style="width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 50%;"></div>
        </div>
        <div class="content">
            <div class="info">
                <div class="name">{{ $siswa->nama }}</div>
                <div class="detail">NISN: {{ $siswa->nisn }}</div>
                <div class="detail">Kelas: {{ $siswa->kelas->nama_lengkap }}</div>
                <div class="detail">Jurusan: {{ $siswa->kelas->jurusan }}</div>
            </div>
            <div class="qrcode-container">
                <div id="qrcode"></div>
            </div>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">Cetak Kartu</button>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $siswa->nisn }}",
            width: 70,
            height: 70,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
