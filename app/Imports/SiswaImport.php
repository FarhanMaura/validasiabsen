<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    private $importedCount = 0;
    private $updatedCount = 0;

    public function model(array $row)
    {
        \Log::info('🔄 PROCESSING ROW:', $row);

        // Debug: log semua headers yang tersedia
        \Log::info('📋 AVAILABLE HEADERS:', array_keys($row));

        // Normalize header names - coba semua kemungkinan
        $nisn = $row['nisn'] ?? $row['nisn'] ?? null;
        $nama = $row['nama'] ?? $row['nama_siswa'] ?? $row['name'] ?? null;
        $kelasName = $row['kelas'] ?? $row['kelas_id'] ?? $row['class'] ?? null;
        $noTelepon = $row['no_telepon_ortu'] ?? $row['no_telepon'] ?? $row['telepon'] ?? $row['phone'] ?? null;
        $alamat = $row['alamat'] ?? $row['address'] ?? '-';
        $rfidUid = $row['rfid_uid'] ?? $row['rfid'] ?? null;

        \Log::info('🎯 NORMALIZED DATA:', [
            'nisn' => $nisn,
            'nama' => $nama,
            'kelas' => $kelasName,
            'no_telepon' => $noTelepon,
            'alamat' => $alamat,
            'rfid' => $rfidUid
        ]);

        // Validasi data required
        if (!$nisn) {
            \Log::warning('❌ MISSING NISN');
            return null;
        }
        if (!$nama) {
            \Log::warning('❌ MISSING NAMA');
            return null;
        }
        if (!$kelasName) {
            \Log::warning('❌ MISSING KELAS');
            return null;
        }

        // Clean NISN
        $nisn = preg_replace('/[^0-9]/', '', $nisn);
        if (strlen($nisn) !== 10) {
            \Log::warning('❌ INVALID NISN LENGTH: ' . $nisn);
            return null;
        }

        // Find kelas
        $kelas = $this->findKelas($kelasName);
        if (!$kelas) {
            \Log::error('❌ KELAS NOT FOUND: ' . $kelasName);

            // Log semua kelas yang ada untuk debugging
            $allKelas = Kelas::all();
            \Log::info('📚 ALL AVAILABLE KELAS:', $allKelas->toArray());

            throw new \Exception("Kelas '{$kelasName}' tidak ditemukan. Kelas yang tersedia: " . $allKelas->pluck('nama_kelas')->implode(', '));
        }

        \Log::info('✅ KELAS FOUND:', [
            'kelas_id' => $kelas->id,
            'tingkat' => $kelas->tingkat,
            'jurusan' => $kelas->jurusan,
            'nama_kelas' => $kelas->nama_kelas
        ]);

        // Check if siswa exists
        $existingSiswa = Siswa::where('nisn', $nisn)->first();

        if ($existingSiswa) {
            \Log::info('📝 UPDATING EXISTING SISWA: ' . $nisn);
            $existingSiswa->update([
                'nama' => $nama,
                'kelas_id' => $kelas->id,
                'no_telepon_ortu' => $noTelepon,
                'alamat' => $alamat,
                'rfid_uid' => $rfidUid,
                'status_aktif' => true,
            ]);
            $this->updatedCount++;
            \Log::info('✅ UPDATE COMPLETED');
            return null;
        }

        \Log::info('🆕 CREATING NEW SISWA: ' . $nisn);

        $siswaData = [
            'nisn' => $nisn,
            'nama' => $nama,
            'kelas_id' => $kelas->id,
            'no_telepon_ortu' => $noTelepon,
            'alamat' => $alamat,
            'rfid_uid' => $rfidUid,
            'status_aktif' => true,
        ];

        \Log::info('💾 SISWA DATA TO CREATE:', $siswaData);

        $this->importedCount++;
        $newSiswa = new Siswa($siswaData);

        \Log::info('✅ CREATION COMPLETED');
        return $newSiswa;
    }

    private function findKelas($kelasName)
    {
        $kelasName = trim($kelasName);
        \Log::info('🔍 SEARCHING KELAS: ' . $kelasName);

        // Coba exact match di nama_kelas
        $kelas = Kelas::where('nama_kelas', $kelasName)->first();
        if ($kelas) {
            \Log::info('✅ FOUND BY NAMA_KELAS EXACT: ' . $kelasName);
            return $kelas;
        }

        // Coba parse format "10 IPA A"
        if (preg_match('/(\d+)\s+(\w+)\s+(\w+)/i', $kelasName, $matches)) {
            $tingkat = $matches[1];
            $jurusan = strtoupper($matches[2]);
            $nama_kelas = strtoupper($matches[3]);

            \Log::info('🔍 PARSED KELAS:', [
                'tingkat' => $tingkat,
                'jurusan' => $jurusan,
                'nama_kelas' => $nama_kelas
            ]);

            $kelas = Kelas::where('tingkat', $tingkat)
                         ->where('jurusan', $jurusan)
                         ->where('nama_kelas', $nama_kelas)
                         ->first();

            if ($kelas) {
                \Log::info('✅ FOUND BY PARSED FORMAT: ' . $kelasName);
                return $kelas;
            }
        }

        // Coba cari dengan LIKE
        $kelas = Kelas::where('nama_kelas', 'like', "%{$kelasName}%")
                     ->orWhere('tingkat', 'like', "%{$kelasName}%")
                     ->orWhere('jurusan', 'like', "%{$kelasName}%")
                     ->first();

        if ($kelas) {
            \Log::info('✅ FOUND BY LIKE SEARCH: ' . $kelasName);
            return $kelas;
        }

        \Log::warning('❌ KELAS NOT FOUND: ' . $kelasName);
        return null;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }
}
