<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Data Siswa</h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi data siswa</p>
            </div>
            <a href="{{ route('siswa.show', $siswa) }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center space-x-2">
                <i class="fas fa-arrow-left text-sm"></i>
                <span>Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Form Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-edit text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Form Edit Siswa</h3>
                            <p class="text-sm text-gray-600">Update data siswa {{ $siswa->nama }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <form action="{{ route('siswa.update', $siswa) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NISN -->
                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>NISN</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $siswa->nisn) }}"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('nisn') border-red-500 @enderror"
                                           placeholder="Masukkan NISN" required maxlength="10">
                                </div>
                                @error('nisn')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>Nama Lengkap</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama', $siswa->nama) }}"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('nama') border-red-500 @enderror"
                                           placeholder="Masukkan nama lengkap" required>
                                </div>
                                @error('nama')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Kelas -->
                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>Kelas</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-door-open text-gray-400"></i>
                                    </div>
                                    <select name="kelas_id" id="kelas_id"
                                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('kelas_id') border-red-500 @enderror"
                                            required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $item)
                                            <option value="{{ $item->id }}" {{ old('kelas_id', $siswa->kelas_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_lengkap }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('kelas_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- No Telepon Ortu -->
                            <div>
                                <label for="no_telepon_ortu" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>No. Telepon Orang Tua</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" name="no_telepon_ortu" id="no_telepon_ortu" value="{{ old('no_telepon_ortu', $siswa->no_telepon_ortu) }}"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('no_telepon_ortu') border-red-500 @enderror"
                                           placeholder="Masukkan nomor telepon" required>
                                </div>
                                @error('no_telepon_ortu')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- RFID UID -->
                            <div>
                                <label for="rfid_uid" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span>RFID UID</span>
                                    <span class="text-gray-400 text-xs ml-1">(Opsional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="rfid_uid" id="rfid_uid" value="{{ old('rfid_uid', $siswa->rfid_uid) }}"
                                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('rfid_uid') border-red-500 @enderror"
                                           placeholder="Masukkan RFID UID">
                                </div>
                                @error('rfid_uid')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Status Aktif -->
                            <div>
                                <label for="status_aktif" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status Aktif
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-toggle-on text-gray-400"></i>
                                    </div>
                                    <select name="status_aktif" id="status_aktif"
                                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                        <option value="1" {{ old('status_aktif', $siswa->status_aktif) ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ !old('status_aktif', $siswa->status_aktif) ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>Alamat</span>
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400 mt-0.5"></i>
                                    </div>
                                    <textarea name="alamat" id="alamat" rows="4"
                                              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('alamat') border-red-500 @enderror"
                                              placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $siswa->alamat) }}</textarea>
                                </div>
                                @error('alamat')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('siswa.show', $siswa) }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center space-x-2">
                                <i class="fas fa-times"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
                                <i class="fas fa-save"></i>
                                <span>Update Data Siswa</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add real-time validation feedback
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '' && this.hasAttribute('required')) {
                        this.classList.add('border-red-300');
                    } else {
                        this.classList.remove('border-red-300');
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('border-red-300');
                    }
                });
            });

            // Add character counter for NISN
            const nisnInput = document.getElementById('nisn');
            if (nisnInput) {
                nisnInput.addEventListener('input', function() {
                    const counter = document.getElementById('nisn-counter') || createCounter(nisnInput, 'nisn-counter');
                    counter.textContent = `${this.value.length}/10`;
                });
            }

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
