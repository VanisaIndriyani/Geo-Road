<?php

namespace App\Http\Controllers;

use App\Models\Road;
use Illuminate\Http\Request;

class WebgisController extends Controller
{
    public function public(Request $request)
    {
        return view('webgis', [
            'kondisiOptions' => Road::kondisiOptions(),
        ]);
    }

    public function admin(Request $request)
    {
        return view('admin.webgis', [
            'kondisiOptions' => Road::kondisiOptions(),
        ]);
    }

    public function roadsGeojson(Request $request)
    {
        $query = Road::query();

        $q = trim((string) $request->string('q'));
        if ($q !== '') {
            $query->where('nama_ruas', 'like', "%{$q}%");
        }

        $kondisi = $request->string('kondisi')->toString();
        if ($kondisi !== '') {
            $query->where('kondisi', $kondisi);
        }

        $roads = $query->orderBy('nama_ruas')->get();

        $features = [];
        $heatPoints = [];

        foreach ($roads as $road) {
            $pointsLatLng = $road->geometryPoints();
            if (count($pointsLatLng) < 2) {
                continue;
            }

            $coordinates = array_map(function (array $pair) {
                return [(float) $pair[1], (float) $pair[0]];
            }, $pointsLatLng);

            $first = $pointsLatLng[0];
            $last = $pointsLatLng[count($pointsLatLng) - 1];
            $mid = [
                ($first[0] + $last[0]) / 2,
                ($first[1] + $last[1]) / 2,
            ];

            $severity = match ($road->kondisi) {
                Road::KONDISI_RUSAK_BERAT => 1.0,
                Road::KONDISI_RUSAK_SEDANG => 0.7,
                Road::KONDISI_RUSAK_RINGAN => 0.4,
                default => 0.15,
            };

            $heatPoints[] = [$mid[0], $mid[1], $severity];

            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'LineString',
                    'coordinates' => $coordinates,
                ],
                'properties' => [
                    'id' => $road->id,
                    'nama_ruas' => $road->nama_ruas,
                    'kabupaten' => $road->kabupaten,
                    'kecamatan' => $road->kecamatan,
                    'panjang' => (float) $road->panjang,
                    'lebar' => $road->lebar !== null ? (float) $road->lebar : null,
                    'kondisi' => $road->kondisi,
                    'jenis_kerusakan' => $road->jenis_kerusakan,
                    'prioritas' => $road->prioritas,
                    'tahun' => $road->tahun,
                    'foto_url' => $road->foto_url,
                ],
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
            'meta' => [
                'heat' => $heatPoints,
            ],
        ]);
    }
}

