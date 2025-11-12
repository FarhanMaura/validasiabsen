<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PengaturanController extends Controller
{
    public function index(): View
    {
        $pengaturan = Pengaturan::all()->keyBy('key');
        return view('pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'waktu_toleransi_terlambat' => 'required|date_format:H:i',
            'pesan_wa_hadir' => 'required|string',
            'pesan_wa_terlambat' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            Pengaturan::setValue($key, $value);
        }

        return redirect()->route('pengaturan.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
