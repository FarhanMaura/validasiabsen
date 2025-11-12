<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Siswa') }}
            </h2>
            @if(auth()->user()->role === 'tu')
            <a href="{{ route('siswa.edit', $siswa) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                Edit Siswa
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informasi Pribadi -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Pribadi</h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">NISN</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $siswa->nisn }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $siswa->nama }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Kelas</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $siswa->kelas->nama_lengkap }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $siswa->status_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $siswa->status_aktif_text }}
                                </span>
                            </div>
                        </div>

                        <!-- Kontak & Lainnya -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Kontak & Lainnya</h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">No. Telepon Orang Tua</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $siswa->no_telepon_ortu }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">RFID UID</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $siswa->rfid_uid ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Alamat</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $siswa->alamat }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Absensi Terbaru -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Absensi Terbaru</h3>

                        @if($siswa->absensi->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Masuk</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($siswa->absensi->take(5) as $absensi)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $absensi->tanggal->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $absensi->waktu_masuk ? $absensi->waktu_masuk->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $absensi->status_masuk_color }}-100 text-{{ $absensi->status_masuk_color }}-800">
                                                {{ ucfirst($absensi->status_masuk) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Belum ada data absensi.</p>
                        @endif
                    </div>

                    <!-- Back Button -->
                    <div class="mt-6">
                        <a href="{{ route('siswa.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            Kembali ke Daftar Siswa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
