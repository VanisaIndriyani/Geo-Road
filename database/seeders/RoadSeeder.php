<?php

namespace Database\Seeders;

use App\Models\Road;
use Illuminate\Database\Seeder;

class RoadSeeder extends Seeder
{
    public function run(): void
    {
        $kabupatenList = [
            'Bandar Lampung',
            'Lampung Selatan',
            'Lampung Timur',
            'Lampung Tengah',
            'Lampung Utara',
            'Pesawaran',
            'Pringsewu',
            'Tanggamus',
            'Tulang Bawang',
            'Tulang Bawang Barat',
            'Way Kanan',
            'Mesuji',
            'Pesisir Barat',
            'Lampung Barat',
            'Metro',
        ];

        $kecamatanList = [
            'Kedaton',
            'Sukarame',
            'Tanjung Karang Barat',
            'Tanjung Karang Timur',
            'Natar',
            'Jati Agung',
            'Kalianda',
            'Labuhan Maringgai',
            'Seputih Banyak',
            'Kotabumi',
        ];

        $prioritasList = ['Rendah', 'Sedang', 'Tinggi'];

        $jenisKerusakanList = [
            'Retak memanjang',
            'Lubang (potholes)',
            'Alur (rutting)',
            'Gelombang (corrugation)',
            'Amblas/penurunan badan jalan',
            'Kerusakan bahu jalan',
        ];

        for ($i = 1; $i <= 25; $i++) {
            $kondisi = collect(Road::kondisiOptions())->random();
            $prioritas = $kondisi === Road::KONDISI_RUSAK_BERAT ? 'Tinggi' : collect($prioritasList)->random();

            $points = $this->randomPolylinePoints();

            Road::create([
                'nama_ruas' => "Ruas Jalan Lampung {$i}",
                'kabupaten' => collect($kabupatenList)->random(),
                'kecamatan' => collect($kecamatanList)->random(),
                'panjang' => round(mt_rand(150, 3500) / 100, 2),
                'lebar' => round(mt_rand(350, 900) / 100, 2),
                'kondisi' => $kondisi,
                'jenis_kerusakan' => $kondisi === Road::KONDISI_BAIK ? null : collect($jenisKerusakanList)->random(),
                'prioritas' => $prioritas,
                'tahun' => mt_rand(2021, 2026),
                'foto' => null,
                'geometry' => json_encode($points),
            ]);
        }
    }

    private function randomPolylinePoints(): array
    {
        $baseLat = -5.25 + (mt_rand(-40, 40) / 100);
        $baseLng = 105.10 + (mt_rand(-60, 60) / 100);
        $count = mt_rand(3, 6);

        $points = [];
        $lat = $baseLat;
        $lng = $baseLng;

        for ($i = 0; $i < $count; $i++) {
            $lat += (mt_rand(-25, 25) / 1000);
            $lng += (mt_rand(-25, 25) / 1000);
            $points[] = [round($lat, 6), round($lng, 6)];
        }

        return $points;
    }
}

