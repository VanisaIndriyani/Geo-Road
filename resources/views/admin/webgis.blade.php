@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3" data-aos="fade-up">
        <div class="text-dark">
            <div class="h4 fw-bold mb-0">WebGIS</div>
            <div class="text-muted">Peta interaktif kondisi ruas jalan Provinsi Lampung.</div>
        </div>
        <a class="btn btn-outline-dark rounded-4" href="{{ route('webgis.public') }}" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Versi Publik
        </a>
    </div>

    <div class="gr-card p-3" data-aos="fade-up">
        <div class="row g-2 mb-2">
            <div class="col-md-6">
                <input type="text" class="form-control rounded-4" id="gisSearch" placeholder="Search nama ruas jalan...">
            </div>
            <div class="col-md-4">
                <select class="form-select rounded-4" id="gisFilterKondisi">
                    <option value="">Semua Kondisi</option>
                    @foreach ($kondisiOptions as $opt)
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-dark rounded-4" id="gisApply">
                    <i class="bi bi-funnel me-1"></i> Terapkan
                </button>
            </div>
        </div>

        <div class="gr-map-shell">
            <div id="mapGis" class="gr-map"></div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.css">
    <style>
        .leaflet-control-layers{border-radius:14px;overflow:hidden}
        .gr-legend{background:rgba(255,255,255,.94);border:1px solid rgba(148,163,184,.25);border-radius:14px;padding:10px 12px;box-shadow:0 18px 60px rgba(2,6,23,.18);font-size:12px}
        .gr-legend .sw{display:inline-block;width:14px;height:10px;border-radius:4px;margin-right:8px}
        .gr-legend .row{display:flex;align-items:center;margin-bottom:6px}
        .gr-legend .row:last-child{margin-bottom:0}
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <script>
        (() => {
            const colors = {
                'Baik': '#22c55e',
                'Rusak Ringan': '#facc15',
                'Rusak Sedang': '#fb923c',
                'Rusak Berat': '#ef4444',
            };

            const map = L.map('mapGis', { fullscreenControl: true, zoomControl: true }).setView([-5.45, 105.27], 9);

            const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
            const sat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles © Esri'
            });

            osm.addTo(map);

            L.control.layers(
                { 'Street (OSM)': osm, 'Satellite': sat },
                {},
                { position: 'topright' }
            ).addTo(map);

            const baseStyle = (feature) => {
                const kondisi = feature?.properties?.kondisi;
                return { color: colors[kondisi] || '#94a3b8', weight: 5, opacity: 0.9 };
            };
            const activeStyle = (feature) => {
                const s = baseStyle(feature);
                return { ...s, weight: 9, opacity: 1 };
            };

            let activeLayer = null;

            const roadsLayer = L.geoJSON([], {
                style: baseStyle,
                onEachFeature: (feature, layer) => {
                    const p = feature.properties || {};
                    const foto = p.foto_url
                        ? `<div class="mt-2"><div class="small text-muted mb-1">Foto Jalan</div><img src="${p.foto_url}" alt="Foto Jalan" style="width:100%;max-width:260px;height:140px;object-fit:cover;border-radius:12px" /></div>`
                        : `<div class="mt-2"><div class="small text-muted mb-1">Foto Jalan</div><div class="small text-muted">Tidak ada foto.</div></div>`;
                    const html = `
                        <div style="min-width:240px">
                            <div style="font-weight:700;margin-bottom:6px">${p.nama_ruas ?? '-'}</div>
                            <div style="font-size:12px;color:#334155">
                                <div><b>Kabupaten:</b> ${p.kabupaten ?? '-'}</div>
                                <div><b>Kecamatan:</b> ${p.kecamatan ?? '-'}</div>
                                <div><b>Panjang:</b> ${(p.panjang ?? 0).toLocaleString('id-ID')} Km</div>
                                <div><b>Kondisi:</b> ${p.kondisi ?? '-'}</div>
                                <div><b>Prioritas:</b> ${p.prioritas ?? '-'}</div>
                                <div><b>Tahun:</b> ${p.tahun ?? '-'}</div>
                            </div>
                            ${foto}
                        </div>
                    `;
                    layer.bindPopup(html, { maxWidth: 320 });

                    layer.on('click', () => {
                        if (activeLayer && activeLayer !== layer && activeLayer.setStyle) {
                            activeLayer.setStyle(baseStyle(activeLayer.feature));
                        }
                        activeLayer = layer;
                        if (layer.setStyle) {
                            layer.setStyle(activeStyle(feature));
                        }
                        if (layer.getBounds) {
                            map.fitBounds(layer.getBounds(), { padding: [40, 40] });
                        }
                        layer.openPopup();
                    });
                }
            }).addTo(map);

            let heatLayer = L.heatLayer([], { radius: 22, blur: 18, maxZoom: 15, minOpacity: 0.25 });
            heatLayer.addTo(map);

            const legend = L.control({ position: 'bottomright' });
            legend.onAdd = () => {
                const div = L.DomUtil.create('div', 'gr-legend');
                div.innerHTML = `
                    <div style="font-weight:800;margin-bottom:8px">Legenda</div>
                    <div class="row"><span class="sw" style="background:${colors['Baik']}"></span> Baik</div>
                    <div class="row"><span class="sw" style="background:${colors['Rusak Ringan']}"></span> Rusak Ringan</div>
                    <div class="row"><span class="sw" style="background:${colors['Rusak Sedang']}"></span> Rusak Sedang</div>
                    <div class="row"><span class="sw" style="background:${colors['Rusak Berat']}"></span> Rusak Berat</div>
                `;
                return div;
            };
            legend.addTo(map);

            const fetchGeojson = async () => {
                const q = document.getElementById('gisSearch').value.trim();
                const kondisi = document.getElementById('gisFilterKondisi').value;
                const url = new URL(@json(route('webgis.roads')), window.location.origin);
                if (q) url.searchParams.set('q', q);
                if (kondisi) url.searchParams.set('kondisi', kondisi);

                const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
                const json = await res.json();

                roadsLayer.clearLayers();
                roadsLayer.addData(json);
                activeLayer = null;

                const heat = json?.meta?.heat ?? [];
                heatLayer.setLatLngs(heat);

                const bounds = roadsLayer.getBounds();
                if (bounds.isValid()) map.fitBounds(bounds, { padding: [24, 24] });
            };

            document.getElementById('gisApply').addEventListener('click', (e) => {
                e.preventDefault();
                fetchGeojson();
            });

            document.getElementById('gisSearch').addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    fetchGeojson();
                }
            });

            fetchGeojson();
        })();
    </script>
@endpush
