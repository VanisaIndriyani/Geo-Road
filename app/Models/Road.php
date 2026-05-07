<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Road extends Model
{
    use HasFactory;

    public const KONDISI_BAIK = 'Baik';
    public const KONDISI_RUSAK_RINGAN = 'Rusak Ringan';
    public const KONDISI_RUSAK_SEDANG = 'Rusak Sedang';
    public const KONDISI_RUSAK_BERAT = 'Rusak Berat';

    protected $fillable = [
        'nama_ruas',
        'kabupaten',
        'kecamatan',
        'panjang',
        'lebar',
        'kondisi',
        'jenis_kerusakan',
        'prioritas',
        'tahun',
        'foto',
        'geometry',
    ];

    protected $casts = [
        'panjang' => 'decimal:2',
        'lebar' => 'decimal:2',
        'tahun' => 'integer',
    ];

    public static function kondisiOptions(): array
    {
        return [
            self::KONDISI_BAIK,
            self::KONDISI_RUSAK_RINGAN,
            self::KONDISI_RUSAK_SEDANG,
            self::KONDISI_RUSAK_BERAT,
        ];
    }

    public static function prioritasOptions(): array
    {
        return [
            'Rutin',
            'Periodik',
            'Peningkatan',
            'Rekonstruksi',
        ];
    }

    public static function kabupatenOptions(): array
    {
        return [
            'Kota Bandar Lampung',
            'Kota Metro',
            'Kabupaten Lampung Selatan',
            'Kabupaten Lampung Tengah',
            'Kabupaten Lampung Utara',
            'Kabupaten Lampung Barat',
            'Kabupaten Lampung Timur',
            'Kabupaten Tulang Bawang',
            'Kabupaten Tulang Bawang Barat',
            'Kabupaten Way Kanan',
            'Kabupaten Mesuji',
            'Kabupaten Pesawaran',
            'Kabupaten Pringsewu',
            'Kabupaten Tanggamus',
            'Kabupaten Pesisir Barat',
        ];
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }

        $normalized = str_replace('\\', '/', (string) $this->foto);
        $relative = '/storage/' . ltrim($normalized, '/');

        return url($relative);
    }

    public function geometryPoints(): array
    {
        if (!$this->geometry) {
            return [];
        }

        $decoded = json_decode($this->geometry, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_filter($decoded, function ($pair) {
            return is_array($pair)
                && count($pair) === 2
                && is_numeric($pair[0])
                && is_numeric($pair[1]);
        }));
    }
}
