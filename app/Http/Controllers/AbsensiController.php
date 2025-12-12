<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pengaturan;
use App\Models\RfidScan;
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

        if ($request->has('status_masuk') && $request->status_masuk) {
            $query->where('status_masuk', $request->status_masuk);
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
            // Log RFID scan for unregistered cards
            RfidScan::create([
                'rfid_uid' => $rfidUid,
                'siswa_id' => null,
                'status' => 'unregistered',
                'scanned_at' => $currentTime,
            ]);

            Log::warning('RFID Absen: RFID UID tidak terdaftar atau siswa tidak aktif.', ['rfid_uid' => $rfidUid]);
            return response()->json([
                'status' => 'error',
                'message' => 'RFID UID tidak terdaftar atau siswa tidak aktif.',
                'rfid_uid' => $rfidUid
            ], 404);
        }

        // DOUBLE-TAP PROTECTION: Check if last scan was within 2 seconds
        $lastScan = cache()->get("rfid_scan_{$siswa->id}");
        if ($lastScan && $currentTime->diffInSeconds($lastScan) < 2) {
            return response()->json([
                'status' => 'info',
                'message' => 'Scan terlalu cepat. Tunggu beberapa detik.',
            ], 429);
        }

        DB::beginTransaction();
        try {
            // Cek apakah siswa sudah absen hari ini dengan row lock untuk mencegah race condition
            $absensi = Absensi::where('siswa_id', $siswa->id)
                              ->where('tanggal', $today)
                              ->lockForUpdate()
                              ->first();

            if (!$absensi) {
                // Belum ada absensi hari ini - MASUK
                $statusMasuk = ($currentTime->greaterThan(Carbon::parse($jamTerlambatMasuk))) ? 'terlambat' : 'hadir';

                $absensi = Absensi::create([
                    'siswa_id'    => $siswa->id,
                    'tanggal'     => $today,
                    'waktu_masuk' => $currentTime->toTimeString(),
                    'status_masuk' => $statusMasuk,
                    'keterangan'  => ($statusMasuk === 'terlambat') ? 'Terlambat masuk' : null,
                ]);

                // Log RFID scan for registered cards
                RfidScan::create([
                    'rfid_uid' => $rfidUid,
                    'siswa_id' => $siswa->id,
                    'status' => 'registered',
                    'scanned_at' => $currentTime,
                ]);

                // Set cache to prevent double-tap
                cache()->put("rfid_scan_{$siswa->id}", $currentTime, now()->addSeconds(3));

                DB::commit();
                Log::info('RFID Absen: Absensi masuk dicatat.', [
                    'siswa_id' => $siswa->id,
                    'nama' => $siswa->nama,
                    'waktu_masuk' => $absensi->waktu_masuk,
                    'status_masuk' => $absensi->status_masuk
                ]);
                
                // Send notification
                $this->sendWhatsAppNotification('check_in', $siswa, $absensi);

                return response()->json([
                    'status'  => 'success',
                    'action'  => 'check_in',
                    'message' => 'Absensi masuk dicatat.',
                    'data'    => [
                        'nama'          => $siswa->nama,
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

                    // Log RFID scan for check-out
                    RfidScan::create([
                        'rfid_uid' => $rfidUid,
                        'siswa_id' => $siswa->id,
                        'status' => 'registered',
                        'scanned_at' => $currentTime,
                    ]);

                    // Set cache to prevent double-tap
                    cache()->put("rfid_scan_{$siswa->id}", $currentTime, now()->addSeconds(3));

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
                            'nama'           => $siswa->nama,
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
                            'nama'           => $siswa->nama,
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

    public function scan(): View
    {
        return view('absensi.scan');
    }

    public function rfidChecker(): View
    {
        return view('rfid.checker');
    }

    /**
     * Get latest RFID scans for polling
     */
    public function getLatestRfidScans(Request $request)
    {
        // Clean old records first
        RfidScan::cleanOldRecords();

        // Get scans from last 5 minutes, limited to 10 most recent
        $scans = RfidScan::with('siswa.kelas')
            ->recent(5)
            ->orderBy('scanned_at', 'desc')
            ->limit(10)
            ->get();

        $formattedScans = $scans->map(function ($scan) {
            $data = [
                'rfid_uid' => $scan->rfid_uid,
                'scanned_at' => $scan->scanned_at->format('Y-m-d H:i:s'),
                'status' => $scan->status === 'registered' ? 'success' : 'error',
            ];

            if ($scan->siswa) {
                $data['data'] = [
                    'nama' => $scan->siswa->nama,
                    'kelas' => $scan->siswa->kelas->nama_lengkap ?? '-',
                    'status_masuk' => 'Terdaftar',
                ];
            } else {
                $data['message'] = 'RFID UID tidak terdaftar';
            }

            return $data;
        });

        return response()->json([
            'status' => 'success',
            'scans' => $formattedScans,
        ]);
    }

    public function checkBarcode(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
        ]);

        $nisn = $request->input('nisn');
        
        $siswa = Siswa::where('nisn', $nisn)->where('status_aktif', true)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan atau tidak aktif.',
            ], 404);
        }

        $result = $this->processAttendance($siswa);

        if ($result['status'] === 'error') {
            return response()->json($result, 500);
        }

        return response()->json($result);
    }

    public function processAttendance(Siswa $siswa)
    {
        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now();
        
        // Ambil pengaturan waktu
        $toleransiTerlambat = Pengaturan::getValue('waktu_toleransi_terlambat') ?? '07:30';

        DB::beginTransaction();
        try {
            // Cek absensi hari ini dengan row lock untuk mencegah race condition
            $absensi = Absensi::where('siswa_id', $siswa->id)
                              ->where('tanggal', $today)
                              ->lockForUpdate()
                              ->first();

            if (!$absensi) {
                // Check-in (Datang)
                $statusMasuk = $currentTime->format('H:i') <= $toleransiTerlambat ? 'hadir' : 'terlambat';

                $absensi = Absensi::create([
                    'siswa_id' => $siswa->id,
                    'tanggal' => $today,
                    'waktu_masuk' => $currentTime->toTimeString(),
                    'status_masuk' => $statusMasuk,
                ]);

                $this->sendWhatsAppNotification('check_in', $siswa, $absensi);

                DB::commit();
                return [
                    'status' => 'success',
                    'message' => 'Absen masuk berhasil dicatat.',
                    'data' => [
                        'nama' => $siswa->nama,
                        'kelas' => $siswa->kelas->nama_lengkap,
                        'waktu' => $currentTime->format('H:i:s'),
                        'status' => ucfirst($statusMasuk),
                        'type' => 'check_in'
                    ]
                ];
            } else {
                // Sudah ada record absensi
                if ($absensi->waktu_pulang) {
                    DB::rollBack();
                    return [
                        'status' => 'info',
                        'message' => 'Siswa sudah melakukan absen pulang hari ini.',
                        'data' => [
                            'nama' => $siswa->nama,
                            'kelas' => $siswa->kelas->nama_lengkap,
                            'waktu_masuk' => $absensi->waktu_masuk,
                            'status_masuk' => $absensi->status_masuk,
                            'waktu_pulang' => $absensi->waktu_pulang,
                            'status_pulang' => $absensi->status_pulang,
                        ]
                    ];
                }

                // Check-out (Pulang)
                $statusPulang = 'tepat_waktu'; // Logic status pulang bisa diperbaiki nanti

                $absensi->update([
                    'waktu_pulang' => $currentTime->toTimeString(),
                    'status_pulang' => $statusPulang,
                ]);

                $this->sendWhatsAppNotification('check_out', $siswa, $absensi);

                DB::commit();
                return [
                    'status' => 'success',
                    'message' => 'Absen pulang berhasil dicatat.',
                    'data' => [
                        'nama' => $siswa->nama,
                        'kelas' => $siswa->kelas->nama_lengkap,
                        'waktu' => $currentTime->format('H:i:s'),
                        'status' => ucfirst($statusPulang),
                        'type' => 'check_out'
                    ]
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Barcode Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem.',
            ];
        }
    }

    public function createManual()
    {
        $siswas = Siswa::where('status_aktif', true)
            ->with('kelas')
            ->orderBy('nama')
            ->get();
        return view('absensi.create_manual', compact('siswas'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:izin,sakit,alfa,hadir,terlambat',
            'keterangan' => 'nullable|string',
        ]);

        $siswa = Siswa::find($request->siswa_id);
        
        Absensi::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'status_masuk' => $request->status,
                'waktu_masuk' => $request->status == 'hadir' || $request->status == 'terlambat' ? Carbon::now()->toTimeString() : null,
                'keterangan' => $request->keterangan,
            ]
        );

        return redirect()->route('absensi.index')->with('success', 'Absensi manual berhasil disimpan.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil dihapus.');
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
