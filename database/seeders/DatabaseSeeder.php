<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pengaturan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users
        User::create([
            'name' => 'Admin TU',
            'email' => 'tu@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'tu',
        ]);

        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'kepsek',
        ]);

        // Create Kelas
        $kelasData = [
            ['nama_kelas' => 'A', 'tingkat' => '10', 'jurusan' => 'IPA'],
            ['nama_kelas' => 'B', 'tingkat' => '10', 'jurusan' => 'IPA'],
            ['nama_kelas' => 'A', 'tingkat' => '10', 'jurusan' => 'IPS'],
            ['nama_kelas' => 'A', 'tingkat' => '11', 'jurusan' => 'IPA'],
            ['nama_kelas' => 'B', 'tingkat' => '11', 'jurusan' => 'IPA'],
            ['nama_kelas' => 'A', 'tingkat' => '11', 'jurusan' => 'IPS'],
            ['nama_kelas' => 'A', 'tingkat' => '12', 'jurusan' => 'IPA'],
            ['nama_kelas' => 'B', 'tingkat' => '12', 'jurusan' => 'IPA'],
            ['nama_kelas' => 'A', 'tingkat' => '12', 'jurusan' => 'IPS'],
        ];

        foreach ($kelasData as $kelas) {
            Kelas::create($kelas);
        }

        // Create Sample Siswa
        $siswaData = [
            [
                'nisn' => '1234567890',
                'nama' => 'Ahmad Fauzi',
                'kelas_id' => 1,
                'no_telepon_ortu' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123',
                'rfid_uid' => 'RFID001',
                'status_aktif' => true,
            ],
            [
                'nisn' => '0987654321',
                'nama' => 'Siti Rahma',
                'kelas_id' => 1,
                'no_telepon_ortu' => '081298765432',
                'alamat' => 'Jl. Jenderal Sudirman No. 45',
                'rfid_uid' => 'RFID002',
                'status_aktif' => true,
            ],
            [
                'nisn' => '1122334455',
                'nama' => 'Budi Santoso',
                'kelas_id' => 2,
                'no_telepon_ortu' => '081112223344',
                'alamat' => 'Jl. Pahlawan No. 67',
                'rfid_uid' => 'RFID003',
                'status_aktif' => true,
            ],
        ];

        foreach ($siswaData as $siswa) {
            Siswa::create($siswa);
        }

        // Create Pengaturan
        Pengaturan::create([
            'key' => 'waktu_toleransi_terlambat',
            'value' => '07:30',
            'deskripsi' => 'Waktu toleransi keterlambatan (format: HH:MM)',
        ]);

        Pengaturan::create([
            'key' => 'pesan_wa_hadir',
            'value' => 'Anak Anda {nama} telah hadir di sekolah pada {waktu}. Terima kasih.',
            'deskripsi' => 'Template pesan WhatsApp untuk kehadiran',
        ]);

        Pengaturan::create([
            'key' => 'pesan_wa_terlambat',
            'value' => 'Anak Anda {nama} terlambat hadir di sekolah pada {waktu}. Harap diperhatikan.',
            'deskripsi' => 'Template pesan WhatsApp untuk keterlambatan',
        ]);
    }
}
