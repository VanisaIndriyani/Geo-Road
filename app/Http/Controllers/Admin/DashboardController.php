<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Road;
use Illuminate\Http\Request;

class DashboardController extends Controller
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

        $rusakKabupaten = Road::selectRaw('kabupaten, COUNT(*) as total')
            ->where('kondisi', '!=', Road::KONDISI_BAIK)
            ->groupBy('kabupaten')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $recent = Road::latest()->limit(6)->get();

        return view('admin.dashboard', [
            'stats' => [
                'baik' => $baik,
                'rusak_ringan' => $rusakRingan,
                'rusak_berat' => $rusakBerat,
                'rusak_sedang' => $rusakSedang,
            ],
            'kondisiBreakdown' => $kondisiBreakdown,
            'rusakKabupaten' => $rusakKabupaten,
            'recentRoads' => $recent,
        ]);
    }
}
