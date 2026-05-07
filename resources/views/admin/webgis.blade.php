@extends('layouts.app')

@section('content')
    <div class="gr-webgis-layout">
        <div class="gr-webgis-card gr-card p-0" data-aos="fade-up">
            <div class="gr-map-shell">
                <div class="gr-webgis-overlay">
                    <div class="gr-webgis-desc">PETA INTERAKTIF KONDISI RUAS JALAN PROVINSI LAMPUNG.</div>
                    <div class="gr-webgis-tools">
                        <input type="text" class="form-control rounded-4" id="gisSearch" placeholder="Search nama ruas jalan...">
                        <select class="form-select rounded-4" id="gisFilterKondisi">
                            <option value="">Semua Kondisi</option>
                            @foreach ($kondisiOptions as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-dark rounded-4" id="gisApply">
                            <i class="bi bi-funnel me-1"></i> Terapkan
                        </button>
                    </div>
                </div>
                <div id="mapGis" class="gr-map"></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.css">
    <style>
        .leaflet-control-layers{border-radius:14px;overflow:hidden}
        .gr-legend-float{
            background: rgba(255,255,255,0.95);
            border-radius: 12px;
            padding: 10px 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            font-size: 12px;
        }
        .gr-legend-float .sw{
            display:inline-block;
            width:12px;
            height:10px;
            border-radius:4px;
            margin-right:8px;
        }
        .gr-legend-float .row{display:flex;align-items:center;margin-bottom:6px}
        .gr-legend-float .row:last-child{margin-bottom:0}
        .leaflet-top.leaflet-right .gr-legend-float{margin-top:150px;margin-right:28px}
        @media (max-width: 992px){
            .leaflet-top.leaflet-right .gr-legend-float{margin-top:120px;margin-right:10px}
        }
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
                'Rusak Sedang': '#2563eb',
                'Rusak Berat': '#ef4444',
            };

            const lampungBoundsBase = [
                [-6.5, 103.5],
                [-3.5, 106.0],
            ];

            const shrinkBounds = (bounds, factor) => {
                const sw = bounds[0];
                const ne = bounds[1];
                const cLat = (sw[0] + ne[0]) / 2;
                const cLng = (sw[1] + ne[1]) / 2;
                const halfLat = ((ne[0] - sw[0]) / 2) * factor;
                const halfLng = ((ne[1] - sw[1]) / 2) * factor;
                return [
                    [cLat - halfLat, cLng - halfLng],
                    [cLat + halfLat, cLng + halfLng],
                ];
            };

            const lampungBounds = shrinkBounds(lampungBoundsBase, 0.45);

            const map = L.map('mapGis', {
                fullscreenControl: false, // Set false agar bisa dipindah posisinya
                zoomControl: false
            });

            // Tambahkan zoom & fullscreen di posisi bawah kiri agar tidak menabrak teks judul
            L.control.zoom({ position: 'bottomleft' }).addTo(map);
            L.control.fullscreen({ position: 'bottomleft' }).addTo(map);

            const fitLampung = () => {
                map.fitBounds(lampungBounds, {
                    paddingTopLeft: [24, 80],
                    paddingBottomRight: [24, 24],
                    maxZoom: 12,
                    animate: true,
                    duration: 1,
                });
            };

            const safeInvalidateSize = () => {
                try { map.invalidateSize(); } catch (e) {}
            };
            window.addEventListener('resize', safeInvalidateSize);
            const sidebarToggle = document.getElementById('grSidebarToggle');
            if (sidebarToggle) sidebarToggle.addEventListener('click', () => setTimeout(safeInvalidateSize, 260));
            setTimeout(safeInvalidateSize, 0);

            const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
            const sat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles © Esri'
            });

            osm.addTo(map);

            fitLampung();

            const layersControl = L.control.layers(
                { 'Street (OSM)': osm, 'Satellite': sat },
                {},
                { position: 'topright' }
            );
            layersControl.addTo(map);

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
                    const awal = (p.awal_lat != null && p.awal_lng != null) ? `${Number(p.awal_lat).toFixed(6)} / ${Number(p.awal_lng).toFixed(6)}` : '-';
                    const akhir = (p.akhir_lat != null && p.akhir_lng != null) ? `${Number(p.akhir_lat).toFixed(6)} / ${Number(p.akhir_lng).toFixed(6)}` : '-';
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
                                <div><b>Koordinat Awal:</b> ${awal}</div>
                                <div><b>Koordinat Akhir:</b> ${akhir}</div>
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

            const legendControl = L.control({ position: 'topright' });
            legendControl.onAdd = () => {
                const div = L.DomUtil.create('div', 'gr-legend-float');
                div.innerHTML = `
                    <div style="font-weight:800;margin-bottom:8px">Legenda</div>
                    <div class="row"><span class="sw" style="background:${colors['Baik']}"></span> Baik</div>
                    <div class="row"><span class="sw" style="background:${colors['Rusak Ringan']}"></span> Rusak Ringan</div>
                    <div class="row"><span class="sw" style="background:${colors['Rusak Sedang']}"></span> Rusak Sedang</div>
                    <div class="row"><span class="sw" style="background:${colors['Rusak Berat']}"></span> Rusak Berat</div>
                `;
                return div;
            };
            legendControl.addTo(map);

            let hasUserApplied = false;

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

                if (!hasUserApplied) {
                    return;
                }

                const bounds = roadsLayer.getBounds();
                if (bounds.isValid() && (q || kondisi)) {
                    map.fitBounds(bounds, {
                        paddingTopLeft: [24, 110],
                        paddingBottomRight: [24, 24],
                        maxZoom: 12,
                        animate: true,
                        duration: 1,
                    });
                } else {
                    fitLampung();
                }
            };

            document.getElementById('gisApply').addEventListener('click', (e) => {
                e.preventDefault();
                hasUserApplied = true;
                fetchGeojson();
            });

            document.getElementById('gisSearch').addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    hasUserApplied = true;
                    fetchGeojson();
                }
            });

            fetchGeojson();
        })();
    </script>
@endpush
