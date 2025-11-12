<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'key',
        'value',
        'deskripsi',
    ];

    public static function getValue($key, $default = null)
    {
        $pengaturan = static::where('key', $key)->first();
        return $pengaturan ? $pengaturan->value : $default;
    }

    public static function setValue($key, $value, $deskripsi = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'deskripsi' => $deskripsi]
        );
    }
}
