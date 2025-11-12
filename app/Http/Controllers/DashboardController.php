<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::aktif()->count();
        $totalKelas = Kelas::count();

        // Data untuk grafik absensi hari ini
        $absensiHariIni = Absensi::hariIni()
            ->select('status_masuk', DB::raw('count(*) as total'))
            ->groupBy('status_masuk')
            ->get()
            ->pluck('total', 'status_masuk');

        $hadirHariIni = $absensiHariIni['hadir'] ?? 0;
        $terlambatHariIni = $absensiHariIni['terlambat'] ?? 0;
        $tidakHadirHariIni = $totalSiswa - $hadirHariIni - $terlambatHariIni;

        // Data untuk grafik absensi bulan ini
        $absensiBulanIni = Absensi::bulanIni()
            ->select('status_masuk', DB::raw('count(*) as total'))
            ->groupBy('status_masuk')
            ->get()
            ->pluck('total', 'status_masuk');

        $hadirBulanIni = $absensiBulanIni['hadir'] ?? 0;
        $terlambatBulanIni = $absensiBulanIni['terlambat'] ?? 0;

        return view('dashboard', compact(
            'totalSiswa',
            'totalKelas',
            'hadirHariIni',
            'terlambatHariIni',
            'tidakHadirHariIni',
            'hadirBulanIni',
            'terlambatBulanIni'
        ));
    }
}
