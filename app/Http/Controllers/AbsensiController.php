<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Absensi::with('siswa.kelas')
            ->latest('tanggal')
            ->latest('waktu_masuk');

        // Filter by date
        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', today());
        }

        // Filter by kelas
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $absensi = $query->paginate(20);
        $kelas = Kelas::all();

        return view('absensi.index', compact('absensi', 'kelas'));
    }

    public function rekap(Request $request): View
    {
        $query = Siswa::with(['kelas', 'absensi' => function ($q) use ($request) {
            if ($request->has('bulan') && $request->bulan) {
                $q->whereMonth('tanggal', Carbon::parse($request->bulan)->month)
                  ->whereYear('tanggal', Carbon::parse($request->bulan)->year);
            } else {
                $q->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
            }
        }]);

        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $siswas = $query->paginate(50);
        $kelas = Kelas::all();

        // Hitung statistik
        $totalHadir = 0;
        $totalTerlambat = 0;
        $totalTidakHadir = 0;

        foreach ($siswas as $siswa) {
            foreach ($siswa->absensi as $absensi) {
                if ($absensi->status_masuk === 'hadir') {
                    $totalHadir++;
                } elseif ($absensi->status_masuk === 'terlambat') {
                    $totalTerlambat++;
                } else {
                    $totalTidakHadir++;
                }
            }
        }

        return view('absensi.rekap', compact('siswas', 'kelas', 'totalHadir', 'totalTerlambat', 'totalTidakHadir'));
    }

    public function export(Request $request): Response
    {
        $absensi = Absensi::with('siswa.kelas')
            ->when($request->has('start_date') && $request->start_date, function ($q) use ($request) {
                $q->whereDate('tanggal', '>=', $request->start_date);
            })
            ->when($request->has('end_date') && $request->end_date, function ($q) use ($request) {
                $q->whereDate('tanggal', '<=', $request->end_date);
            })
            ->when($request->has('kelas_id') && $request->kelas_id, function ($q) use ($request) {
                $q->whereHas('siswa', function ($q2) use ($request) {
                    $q2->where('kelas_id', $request->kelas_id);
                });
            })
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rekap_absensi_' . date('Y-m-d') . '.csv"',
        ];

        $csv = "Tanggal,NISN,Nama Siswa,Kelas,Waktu Masuk,Status Masuk,Waktu Pulang,Status Pulang,Keterangan\n";

        foreach ($absensi as $item) {
            $csv .= "\"{$item->tanggal}\","
                  . "\"{$item->siswa->nisn}\","
                  . "\"{$item->siswa->nama}\","
                  . "\"{$item->siswa->kelas->nama_lengkap}\","
                  . "\"{$item->waktu_masuk}\","
                  . "\"{$item->status_masuk}\","
                  . "\"{$item->waktu_pulang}\","
                  . "\"{$item->status_pulang}\","
                  . "\"{$item->keterangan}\"\n";
        }

        return response($csv, 200, $headers);
    }
}
