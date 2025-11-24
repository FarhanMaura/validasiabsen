<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Absensi Manual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('absensi.storeManual') }}">
                        @csrf

                        <!-- Siswa -->
                        <div>
                            <x-input-label for="siswa_id" :value="__('Siswa')" />
                            <select id="siswa_id" name="siswa_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm select2" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->id }}">{{ $siswa->nama }} - {{ $siswa->kelas->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('siswa_id')" class="mt-2" />
                        </div>

                        <!-- Tanggal -->
                        <div class="mt-4">
                            <x-input-label for="tanggal" :value="__('Tanggal')" />
                            <x-text-input id="tanggal" class="block mt-1 w-full" type="date" name="tanggal" :value="date('Y-m-d')" required />
                            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="hadir">Hadir</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="alfa">Alfa</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Keterangan -->
                        <div class="mt-4">
                            <x-input-label for="keterangan" :value="__('Keterangan')" />
                            <textarea id="keterangan" name="keterangan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
