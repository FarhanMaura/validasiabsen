<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Kelas') }}
            </h2>
            <a href="{{ route('kelas.edit', $kelas) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                Edit Kelas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Informasi Kelas -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Kelas</h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nama Kelas</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $kelas->nama_kelas }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Tingkat</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $kelas->tingkat }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Jurusan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $kelas->jurusan }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                                <p class="mt-1 text-lg font-semibold text-blue-600">{{ $kelas->nama_lengkap }}</p>
                            </div>
                        </div>

                        <!-- Statistik -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Statistik</h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Total Siswa</label>
                                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $kelas->siswas->count() }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Siswa Aktif</label>
                                <p class="mt-1 text-lg text-green-600">
                                    {{ $kelas->siswas->where('status_aktif', true)->count() }} siswa
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Siswa -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Daftar Siswa</h3>

                        @if($kelas->siswas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($kelas->siswas as $siswa)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $siswa->nisn }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $siswa->nama }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $siswa->status_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $siswa->status_aktif_text }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('siswa.show', $siswa) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Belum ada siswa di kelas ini.</p>
                        @endif
                    </div>

                    <!-- Back Button -->
                    <div class="mt-6">
                        <a href="{{ route('kelas.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            Kembali ke Daftar Kelas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
