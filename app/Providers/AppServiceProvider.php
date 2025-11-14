<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyCsrfToken::except([
            'api/absensi/check', // URI API lengkap Anda
            // Anda juga bisa menggunakan wildcard jika ingin mengecualikan semua rute di bawah '/api/absensi/'
            // 'api/absensi/*',
        ]);    }
}
