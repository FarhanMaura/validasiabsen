<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Data Kelas</h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi kelas {{ $kelas->nama_lengkap }}</p>
            </div>
            <a href="{{ route('kelas.show', $kelas) }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center space-x-2">
                <i class="fas fa-arrow-left text-sm"></i>
                <span>Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-2xl">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#1FAE59] rounded-xl flex items-center justify-center">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Form Edit Kelas</h3>
                            <p class="text-sm text-gray-600">Update data kelas {{ $kelas->nama_kelas }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <form action="{{ route('kelas.update', $kelas) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Nama Kelas -->
                            <div>
                                <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>Nama Kelas</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 @error('nama_kelas') border-red-500 @enderror"
                                           placeholder="Contoh: X IPA 1" required maxlength="10">
                                </div>
                                @error('nama_kelas')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Format: [Tingkat] [Jurusan] [Nomor]</p>
                            </div>

                            <!-- Tingkat -->
                            <div>
                                <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>Tingkat Kelas</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="relative">
                                        <input type="radio" name="tingkat" value="10" class="hidden peer" {{ old('tingkat', $kelas->tingkat) == '10' ? 'checked' : '' }} required>
                                        <div class="p-4 border-2 border-gray-300 rounded-xl text-center cursor-pointer transition-all duration-200 peer-checked:border-[#1FAE59] peer-checked:bg-green-50 peer-checked:ring-2 peer-checked:ring-green-200 hover:border-gray-400">
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2 peer-checked:bg-[#1FAE59]">
                                                <span class="text-gray-600 font-bold peer-checked:text-white">10</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">Kelas X</span>
                                        </div>
                                    </label>

                                    <label class="relative">
                                        <input type="radio" name="tingkat" value="11" class="hidden peer" {{ old('tingkat', $kelas->tingkat) == '11' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-300 rounded-xl text-center cursor-pointer transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-2 peer-checked:ring-emerald-200 hover:border-gray-400">
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2 peer-checked:bg-emerald-500">
                                                <span class="text-gray-600 font-bold peer-checked:text-white">11</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">Kelas XI</span>
                                        </div>
                                    </label>

                                    <label class="relative">
                                        <input type="radio" name="tingkat" value="12" class="hidden peer" {{ old('tingkat', $kelas->tingkat) == '12' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-300 rounded-xl text-center cursor-pointer transition-all duration-200 peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:ring-2 peer-checked:ring-teal-200 hover:border-gray-400">
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2 peer-checked:bg-teal-500">
                                                <span class="text-gray-600 font-bold peer-checked:text-white">12</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">Kelas XII</span>
                                        </div>
                                    </label>
                                </div>
                                @error('tingkat')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Jurusan -->
                            <div>
                                <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>Jurusan</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-book text-gray-400"></i>
                                    </div>
                                    <input type="text" name="jurusan" id="jurusan" value="{{ old('jurusan', $kelas->jurusan) }}"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 @error('jurusan') border-red-500 @enderror"
                                           placeholder="Contoh: IPA, IPS, Bahasa" required>
                                </div>
                                @error('jurusan')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-eye mr-2 text-[#1FAE59]"></i>
                                Preview Kelas
                            </h4>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span class="text-green-600 font-bold" id="previewTingkat">{{ $kelas->tingkat }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900" id="previewNama">{{ $kelas->nama_kelas }}</p>
                                    <p class="text-xs text-gray-600" id="previewJurusan">{{ $kelas->jurusan }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('kelas.show', $kelas) }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center space-x-2">
                                <i class="fas fa-times"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit"
                                    class="bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                                <i class="fas fa-save"></i>
                                <span>Update Kelas</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaKelasInput = document.getElementById('nama_kelas');
            const tingkatInputs = document.querySelectorAll('input[name="tingkat"]');
            const jurusanInput = document.getElementById('jurusan');

            const previewNama = document.getElementById('previewNama');
            const previewTingkat = document.getElementById('previewTingkat');
            const previewJurusan = document.getElementById('previewJurusan');

            function updatePreview() {
                const tingkat = document.querySelector('input[name="tingkat"]:checked')?.value || '{{ $kelas->tingkat }}';
                const jurusan = jurusanInput.value || '{{ $kelas->jurusan }}';
                const namaKelas = namaKelasInput.value || '{{ $kelas->nama_kelas }}';

                previewNama.textContent = namaKelas;
                previewTingkat.textContent = tingkat;
                previewJurusan.textContent = jurusan;
            }

            namaKelasInput.addEventListener('input', updatePreview);
            jurusanInput.addEventListener('input', updatePreview);
            tingkatInputs.forEach(input => {
                input.addEventListener('change', updatePreview);
            });

            // Initial preview
            updatePreview();

            // Add character counter for nama_kelas
            namaKelasInput.addEventListener('input', function() {
                const counter = document.getElementById('nama-kelas-counter') || createCounter(namaKelasInput, 'nama-kelas-counter');
                counter.textContent = `${this.value.length}/10`;
            });

            function createCounter(input, id) {
                const counter = document.createElement('div');
                counter.id = id;
                counter.className = 'text-xs text-gray-500 mt-1 text-right';
                input.parentNode.appendChild(counter);
                return counter;
            }
        });
    </script>
</x-app-layout>
