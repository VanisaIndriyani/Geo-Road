@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3" data-aos="fade-up">
        <div class="text-dark">
            <div class="h4 fw-bold mb-0">Tambah Data Jalan</div>
            <div class="text-muted">Lengkapi informasi ruas jalan dan gambar polyline.</div>
        </div>
        <a href="{{ route('admin.roads.index') }}" class="btn btn-outline-dark rounded-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.roads.store') }}" enctype="multipart/form-data" class="row g-3 g-lg-4">
        @csrf

        <div class="col-lg-5" data-aos="fade-up">
            <div class="gr-card p-4">
                <div class="fw-bold mb-3">Informasi Ruas Jalan</div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Ruas Jalan</label>
                    <input type="text" class="form-control rounded-4 @error('nama_ruas') is-invalid @enderror" name="nama_ruas" value="{{ old('nama_ruas') }}" required>
                    @error('nama_ruas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-2">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kabupaten</label>
                        <input type="text" class="form-control rounded-4 @error('kabupaten') is-invalid @enderror" name="kabupaten" value="{{ old('kabupaten') }}" required>
                        @error('kabupaten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kecamatan</label>
                        <input type="text" class="form-control rounded-4 @error('kecamatan') is-invalid @enderror" name="kecamatan" value="{{ old('kecamatan') }}" required>
                        @error('kecamatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Panjang Jalan (Km)</label>
                        <input type="number" step="0.01" class="form-control rounded-4 @error('panjang') is-invalid @enderror" name="panjang" value="{{ old('panjang') }}" required>
                        @error('panjang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Lebar Jalan (m)</label>
                        <input type="number" step="0.01" class="form-control rounded-4 @error('lebar') is-invalid @enderror" name="lebar" value="{{ old('lebar') }}">
                        @error('lebar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kondisi Jalan</label>
                    <select class="form-select rounded-4 @error('kondisi') is-invalid @enderror" name="kondisi" required>
                        @foreach ($kondisiOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('kondisi') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                    @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kerusakan</label>
                    <textarea class="form-control rounded-4 @error('jenis_kerusakan') is-invalid @enderror" name="jenis_kerusakan" rows="2" placeholder="Opsional">{{ old('jenis_kerusakan') }}</textarea>
                    @error('jenis_kerusakan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-2">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Prioritas Penanganan</label>
                        <select class="form-select rounded-4 @error('prioritas') is-invalid @enderror" name="prioritas">
                            <option value="">-</option>
                            <option value="Rendah" @selected(old('prioritas') === 'Rendah')>Rendah</option>
                            <option value="Sedang" @selected(old('prioritas') === 'Sedang')>Sedang</option>
                            <option value="Tinggi" @selected(old('prioritas') === 'Tinggi')>Tinggi</option>
                        </select>
                        @error('prioritas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tahun Survey</label>
                        <input type="number" class="form-control rounded-4 @error('tahun') is-invalid @enderror" name="tahun" value="{{ old('tahun') }}" placeholder="2026">
                        @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Jalan</label>
                    <input type="file" class="form-control rounded-4 @error('foto') is-invalid @enderror" name="foto" accept="image/*">
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <input type="hidden" name="geometry" id="geometry" value="{{ old('geometry') }}">

                <div class="d-grid">
                    <button type="submit" class="btn gr-btn-gold rounded-4 py-2">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-7" data-aos="fade-up">
            <div class="gr-card p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <div class="fw-bold">Geometry Polyline</div>
                        <div class="small text-muted">Gambar polyline ruas jalan pada peta. Klik ikon garis lalu tarik titik-titik.</div>
                    </div>
                    <button type="button" class="btn btn-outline-dark rounded-4" id="btnClearLine">
                        <i class="bi bi-eraser me-1"></i> Reset Garis
                    </button>
                </div>
                <div style="height:520px" class="gr-map" id="mapRoad"></div>
                @error('geometry')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script>
        (() => {
            const map = L.map('mapRoad', { zoomControl: true }).setView([-5.45, 105.27], 9);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

            const drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            const drawControl = new L.Control.Draw({
                draw: {
                    polygon: false,
                    rectangle: false,
                    circle: false,
                    circlemarker: false,
                    marker: false,
                    polyline: { shapeOptions: { color: '#facc15', weight: 5 } }
                },
                edit: { featureGroup: drawnItems, remove: true }
            });
            map.addControl(drawControl);

            const geometryInput = document.getElementById('geometry');
            const syncGeometry = () => {
                const layers = drawnItems.getLayers();
                const poly = layers.find(l => l instanceof L.Polyline);
                if (!poly) {
                    geometryInput.value = '';
                    return;
                }
                const latlngs = poly.getLatLngs().map(p => [Number(p.lat.toFixed(6)), Number(p.lng.toFixed(6))]);
                geometryInput.value = JSON.stringify(latlngs);
            };

            map.on(L.Draw.Event.CREATED, (e) => {
                drawnItems.clearLayers();
                drawnItems.addLayer(e.layer);
                syncGeometry();
            });
            map.on(L.Draw.Event.EDITED, syncGeometry);
            map.on(L.Draw.Event.DELETED, syncGeometry);

            document.getElementById('btnClearLine').addEventListener('click', () => {
                drawnItems.clearLayers();
                syncGeometry();
            });

            if (geometryInput.value) {
                try {
                    const points = JSON.parse(geometryInput.value);
                    if (Array.isArray(points) && points.length >= 2) {
                        const line = L.polyline(points.map(p => ({ lat: p[0], lng: p[1] })), { color: '#facc15', weight: 5 });
                        drawnItems.addLayer(line);
                        map.fitBounds(line.getBounds(), { padding: [24, 24] });
                    }
                } catch (e) {}
            }
        })();
    </script>
@endpush
