<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $siswa->nama }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: white;
        }
        #qrcode {
            margin-bottom: 20px;
        }
        .print-btn {
            margin-top: 30px;
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
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div id="qrcode"></div>

    <button class="print-btn" onclick="window.print()">Cetak QR Code</button>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ route('siswa.public', $siswa->nisn) }}",
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
