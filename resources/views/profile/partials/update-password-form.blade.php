<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div class="space-y-4">
        <!-- Current Password -->
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <i class="fas fa-key text-gray-400 mr-2"></i>
                Kata Sandi Saat Ini
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" name="current_password" id="current_password"
                       autocomplete="current-password"
                       class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 @error('current_password') border-red-500 @enderror"
                       placeholder="Masukkan kata sandi saat ini">
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password" data-target="current_password">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>
            @error('current_password')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <i class="fas fa-lock text-gray-400 mr-2"></i>
                Kata Sandi Baru
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-key text-gray-400"></i>
                </div>
                <input type="password" name="password" id="password"
                       autocomplete="new-password"
                       class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200 @error('password') border-red-500 @enderror"
                       placeholder="Masukkan kata sandi baru">
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password" data-target="password">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <i class="fas fa-lock text-gray-400 mr-2"></i>
                Konfirmasi Kata Sandi Baru
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-shield-alt text-gray-400"></i>
                </div>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       autocomplete="new-password"
                       class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1FAE59] focus:border-[#1FAE59] transition-colors duration-200"
                       placeholder="Konfirmasi kata sandi baru">
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password" data-target="password_confirmation">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex items-center justify-end space-x-3">
        @if (session('status') === 'password-updated')
            <p class="text-sm text-green-600 flex items-center">
                <i class="fas fa-check-circle mr-1"></i>Kata sandi berhasil diperbarui
            </p>
        @endif

        <button type="submit"
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl flex items-center space-x-2">
            <i class="fas fa-sync-alt"></i>
            <span>Perbarui Kata Sandi</span>
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                targetInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye text-gray-400"></i>' : '<i class="fas fa-eye-slash text-gray-400"></i>';
            });
        });
    });
</script>
