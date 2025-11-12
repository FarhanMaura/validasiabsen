<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';

    protected $fillable = [
        'nisn',
        'nama',
        'kelas_id',
        'no_telepon_ortu',
        'alamat',
        'rfid_uid',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function getStatusAktifTextAttribute(): string
    {
        return $this->status_aktif ? 'Aktif' : 'Tidak Aktif';
    }

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('nisn', 'like', '%'.$search.'%')
                    ->orWhere('nama', 'like', '%'.$search.'%')
                    ->orWhere('no_telepon_ortu', 'like', '%'.$search.'%')
                    ->orWhere('rfid_uid', 'like', '%'.$search.'%')
                    ->orWhereHas('kelas', function ($query) use ($search) {
                        $query->where('nama_kelas', 'like', '%'.$search.'%')
                                ->orWhere('tingkat', 'like', '%'.$search.'%')
                                ->orWhere('jurusan', 'like', '%'.$search.'%');
                    });
            });
        });
    }

    public function getAbsensiHariIniAttribute()
    {
        return $this->absensi()->whereDate('tanggal', today())->first();
    }
}
