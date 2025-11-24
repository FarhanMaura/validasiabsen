<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa - {{ $siswa->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full text-center">
        <div class="w-24 h-24 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center text-gray-500 text-3xl font-bold">
            {{ substr($siswa->nama, 0, 1) }}
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $siswa->nama }}</h1>
        <p class="text-gray-600 mb-4">{{ $siswa->kelas->nama_lengkap }}</p>
        
        <div class="border-t pt-4 text-left">
            <div class="mb-2">
                <span class="text-xs text-gray-500 uppercase font-semibold">NISN</span>
                <p class="text-gray-800">{{ $siswa->nisn }}</p>
            </div>
            <div class="mb-2">
                <span class="text-xs text-gray-500 uppercase font-semibold">Alamat</span>
                <p class="text-gray-800">{{ $siswa->alamat }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-500 uppercase font-semibold">Status</span>
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $siswa->status_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $siswa->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
        </div>
    </div>
</body>
</html>
