<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Cari kelas berdasarkan nama atau ID
        $kelas = Kelas::where('id', $row['kelas_id'])
                    ->orWhere('nama_kelas', $row['kelas_id'])
                    ->first();

        if (!$kelas) {
            throw new \Exception("Kelas dengan ID/Nama '{$row['kelas_id']}' tidak ditemukan");
        }

        return new Siswa([
            'nisn' => $row['nisn'],
            'nama' => $row['nama'],
            'kelas_id' => $kelas->id,
            'no_telepon_ortu' => $row['no_telepon_ortu'],
            'alamat' => $row['alamat'],
            'rfid_uid' => $row['rfid_uid'] ?? null,
            'status_aktif' => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'nisn' => 'required|digits:10|unique:siswas,nisn',
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'no_telepon_ortu' => 'required|string|max:15',
            'alamat' => 'required|string',
            'rfid_uid' => 'nullable|string|max:50|unique:siswas,rfid_uid',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nisn.required' => 'NISN wajib diisi',
            'nisn.digits' => 'NISN harus 10 digit',
            'nisn.unique' => 'NISN sudah terdaftar',
            'kelas_id.exists' => 'Kelas tidak ditemukan',
            'no_telepon_ortu.required' => 'Nomor telepon orang tua wajib diisi',
        ];
    }
}
