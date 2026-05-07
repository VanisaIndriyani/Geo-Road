<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RoadsExport;
use App\Http\Controllers\Controller;
use App\Models\Road;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RoadController extends Controller
{
    public function index(Request $request)
    {
        $query = Road::query();

        $q = trim((string) $request->string('q'));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_ruas', 'like', "%{$q}%")
                    ->orWhere('kabupaten', 'like', "%{$q}%")
                    ->orWhere('kecamatan', 'like', "%{$q}%");
            });
        }

        $kondisi = $request->string('kondisi')->toString();
        if ($kondisi !== '') {
            $query->where('kondisi', $kondisi);
        }

        $roads = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.roads.index', [
            'roads' => $roads,
            'q' => $q,
            'kondisi' => $kondisi,
            'kondisiOptions' => Road::kondisiOptions(),
        ]);
    }

    public function create()
    {
        return view('admin.roads.create', [
            'kondisiOptions' => Road::kondisiOptions(),
            'prioritasOptions' => Road::prioritasOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRoad($request);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('roads', 'public');
        }

        $validated['geometry'] = $this->normalizeGeometry($validated['geometry'] ?? null);

        Road::create($validated);

        return redirect()
            ->route('admin.roads.index')
            ->with('success', 'Data jalan berhasil ditambahkan.');
    }

    public function show(Road $road)
    {
        return view('admin.roads.show', [
            'road' => $road,
        ]);
    }

    public function edit(Road $road)
    {
        return view('admin.roads.edit', [
            'road' => $road,
            'kondisiOptions' => Road::kondisiOptions(),
            'prioritasOptions' => Road::prioritasOptions(),
            'geometryPoints' => $road->geometryPoints(),
        ]);
    }

    public function update(Request $request, Road $road)
    {
        $validated = $this->validateRoad($request, $road->id);

        if ($request->hasFile('foto')) {
            if ($road->foto) {
                Storage::disk('public')->delete($road->foto);
            }
            $validated['foto'] = $request->file('foto')->store('roads', 'public');
        }

        $validated['geometry'] = $this->normalizeGeometry($validated['geometry'] ?? null);

        $road->update($validated);

        return redirect()
            ->route('admin.roads.index')
            ->with('success', 'Data jalan berhasil diperbarui.');
    }

    public function destroy(Road $road)
    {
        if ($road->foto) {
            Storage::disk('public')->delete($road->foto);
        }

        $road->delete();

        return redirect()
            ->route('admin.roads.index')
            ->with('success', 'Data jalan berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new RoadsExport($request->all()), 'geo-road-data-jalan.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $roads = (new RoadsExport($request->all()))->collection();

        $pdf = Pdf::loadView('admin.roads.pdf', [
            'roads' => $roads,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('geo-road-data-jalan.pdf');
    }

    private function validateRoad(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'nama_ruas' => ['required', 'string', 'max:255'],
            'kabupaten' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'panjang' => ['required', 'numeric', 'min:0'],
            'lebar' => ['nullable', 'numeric', 'min:0'],
            'kondisi' => ['required', 'string', 'in:'.implode(',', Road::kondisiOptions())],
            'jenis_kerusakan' => ['nullable', 'string'],
            'prioritas' => ['nullable', 'string', 'max:50'],
            'tahun' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'foto' => ['nullable', 'image', 'max:5120'],
            'geometry' => ['nullable', 'string'],
        ], [
            'kondisi.in' => 'Kondisi jalan tidak valid.',
        ]);
    }

    private function normalizeGeometry(?string $geometry): ?string
    {
        if (!$geometry) {
            return null;
        }

        $decoded = json_decode($geometry, true);
        if (!is_array($decoded)) {
            return null;
        }

        $points = [];
        foreach ($decoded as $pair) {
            if (!is_array($pair) || count($pair) !== 2) {
                continue;
            }
            $lat = $pair[0];
            $lng = $pair[1];
            if (!is_numeric($lat) || !is_numeric($lng)) {
                continue;
            }
            $points[] = [round((float) $lat, 6), round((float) $lng, 6)];
        }

        if (count($points) < 2) {
            return null;
        }

        return json_encode($points);
    }
}

