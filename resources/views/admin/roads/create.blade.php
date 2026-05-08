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
                            @foreach ($prioritasOptions as $opt)
                                <option value="{{ $opt }}" @selected(old('prioritas') === $opt)>{{ $opt }}</option>
                            @endforeach
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

                <div class="mb-3">
                    <label class="form-label fw-semibold">Koordinat Awal (Lat / Long)</label>
                    <input type="text" class="form-control rounded-4" id="coordAwal" placeholder="-5.714828 / 105.587492 atau 5°42'53.38&quot; / 105°35'14.97&quot;">
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold">Koordinat Akhir (Lat / Long)</label>
                    <input type="text" class="form-control rounded-4" id="coordAkhir" placeholder="-5.738647 / 105.591428 atau 5°44'19.13&quot; / 105°35'29.14&quot;">
                </div>
                <div class="d-grid mb-3">
                    <button type="button" class="btn btn-outline-dark rounded-4" id="btnApplyCoords">
                        <i class="bi bi-geo-alt me-1"></i> Terapkan Koordinat ke Peta
                    </button>
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
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="autoSnap" checked>
                            <label class="form-check-label small" for="autoSnap">Auto rapikan</label>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-dark rounded-4" id="btnSnapToRoads">
                                <i class="bi bi-magic me-1"></i> Rapikan ke Jalan OSM
                            </button>
                            <button type="button" class="btn btn-outline-dark rounded-4" id="btnClearLine">
                                <i class="bi bi-eraser me-1"></i> Reset Garis
                            </button>
                        </div>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.10.1/src/leaflet.geometryutil.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-snap@0.0.4/leaflet.snap.js"></script>
    <script>
        (() => {
            const map = L.map('mapRoad', {
                zoomControl: false
            }).setView([-5.45, 105.27], 9);

            L.control.zoom({ position: 'bottomleft' }).addTo(map);

            const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
            osm.addTo(map);

            const drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            const roadsGuideLayer = L.geoJSON(null, {
                style: { color: '#0f172a', weight: 3, opacity: 0.35 }
            }).addTo(map);

            L.control.layers(
                { 'Street (OSM)': osm },
                { 'Jalan OSM (Snapping)': roadsGuideLayer },
                { position: 'topright' }
            ).addTo(map);

            let roadsLoading = false;
            let roadsLastBbox = '';

            const wait = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

            const toBboxString = (bounds) => {
                return [
                    bounds.getSouth().toFixed(5),
                    bounds.getWest().toFixed(5),
                    bounds.getNorth().toFixed(5),
                    bounds.getEast().toFixed(5),
                ].join(',');
            };

            const fetchOverpassRoads = async (bounds) => {
                if (roadsLoading) return;
                const bbox = toBboxString(bounds);
                if (bbox === roadsLastBbox) return;
                roadsLastBbox = bbox;
                roadsLoading = true;

                try {
                    const query = `[out:json][timeout:25];(way["highway"~"motorway|trunk|primary|secondary|tertiary|residential|unclassified|service"](${bbox}););out geom;`;
                    const res = await fetch('https://overpass-api.de/api/interpreter', {
                        method: 'POST',
                        headers: { 'Content-Type': 'text/plain' },
                        body: query,
                    });
                    if (!res.ok) return;
                    const json = await res.json();
                    const features = (json?.elements ?? [])
                        .filter(el => el?.type === 'way' && Array.isArray(el.geometry) && el.geometry.length >= 2)
                        .map(el => ({
                            type: 'Feature',
                            properties: { id: el.id, highway: el?.tags?.highway },
                            geometry: {
                                type: 'LineString',
                                coordinates: el.geometry.map(p => [p.lon, p.lat]),
                            }
                        }));
                    roadsGuideLayer.clearLayers();
                    roadsGuideLayer.addData({ type: 'FeatureCollection', features });
                } catch (e) {
                } finally {
                    roadsLoading = false;
                }
            };

            const ensureRoads = async (bounds) => {
                if (!map.hasLayer(roadsGuideLayer)) {
                    roadsGuideLayer.addTo(map);
                }
                while (roadsLoading) {
                    await wait(120);
                }
                await fetchOverpassRoads(bounds);
                while (roadsLoading) {
                    await wait(120);
                }
            };

            const drawControl = new L.Control.Draw({
                draw: {
                    polygon: false,
                    rectangle: false,
                    circle: false,
                    circlemarker: false,
                    marker: false,
                    polyline: { shapeOptions: { color: '#facc15', weight: 5 }, guideLayers: [roadsGuideLayer], snapDistance: 14 }
                },
                edit: { featureGroup: drawnItems, remove: true }
            });
            map.addControl(drawControl);

            const geometryInput = document.getElementById('geometry');
            const coordAwalEl = document.getElementById('coordAwal');
            const coordAkhirEl = document.getElementById('coordAkhir');
            const btnApplyCoords = document.getElementById('btnApplyCoords');
            const autoSnapEl = document.getElementById('autoSnap');

            const parseAngle = (value, isLat) => {
                const s0 = String(value ?? '').trim();
                if (!s0) return null;
                const s = s0.replace(',', '.');

                let sign = 1;
                if (/[SW]/i.test(s) || /^\s*-/.test(s)) sign = -1;

                const dms = s.match(/(-?\d+(?:\.\d+)?)\s*°\s*(?:(\d+(?:\.\d+)?)\s*'\s*)?(?:(\d+(?:\.\d+)?)\s*")?/);
                if (dms) {
                    const deg = parseFloat(dms[1] ?? '0');
                    const min = parseFloat(dms[2] ?? '0');
                    const sec = parseFloat(dms[3] ?? '0');
                    let decimal = Math.abs(deg) + (min / 60) + (sec / 3600);
                    if (sign > 0 && isLat) sign = -1;
                    decimal = decimal * sign;
                    return Number.isFinite(decimal) ? decimal : null;
                }

                const cleaned = s.replace(/[^0-9.\-]/g, '');
                if (!cleaned) return null;
                let decimal = parseFloat(cleaned);
                if (!Number.isFinite(decimal)) return null;
                if (decimal >= 0 && isLat) decimal = -decimal;
                return decimal;
            };

            const normalizeLatLng = (lat, lng) => {
                if (lat === null || lng === null) return null;
                let a = Number(lat);
                let b = Number(lng);
                if (!Number.isFinite(a) || !Number.isFinite(b)) return null;

                if (Math.abs(a) > 90 && Math.abs(b) <= 90) {
                    [a, b] = [b, a];
                }

                if (Math.abs(a) > 90 || Math.abs(b) > 180) return null;
                return [Number(a.toFixed(6)), Number(b.toFixed(6))];
            };

            const parseLatLngPair = (text) => {
                const raw = String(text ?? '').trim();
                if (!raw) return null;

                let parts = [];
                if (raw.includes('/')) {
                    parts = raw.split('/').map(p => p.trim()).filter(Boolean);
                } else if (raw.includes(',')) {
                    parts = raw.split(',').map(p => p.trim()).filter(Boolean);
                } else {
                    parts = raw.split(/\s+/).map(p => p.trim()).filter(Boolean);
                }

                if (parts.length < 2) return null;

                const a = parseAngle(parts[0], true);
                const b = parseAngle(parts[1], false);
                return normalizeLatLng(a, b);
            };

            const setCoordFieldsFromPoints = (latlngs) => {
                if (!Array.isArray(latlngs) || latlngs.length < 2) return;
                const first = latlngs[0];
                const last = latlngs[latlngs.length - 1];
                coordAwalEl.value = `${Number(first[0]).toFixed(6)} / ${Number(first[1]).toFixed(6)}`;
                coordAkhirEl.value = `${Number(last[0]).toFixed(6)} / ${Number(last[1]).toFixed(6)}`;
            };

            const applyCoordsToMap = async () => {
                const awal = parseLatLngPair(coordAwalEl.value);
                const akhir = parseLatLngPair(coordAkhirEl.value);
                if (!awal || !akhir) {
                    window.alert('Format koordinat tidak valid. Contoh: -5.714828 / 105.587492 atau -5.714828, 105.587492');
                    return;
                }

                drawnItems.clearLayers();
                const line = L.polyline([{ lat: awal[0], lng: awal[1] }, { lat: akhir[0], lng: akhir[1] }], { color: '#facc15', weight: 5 });
                drawnItems.addLayer(line);
                geometryInput.value = JSON.stringify([awal, akhir]);
                map.fitBounds(line.getBounds(), { padding: [24, 24] });
                if (autoSnapEl?.checked) {
                    await snapPolylineToRoads();
                }
            };

            const syncGeometry = () => {
                const layers = drawnItems.getLayers();
                const poly = layers.find(l => l instanceof L.Polyline);
                if (!poly) {
                    geometryInput.value = '';
                    coordAwalEl.value = '';
                    coordAkhirEl.value = '';
                    return;
                }
                const latlngs = poly.getLatLngs().map(p => [Number(p.lat.toFixed(6)), Number(p.lng.toFixed(6))]);
                geometryInput.value = JSON.stringify(latlngs);
                setCoordFieldsFromPoints(latlngs);
            };

            const getSnapResult = (latlng) => {
                if (!window.L?.GeometryUtil?.closestLayerSnap) return null;
                return L.GeometryUtil.closestLayerSnap(map, [roadsGuideLayer], latlng, 14);
            };

            const snapLatLngs = (latlngs) => {
                return latlngs.map((p) => {
                    const r = getSnapResult(p);
                    return r?.latlng ?? p;
                });
            };

            const snapPolylineToRoads = async () => {
                const layers = drawnItems.getLayers();
                const poly = layers.find(l => l instanceof L.Polyline);
                if (!poly) {
                    window.alert('Silakan gambar garis terlebih dahulu atau masukkan koordinat.');
                    return;
                }

                const latlngs = poly.getLatLngs();
                if (!Array.isArray(latlngs) || latlngs.length < 2) return;

                const points = (Array.isArray(latlngs[0]) ? latlngs[0] : latlngs)
                    .map(p => `${p.lng},${p.lat}`).join(';');

                const btn = document.getElementById('btnSnapToRoads');
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Merapikan...';

                try {
                    const res = await fetch(`https://router.project-osrm.org/route/v1/driving/${points}?overview=full&geometries=geojson`);
                    const data = await res.json();

                    if (data.code === 'Ok' && data.routes?.length > 0) {
                        const route = data.routes[0].geometry.coordinates;
                        const newLatLngs = route.map(c => [c[1], c[0]]);

                        poly.setLatLngs(newLatLngs);
                        syncGeometry();
                    } else {
                        window.alert('Gagal merapikan garis. Pastikan titik-titik berada di dekat jalan yang terpetakan.');
                    }
                } catch (e) {
                    console.error(e);
                    window.alert('Terjadi kesalahan saat menghubungi server routing.');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            };

            map.on(L.Draw.Event.CREATED, (e) => {
                drawnItems.clearLayers();
                drawnItems.addLayer(e.layer);
                syncGeometry();
                if (autoSnapEl?.checked) {
                    snapPolylineToRoads();
                }
            });
            map.on(L.Draw.Event.EDITED, syncGeometry);
            map.on(L.Draw.Event.DELETED, syncGeometry);

            map.on('draw:drawstart', () => {
                if (map.getZoom() >= 12) fetchOverpassRoads(map.getBounds());
            });
            map.on('draw:editstart', () => {
                if (map.getZoom() >= 12) fetchOverpassRoads(map.getBounds());
                drawnItems.eachLayer((layer) => {
                    if (!(layer instanceof L.Polyline)) return;
                    layer.snapediting = new L.Handler.PolylineSnap(map, layer, { snapDistance: 14 });
                    layer.snapediting.addGuideLayer(roadsGuideLayer);
                    layer.snapediting.enable();
                });
            });
            map.on('draw:editstop', () => {
                drawnItems.eachLayer((layer) => {
                    if (!layer.snapediting) return;
                    layer.snapediting.disable();
                    layer.snapediting = null;
                });
            });

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
                        setCoordFieldsFromPoints(points);
                    }
                } catch (e) {}
            }

            map.on('moveend', () => {
                if (map.getZoom() < 12) return;
                if (!map.hasLayer(roadsGuideLayer)) return;
                fetchOverpassRoads(map.getBounds());
            });

            document.getElementById('btnSnapToRoads').addEventListener('click', snapPolylineToRoads);

            btnApplyCoords.addEventListener('click', applyCoordsToMap);
            [coordAwalEl, coordAkhirEl].forEach((el) => {
                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyCoordsToMap();
                    }
                });
            });
        })();
    </script>
@endpush
