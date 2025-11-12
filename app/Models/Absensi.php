<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'status_masuk',
        'status_pulang',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function getStatusMasukColorAttribute(): string
    {
        return match($this->status_masuk) {
            'hadir' => 'success',
            'terlambat' => 'warning',
            'tidak_hadir' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusPulangColorAttribute(): string
    {
        return match($this->status_pulang) {
            'tepat_waktu' => 'success',
            'cepat' => 'warning',
            'tidak_hadir' => 'danger',
            default => 'secondary'
        };
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
    }
}
