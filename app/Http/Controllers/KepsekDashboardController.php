<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Services\AIRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KepsekDashboardController extends Controller
{
    private AIRecommendationService $aiService;

    public function __construct(AIRecommendationService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display principal dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $periode = Carbon::parse($bulan);

        // Basic statistics
        $totalSiswa = Siswa::aktif()->count();
        
        // Today's attendance
        $hadirHariIni = Absensi::whereDate('tanggal', today())
            ->whereIn('status_masuk', ['hadir', 'terlambat'])
            ->count();

        // Monthly attendance statistics
        $absensiQuery = Absensi::whereMonth('tanggal', $periode->month)
            ->whereYear('tanggal', $periode->year);

        $totalHadirBulan = (clone $absensiQuery)->where('status_masuk', 'hadir')->count();
        $totalTerlambatBulan = (clone $absensiQuery)->where('status_masuk', 'terlambat')->count();
        $totalIzinBulan = (clone $absensiQuery)->where('status_masuk', 'izin')->count();
        $totalSakitBulan = (clone $absensiQuery)->where('status_masuk', 'sakit')->count();
        $totalAlfaBulan = (clone $absensiQuery)->where('status_masuk', 'alfa')->count();

        // Calculate average attendance rate
        $jumlahHariSekolah = $this->getSchoolDaysInMonth($periode);
        $totalKehadiranDiharapkan = $totalSiswa * $jumlahHariSekolah;
        $rataRataKehadiran = $totalKehadiranDiharapkan > 0 
            ? round((($totalHadirBulan + $totalTerlambatBulan) / $totalKehadiranDiharapkan) * 100, 1)
            : 0;

        // Get frequently late students (3+ times in the month)
        $siswaSringTerlambat = $this->getFrequentlyLateStudents($periode);

        // Get attendance trend for last 30 days
        $trendKehadiran = $this->getAttendanceTrend();

        // Get statistics per class
        $kelasStats = $this->getClassStatistics($periode);

        return view('kepsek.dashboard', compact(
            'totalSiswa',
            'hadirHariIni',
            'rataRataKehadiran',
            'totalTerlambatBulan',
            'totalHadirBulan',
            'totalIzinBulan',
            'totalSakitBulan',
            'totalAlfaBulan',
            'siswaSringTerlambat',
            'trendKehadiran',
            'kelasStats',
            'bulan'
        ));
    }

    /**
     * Get AI recommendations
     */
    public function getAIRecommendation(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $periode = Carbon::parse($bulan);

        // Prepare data for AI
        $data = [
            'total_siswa' => Siswa::aktif()->count(),
            'hadir_hari_ini' => Absensi::whereDate('tanggal', today())
                ->whereIn('status_masuk', ['hadir', 'terlambat'])
                ->count(),
            'rata_rata_kehadiran' => $this->calculateAttendanceRate($periode),
            'total_terlambat' => Absensi::whereMonth('tanggal', $periode->month)
                ->whereYear('tanggal', $periode->year)
                ->where('status_masuk', 'terlambat')
                ->count(),
            'siswa_sering_terlambat' => $this->getFrequentlyLateStudents($periode)->map(function ($siswa) {
                return [
                    'nama' => $siswa->nama,
                    'kelas' => $siswa->kelas->nama_lengkap,
                    'jumlah_terlambat' => $siswa->jumlah_terlambat,
                ];
            })->toArray(),
            'kelas_stats' => $this->getClassStatistics($periode)->map(function ($kelas) {
                return [
                    'nama_kelas' => $kelas->nama_lengkap,
                    'persentase_hadir' => $kelas->persentase_hadir,
                    'total_terlambat' => $kelas->total_terlambat,
                ];
            })->toArray(),
        ];

        $result = $this->aiService->generateRecommendations($data);

        return response()->json($result);
    }

    /**
     * Get frequently late students (3+ times in a month)
     */
    private function getFrequentlyLateStudents(Carbon $periode)
    {
        return Siswa::select('siswas.*', DB::raw('COUNT(absensis.id) as jumlah_terlambat'))
            ->join('absensis', 'siswas.id', '=', 'absensis.siswa_id')
            ->where('siswas.status_aktif', true)
            ->where('absensis.status_masuk', 'terlambat')
            ->whereMonth('absensis.tanggal', $periode->month)
            ->whereYear('absensis.tanggal', $periode->year)
            ->with('kelas')
            ->groupBy('siswas.id')
            ->having('jumlah_terlambat', '>=', 3)
            ->orderByDesc('jumlah_terlambat')
            ->get();
    }

    /**
     * Get attendance trend for last 30 days
     */
    private function getAttendanceTrend()
    {
        $trends = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            $hadir = Absensi::whereDate('tanggal', $date)
                ->where('status_masuk', 'hadir')
                ->count();
                
            $terlambat = Absensi::whereDate('tanggal', $date)
                ->where('status_masuk', 'terlambat')
                ->count();
                
            $tidakHadir = Absensi::whereDate('tanggal', $date)
                ->whereIn('status_masuk', ['alfa', 'izin', 'sakit'])
                ->count();

            $trends[] = [
                'tanggal' => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'tidak_hadir' => $tidakHadir,
            ];
        }

        return $trends;
    }

    /**
     * Get statistics per class
     */
    private function getClassStatistics(Carbon $periode)
    {
        $kelas = Kelas::withCount(['siswa' => function ($query) {
            $query->where('status_aktif', true);
        }])->get();

        return $kelas->map(function ($kelasItem) use ($periode) {
            $jumlahSiswa = $kelasItem->siswa_count;
            
            $hadir = Absensi::whereHas('siswa', function ($q) use ($kelasItem) {
                $q->where('kelas_id', $kelasItem->id);
            })
            ->whereMonth('tanggal', $periode->month)
            ->whereYear('tanggal', $periode->year)
            ->where('status_masuk', 'hadir')
            ->count();

            $terlambat = Absensi::whereHas('siswa', function ($q) use ($kelasItem) {
                $q->where('kelas_id', $kelasItem->id);
            })
            ->whereMonth('tanggal', $periode->month)
            ->whereYear('tanggal', $periode->year)
            ->where('status_masuk', 'terlambat')
            ->count();

            $jumlahHariSekolah = $this->getSchoolDaysInMonth($periode);
            $totalKehadiranDiharapkan = $jumlahSiswa * $jumlahHariSekolah;
            
            $kelasItem->total_hadir = $hadir;
            $kelasItem->total_terlambat = $terlambat;
            $kelasItem->persentase_hadir = $totalKehadiranDiharapkan > 0
                ? round((($hadir + $terlambat) / $totalKehadiranDiharapkan) * 100, 1)
                : 0;

            return $kelasItem;
        })->sortByDesc('persentase_hadir');
    }

    /**
     * Calculate attendance rate for a period
     */
    private function calculateAttendanceRate(Carbon $periode): float
    {
        $totalSiswa = Siswa::aktif()->count();
        $jumlahHariSekolah = $this->getSchoolDaysInMonth($periode);
        
        $totalHadir = Absensi::whereMonth('tanggal', $periode->month)
            ->whereYear('tanggal', $periode->year)
            ->where('status_masuk', 'hadir')
            ->count();

        $totalTerlambat = Absensi::whereMonth('tanggal', $periode->month)
            ->whereYear('tanggal', $periode->year)
            ->where('status_masuk', 'terlambat')
            ->count();

        $totalKehadiranDiharapkan = $totalSiswa * $jumlahHariSekolah;
        
        return $totalKehadiranDiharapkan > 0
            ? round((($totalHadir + $totalTerlambat) / $totalKehadiranDiharapkan) * 100, 1)
            : 0;
    }

    /**
     * Get number of school days in a month (excluding weekends)
     */
    private function getSchoolDaysInMonth(Carbon $periode): int
    {
        $start = $periode->copy()->startOfMonth();
        $end = $periode->copy()->endOfMonth();
        
        // If current month, only count until today
        if ($periode->isCurrentMonth()) {
            $end = now();
        }

        $days = 0;
        while ($start->lte($end)) {
            // Count weekdays only (Monday-Friday)
            if ($start->isWeekday()) {
                $days++;
            }
            $start->addDay();
        }

        return $days;
    }
}
