<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Edit User</h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Edit data {{ $user->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-2xl">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Form Edit User</h3>
                </div>

                <div class="p-4 sm:p-6">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200"
                                       required autofocus placeholder="Masukkan nama lengkap">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200"
                                       required placeholder="Masukkan alamat email">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select id="role" name="role"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200">
                                    <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                                    <option value="tu" {{ old('role', $user->role) == 'tu' ? 'selected' : '' }}>Tata Usaha</option>
                                </select>
                            </div>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-gray-500 font-normal">(Isi jika ingin mengubah)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" name="password" type="password"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200"
                                       placeholder="Kosongkan jika tidak ingin mengubah">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200"
                                       placeholder="Konfirmasi password baru">
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('users.index') }}"
                               class="w-full sm:w-auto text-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                Batal
                            </a>
                            <button type="submit"
                                    class="w-full sm:w-auto bg-[#1FAE59] hover:bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                                <i class="fas fa-save"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
