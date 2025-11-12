<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek jika user tidak login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Debug info
        \Log::info('Role Check', [
            'user_role' => $user->role,
            'required_role' => $role,
            'path' => $request->path()
        ]);

        // Jika user role sesuai dengan yang diizinkan, lanjutkan
        if ($user->role === $role) {
            return $next($request);
        }

        // Jika Kepsek mencoba akses halaman TU, redirect ke dashboard dengan error
        if ($user->role === 'kepsek') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // Jika TU mencoba akses yang tidak diizinkan
        abort(403, 'Unauthorized action. Anda tidak memiliki akses ke halaman ini.');
    }
}
