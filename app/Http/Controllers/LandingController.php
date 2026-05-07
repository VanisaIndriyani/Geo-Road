<?php

namespace App\Http\Controllers;

use App\Models\Road;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function __invoke(Request $request)
    {
        $baik = Road::where('kondisi', Road::KONDISI_BAIK)->count();
        $rusakRingan = Road::where('kondisi', Road::KONDISI_RUSAK_RINGAN)->count();
        $rusakSedang = Road::where('kondisi', Road::KONDISI_RUSAK_SEDANG)->count();
        $rusakBerat = Road::where('kondisi', Road::KONDISI_RUSAK_BERAT)->count();

        $kondisiBreakdown = Road::selectRaw('kondisi, COUNT(*) as total')
            ->groupBy('kondisi')
            ->pluck('total', 'kondisi')
            ->toArray();

        $kerusakanKabupatenRaw = Road::selectRaw('kabupaten, COUNT(*) as total')
            ->where('kondisi', '!=', Road::KONDISI_BAIK)
            ->groupBy('kabupaten')
            ->get()
            ->pluck('total', 'kabupaten')
            ->toArray();

        $kerusakanKabupaten = collect(Road::kabupatenOptions())->map(function ($kab) use ($kerusakanKabupatenRaw) {
            return (object) [
                'kabupaten' => $kab,
                'total' => $kerusakanKabupatenRaw[$kab] ?? 0,
            ];
        })->sortByDesc('total')->values();

        return view('welcome', [
            'stats' => [
                'baik' => $baik,
                'rusak_ringan' => $rusakRingan,
                'rusak_sedang' => $rusakSedang,
                'rusak_berat' => $rusakBerat,
            ],
            'kondisiBreakdown' => $kondisiBreakdown,
            'kerusakanKabupaten' => $kerusakanKabupaten,
        ]);
    }
}
