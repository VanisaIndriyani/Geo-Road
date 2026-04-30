@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3" data-aos="fade-up">
        <div class="text-dark">
            <div class="h4 fw-bold mb-0">Detail Jalan</div>
            <div class="text-muted">{{ $road->nama_ruas }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.roads.edit', $road) }}" class="btn gr-btn-gold rounded-4">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            <a href="{{ route('admin.roads.index') }}" class="btn btn-outline-dark rounded-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-lg-5" data-aos="fade-up">
            <div class="gr-card p-4">
                <div class="fw-bold mb-3">Informasi</div>

                <div class="row g-2">
                    <div class="col-5 text-muted">Kabupaten</div>
                    <div class="col-7 fw-semibold">{{ $road->kabupaten }}</div>
                    <div class="col-5 text-muted">Kecamatan</div>
                    <div class="col-7 fw-semibold">{{ $road->kecamatan }}</div>
                    <div class="col-5 text-muted">Panjang</div>
                    <div class="col-7 fw-semibold">{{ number_format((float) $road->panjang, 2, ',', '.') }} Km</div>
                    <div class="col-5 text-muted">Lebar</div>
                    <div class="col-7 fw-semibold">{{ $road->lebar !== null ? number_format((float) $road->lebar, 2, ',', '.') . ' m' : '-' }}</div>
                    <div class="col-5 text-muted">Kondisi</div>
                    <div class="col-7 fw-semibold">{{ $road->kondisi }}</div>
                    <div class="col-5 text-muted">Prioritas</div>
                    <div class="col-7 fw-semibold">{{ $road->prioritas ?? '-' }}</div>
                    <div class="col-5 text-muted">Tahun Survey</div>
                    <div class="col-7 fw-semibold">{{ $road->tahun ?? '-' }}</div>
                </div>

                <hr>

                <div class="fw-bold mb-2">Jenis Kerusakan</div>
                <div class="text-muted">{{ $road->jenis_kerusakan ?: '-' }}</div>

                <div class="fw-bold mt-4 mb-2">Foto</div>
                @if ($road->foto_url)
                    <img src="{{ $road->foto_url }}" class="w-100 rounded-4" style="object-fit:cover;max-height:260px" alt="Foto Jalan">
                @else
                    <div class="text-muted">Tidak ada foto.</div>
                @endif
            </div>
        </div>

        <div class="col-lg-7" data-aos="fade-up">
            <div class="gr-card p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="fw-bold">Peta Ruas Jalan</div>
                        <div class="small text-muted">Polyline ditampilkan dari geometry.</div>
                    </div>
                    <a class="btn btn-outline-dark rounded-4" href="{{ route('admin.webgis') }}">
                        <i class="bi bi-map me-1"></i> Buka WebGIS
                    </a>
                </div>
                <div style="height:560px" class="gr-map" id="mapShow"></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (() => {
            const points = @json($road->geometryPoints());
            const map = L.map('mapShow', { zoomControl: true }).setView([-5.45, 105.27], 9);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

            if (Array.isArray(points) && points.length >= 2) {
                const line = L.polyline(points.map(p => ({ lat: p[0], lng: p[1] })), { color: '#facc15', weight: 6, opacity: .9 }).addTo(map);
                map.fitBounds(line.getBounds(), { padding: [24, 24] });
            }
        })();
    </script>
@endpush
