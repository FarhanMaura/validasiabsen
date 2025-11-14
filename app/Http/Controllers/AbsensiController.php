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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Absensi::with('siswa.kelas')
            ->latest('tanggal')
            ->latest('waktu_masuk');

        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', today());
        }

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

        $totalHadir = 0;
        $totalTerlambat = 0;
        $totalTidakHadir = 0;

        foreach ($siswas as $siswa) {
            $siswa->hadirCount = 0;
            $siswa->terlambatCount = 0;
            $siswa->tidakHadirCount = 0;

            foreach ($siswa->absensi as $absensi) {
                if ($absensi->status_masuk === 'hadir') {
                    $totalHadir++;
                    $siswa->hadirCount++;
                } elseif ($absensi->status_masuk === 'terlambat') {
                    $totalTerlambat++;
                    $siswa->terlambatCount++;
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

    public function checkabsen(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required|string|max:255',
        ]);

        $rfidUid = $request->input('rfid_uid');
        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now();

        $jamTerlambatMasuk = config('app.jam_terlambat_masuk', '07:40:00');
        $jamPulangTepatWaktu = config('app.jam_pulang_tepat_waktu', '15:50:00');

        $siswa = Siswa::where('rfid_uid', $rfidUid)
                      ->where('status_aktif', true)
                      ->with('kelas')
                      ->first();

        if (!$siswa) {
            Log::warning('RFID Absen: RFID UID tidak terdaftar atau siswa tidak aktif.', ['rfid_uid' => $rfidUid]);
            return response()->json([
                'status' => 'error',
                'message' => 'RFID UID tidak terdaftar atau siswa tidak aktif.'
            ], 404);
        }

        $absensi = Absensi::where('siswa_id', $siswa->id)
                          ->where('tanggal', $today)
                          ->first();

        DB::beginTransaction();
        try {
            if (!$absensi) {
                $statusMasuk = ($currentTime->greaterThan(Carbon::parse($jamTerlambatMasuk))) ? 'terlambat' : 'hadir';

                $absensi = Absensi::create([
                    'siswa_id'    => $siswa->id,
                    'tanggal'     => $today,
                    'waktu_masuk' => $currentTime->toTimeString(),
                    'status_masuk' => $statusMasuk,
                    'keterangan'  => ($statusMasuk === 'terlambat') ? 'Terlambat masuk' : null,
                ]);

                DB::commit();
                Log::info('RFID Absen: Absensi masuk dicatat.', [
                    'siswa_id' => $siswa->id,
                    'nama' => $siswa->nama,
                    'waktu_masuk' => $absensi->waktu_masuk,
                    'status_masuk' => $absensi->status_masuk
                ]);

                $this->sendWhatsAppNotification('check_in', $siswa, $absensi);

                return response()->json([
                    'status'  => 'success',
                    'action'  => 'check_in',
                    'message' => 'Absensi masuk dicatat.',
                    'data'    => [
                        'nama_siswa'    => $siswa->nama,
                        'kelas'         => $siswa->kelas->nama_lengkap,
                        'waktu_masuk'   => $absensi->waktu_masuk,
                        'status_masuk'  => $absensi->status_masuk,
                        'tanggal'       => $absensi->tanggal,
                    ]
                ]);
            } else {
                if ($absensi->waktu_pulang === null) {
                    $statusPulang = ($currentTime->lessThan(Carbon::parse($jamPulangTepatWaktu))) ? 'cepat' : 'tepat_waktu';

                    $absensi->waktu_pulang = $currentTime->toTimeString();
                    $absensi->status_pulang = $statusPulang;
                    $absensi->save();

                    DB::commit();
                    Log::info('RFID Absen: Absensi pulang dicatat.', [
                        'siswa_id' => $siswa->id,
                        'nama' => $siswa->nama,
                        'waktu_pulang' => $absensi->waktu_pulang,
                        'status_pulang' => $absensi->status_pulang
                    ]);

                    $this->sendWhatsAppNotification('check_out', $siswa, $absensi);

                    return response()->json([
                        'status'  => 'success',
                        'action'  => 'check_out',
                        'message' => 'Absensi pulang dicatat.',
                        'data'    => [
                            'nama_siswa'     => $siswa->nama,
                            'kelas'          => $siswa->kelas->nama_lengkap,
                            'waktu_pulang'   => $absensi->waktu_pulang,
                            'status_pulang'  => $absensi->status_pulang,
                            'tanggal'        => $absensi->tanggal,
                        ]
                    ]);
                } else {
                    DB::rollBack();
                    Log::info('RFID Absen: Siswa sudah absen masuk dan pulang hari ini.', [
                        'siswa_id' => $siswa->id,
                        'nama' => $siswa->nama,
                        'tanggal' => $today
                    ]);
                    return response()->json([
                        'status'  => 'info',
                        'message' => 'Siswa sudah melakukan absensi masuk dan pulang hari ini.',
                        'data'    => [
                            'nama_siswa'     => $siswa->nama,
                            'kelas'          => $siswa->kelas->nama_lengkap,
                            'waktu_masuk'    => $absensi->waktu_masuk,
                            'status_masuk'   => $absensi->status_masuk,
                            'waktu_pulang'   => $absensi->waktu_pulang,
                            'status_pulang'  => $absensi->status_pulang,
                            'tanggal'        => $absensi->tanggal,
                        ]
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('RFID Absen: Error saat memproses absensi RFID: ' . $e->getMessage(), [
                'rfid_uid' => $rfidUid,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal server. Silakan coba lagi.'
            ], 500);
        }
    }

    private function sendWhatsAppNotification(string $type, Siswa $siswa, Absensi $absensi): void
    {
        $fonnteToken = config('app.fonnte_token');
        $phoneNumberOrtu = $siswa->no_telepon_ortu;

        if (!$fonnteToken) {
            Log::warning('WhatsApp Notification: Fonnte API Token tidak ditemukan di konfigurasi.');
            return;
        }
        if (!$phoneNumberOrtu) {
            Log::warning('WhatsApp Notification: Nomor telepon orang tua tidak ditemukan untuk siswa: ' . $siswa->nama);
            return;
        }

        if (substr($phoneNumberOrtu, 0, 1) === '0') {
            $phoneNumberOrtu = '62' . substr($phoneNumberOrtu, 1);
        }
        $phoneNumberOrtu = preg_replace('/[^0-9]/', '', $phoneNumberOrtu);

        $message = "";

        if ($type === 'check_in') {
            $status = ($absensi->status_masuk === 'terlambat') ? 'TERLAMBAT' : 'HADIR';
            // Menyederhanakan pesan untuk kompatibilitas dengan paket gratis
            $message = "Yth. Wali dari " . $siswa->nama . " (" . $siswa->kelas->nama_lengkap . "),";
            $message .= " Putra/putri Anda telah absen masuk pada tanggal " . Carbon::parse($absensi->tanggal)->translatedFormat('l, d F Y');
            $message .= " pukul " . Carbon::parse($absensi->waktu_masuk)->format('H:i') . " WIB dengan status " . $status . ".";
            $message .= " Terima kasih.";
        } elseif ($type === 'check_out') {
            $status = ($absensi->status_pulang === 'cepat') ? 'PULANG LEBIH CEPAT' : 'PULANG TEPAT WAKTU';
            // Menyederhanakan pesan untuk kompatibilitas dengan paket gratis
            $message = "Yth. Wali dari " . $siswa->nama . " (" . $siswa->kelas->nama_lengkap . "),";
            $message .= " Putra/putri Anda telah absen pulang pada tanggal " . Carbon::parse($absensi->tanggal)->translatedFormat('l, d F Y');
            $message .= " pukul " . Carbon::parse($absensi->waktu_pulang)->format('H:i') . " WIB dengan status " . $status . ".";
            $message .= " Terima kasih.";
        } else {
            Log::warning('WhatsApp Notification: Tipe notifikasi tidak dikenal: ' . $type);
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $fonnteToken,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $phoneNumberOrtu,
                'message' => $message,
                'countryCode' => '62',
            ]);

            Log::info('Fonnte API Request:', [
                'target' => $phoneNumberOrtu,
                'message' => $message,
                'headers' => ['Authorization' => '[TOKEN_HIDDEN]'],
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp Notification: Berhasil mengirim notifikasi untuk siswa ' . $siswa->nama, ['response' => $response->json()]);
            } else {
                Log::error('WhatsApp Notification: Gagal mengirim notifikasi untuk siswa ' . $siswa->nama, [
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                    'response_json' => $response->json(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Notification: Error saat mengirim notifikasi untuk siswa ' . $siswa->nama . ': ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
