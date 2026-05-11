<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Geo-Road — WebGIS</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@2.4.0/Control.FullScreen.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{
            --gr-gold:#facc15;
            --gr-gold-2:#eab308;
            --gr-navy:#0f172a;
            --gr-overlay:rgba(2,6,23,.75);
            --gr-bg:#f8fafc;
            --gr-text:#111827;
            --gr-sub:#6b7280;
            --gr-border:rgba(148,163,184,.22);
        }
        body{margin:0}
        .grw-body{background:var(--gr-bg);color:var(--gr-text)}
        .gr-webgis{height:100vh}
        #mapPublic{height:100vh;height:100dvh}

        .grw-navbar{
            background: rgba(255,255,255,.70);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(148,163,184,.18);
        }
        .grw-brandmark{
            width: 42px;
            height: 42px;
            border-radius: 16px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, var(--gr-navy) 0%, rgba(2,6,23,.92) 100%);
            box-shadow: 0 16px 40px rgba(2,6,23,.18);
        }
        .grw-brandmark i{color: var(--gr-gold); font-size: 18px}
        .grw-navlink{
            color: rgba(15,23,42,.86) !important;
            font-weight: 600;
            transition: color .18s ease;
        }
        .grw-navlink:hover{color: rgba(234,179,8,1) !important}
        .grw-btn-primary{
            background: linear-gradient(135deg, var(--gr-gold) 0%, var(--gr-gold-2) 100%);
            color: #111827;
            border: 0;
            font-weight: 800;
            box-shadow: 0 16px 40px rgba(250,204,21,.22);
        }
        .grw-btn-primary:hover{filter:brightness(.98)}

        .gr-webgis-ui{
            position:fixed;
            top:92px;
            left:16px;
            z-index: 999;
            width: min(520px, calc(100vw - 32px));
        }
        .grw-panel{
            border-radius: 22px;
            background: rgba(255,255,255,.78);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(148,163,184,.20);
            box-shadow: 0 22px 80px rgba(2,6,23,.14);
            overflow: hidden;
        }
        .grw-panel-header{
            padding: 14px 16px 10px;
            border-bottom: 1px solid rgba(148,163,184,.16);
        }
        .grw-line{
            width: 38px;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--gr-gold) 0%, var(--gr-gold-2) 100%);
        }
        .grw-panel-body{padding: 14px 16px 16px}
        .grw-help{color: var(--gr-sub)}
        .grw-input-icon{
            background: rgba(255,255,255,.88);
            border-color: rgba(148,163,184,.28);
            color: rgba(15,23,42,.72);
        }

        .gr-legend{
            background: rgba(255,255,255,.78);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(148,163,184,.20);
            border-radius: 18px;
            padding: 12px 14px;
            box-shadow: 0 22px 80px rgba(2,6,23,.14);
            font-size: 12px;
            color: rgba(15,23,42,.92);
        }
        .gr-legend .sw{display:inline-block;width:14px;height:10px;border-radius:4px;margin-right:8px}
        .gr-legend .row{display:flex;align-items:center;margin-bottom:6px}
        .gr-legend .row:last-child{margin-bottom:0}

        .leaflet-control-layers,
        .leaflet-control-zoom,
        .leaflet-control-fullscreen{
            box-shadow: 0 18px 60px rgba(2,6,23,.14) !important;
            border-radius: 14px !important;
            overflow: hidden;
            border: 1px solid rgba(148,163,184,.20);
        }
        .leaflet-control-layers a,
        .leaflet-bar a{
            background: rgba(255,255,255,.86) !important;
            color: rgba(15,23,42,.90) !important;
        }
        .leaflet-bar a:hover{background: rgba(255,255,255,.96) !important}

        .leaflet-bottom .leaflet-control{
            margin-bottom: calc(16px + env(safe-area-inset-bottom));
        }
        .leaflet-top .leaflet-control{
            margin-top: calc(16px + env(safe-area-inset-top));
        }
        .leaflet-right .leaflet-control{
            margin-right: calc(16px + env(safe-area-inset-right));
        }
        .leaflet-left .leaflet-control{
            margin-left: calc(16px + env(safe-area-inset-left));
        }

        @media (max-width: 575.98px){
            .gr-webgis-ui{left: 12px; width: calc(100vw - 24px)}
        }
    </style>
</head>
<body class="grw-body">
    <div id="gr-loader" class="gr-loader">
        <div class="text-center text-white">
            <div class="gr-spinner mx-auto mb-3"></div>
            <div class="fw-semibold">Geo-Road WebGIS</div>
            <div class="small text-white-50">Memuat peta...</div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top grw-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="grw-brandmark"><i class="bi bi-map-fill"></i></span>
                <span class="fw-bold" style="letter-spacing:.3px">Geo-Road</span>
                <span class="d-none d-md-inline small text-muted ms-2">WebGIS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link grw-navlink" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link grw-navlink active" href="{{ route('webgis.public') }}">WebGIS</a></li>
                    @auth
                        <li class="nav-item ms-lg-2"><a class="btn btn-sm grw-btn-primary rounded-4 px-3 py-2" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                    @else
                        <li class="nav-item ms-lg-2"><a class="btn btn-sm grw-btn-primary rounded-4 px-3 py-2" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="gr-webgis">
        <div id="mapPublic"></div>

        <div class="gr-webgis-ui" data-aos="fade-right">
            <div class="grw-panel">
                <div class="grw-panel-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="grw-line mb-2"></div>
                            <div class="fw-bold">Kontrol Peta</div>
                            <div class="small grw-help">Cari ruas jalan dan filter kondisi.</div>
                        </div>
                        <i class="bi bi-sliders" style="color:rgba(15,23,42,.70)"></i>
                    </div>
                </div>
                <div class="grw-panel-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text grw-input-icon rounded-start-4"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control rounded-end-4" id="gisSearch" placeholder="Cari nama ruas jalan...">
                            </div>
                        </div>
                        <div class="col-8">
                            <select class="form-select rounded-4" id="gisFilterKondisi">
                                <option value="">Semua Kondisi</option>
                                @foreach ($kondisiOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 d-grid">
                            <button class="btn grw-btn-primary rounded-4" id="gisApply"><i class="bi bi-funnel me-1"></i> Terapkan</button>
                        </div>
                    </div>
                    <div class="small grw-help mt-2">
                        Tips: tekan Enter untuk mencari cepat.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
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

            const map = L.map('mapPublic', {
                fullscreenControl: false,
                zoomControl: false
            }).setView([-5.45, 105.27], 9);

            L.control.zoom({ position: 'bottomleft' }).addTo(map);
            L.control.fullscreen({ position: 'bottomleft' }).addTo(map);
            const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
            const sat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19, attribution: 'Tiles © Esri' });
            osm.addTo(map);

            L.control.layers({ 'Street (OSM)': osm, 'Satellite': sat }, {}, { position: 'topright' }).addTo(map);

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
                                <div><b>Penanganan:</b> ${p.prioritas ?? '-'}</div>
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

            let heatLayer = L.heatLayer([], { radius: 22, blur: 18, maxZoom: 15, minOpacity: 0.25 }).addTo(map);

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
</body>
</html>
