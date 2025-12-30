<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidScan extends Model
{
    protected $fillable = [
        'rfid_uid',
        'siswa_id',
        'status',
        'scanned_at',
        'ip_address',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    /**
     * Relationship to Siswa model
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Scope untuk mendapatkan scan terbaru
     */
    public function scopeRecent($query, $minutes = 5)
    {
        return $query->where('scanned_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Auto-delete old records (older than 24 hours)
     */
    public static function cleanOldRecords()
    {
        return static::where('scanned_at', '<', now()->subHours(24))->delete();
    }
}

