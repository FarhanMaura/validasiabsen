<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Pengaturan Profil</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola informasi akun dan keamanan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl space-y-6">
            <!-- Update Profile Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#1FAE59] rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-edit text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
                            <p class="text-sm text-gray-600">Perbarui informasi profil dan email Anda</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-lock text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Ubah Kata Sandi</h3>
                            <p class="text-sm text-gray-600">Pastikan kata sandi Anda kuat dan aman</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Hapus Akun</h3>
                            <p class="text-sm text-gray-600">Hapus permanen akun dan semua data Anda</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-section {
            transition: all 0.3s ease;
        }

        .profile-section:hover {
            transform: translateY(-2px);
        }
    </style>
</x-app-layout>
