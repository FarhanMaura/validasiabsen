<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div class="space-y-4">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <i class="fas fa-user text-gray-400 mr-2"></i>
                Nama Lengkap
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-id-card text-gray-400"></i>
                </div>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       autofocus
                       autocomplete="name"
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 @error('name') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap">
            </div>
            @error('name')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                Alamat Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-at text-gray-400"></i>
                </div>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $user->email) }}"
                       required
                       autocomplete="email"
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 @error('email') border-red-500 @enderror"
                       placeholder="Masukkan alamat email">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex items-center justify-end space-x-3">
        @if (session('status') === 'profile-updated')
            <p class="text-sm text-green-600 flex items-center">
                <i class="fas fa-check-circle mr-1"></i>Profil berhasil diperbarui
            </p>
        @endif

        <button type="submit"
                class="bg-gradient-to-r from-[#1FAE59] to-green-500 hover:from-green-500 hover:to-green-600 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
            <i class="fas fa-save"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>
