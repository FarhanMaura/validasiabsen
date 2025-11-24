<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;

use App\Http\Controllers\AbsensiController;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $siswas = Siswa::with('kelas')
            ->latest()
            ->filter(request(['search']))
            ->paginate(10);

        return view('siswa.index', compact('siswas'));
    }

    public function create(): View
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => 'required|unique:siswas,nisn|digits:10',
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'no_telepon_ortu' => 'required|string|max:15',
            'alamat' => 'required|string',
            'rfid_uid' => 'nullable|unique:siswas,rfid_uid|string|max:50',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa): View
    {
        $siswa->load('kelas', 'absensi');
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa): View
    {
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => 'required|digits:10|unique:siswas,nisn,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'no_telepon_ortu' => 'required|string|max:15',
            'alamat' => 'required|string',
            'rfid_uid' => 'nullable|string|max:50|unique:siswas,rfid_uid,' . $siswa->id,
            'status_aktif' => 'required|boolean',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function card(Siswa $siswa)
    {
        return view('siswa.card', compact('siswa'));
    }

    public function publicProfile($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();

        // Otomatis catat absensi karena route sudah diproteksi untuk Guru
        $absensiController = new AbsensiController();
        $result = $absensiController->processAttendance($siswa);
        
        return view('siswa.public', compact('siswa', 'result'));
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diimport.');
    }

    public function exportTemplate(): Response
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
        ];

        $template = "nisn,nama,kelas_id,no_telepon_ortu,alamat,rfid_uid\n".
                   "1234567890,John Doe,1,081234567890,Jl. Contoh No. 123,RFID123\n".
                   "0987654321,Jane Smith,2,081298765432,Jl. Sample No. 456,RFID456";

        return response($template, 200, $headers);
    }
}
