@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3" data-aos="fade-up">
        <div class="text-dark">
            <div class="h4 fw-bold mb-0">Statistik</div>
            <div class="text-muted">Analisis cepat kondisi dan sebaran kerusakan.</div>
        </div>
        <a class="btn btn-outline-dark rounded-4" href="{{ route('admin.roads.index') }}">
            <i class="bi bi-signpost-2 me-1"></i> Data Jalan
        </a>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-lg-6" data-aos="fade-up">
            <div class="gr-card p-4">
                <div class="fw-bold mb-1">Kondisi Jalan</div>
                <div class="text-muted small mb-3">Distribusi kondisi dari semua ruas jalan.</div>
                <div style="height:360px">
                    <canvas id="statPie"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-up">
            <div class="gr-card p-4">
                <div class="fw-bold mb-1">Kerusakan per Kabupaten/Kota</div>
                <div class="text-muted small mb-3">Top 15 kabupaten/kota berdasarkan jumlah ruas kondisi tidak baik.</div>
                <div style="height:360px">
                    <canvas id="statBar"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="gr-card p-4 mt-3" data-aos="fade-up">
        <div class="fw-bold mb-2">Ringkasan</div>
        <div class="text-muted small">Data dihitung dari layer yang sama dengan WebGIS.</div>
        <div class="row g-2 mt-2" id="statSummary"></div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            if (window.Chart) {
                Chart.defaults.color = 'rgba(15,23,42,.88)';
                Chart.defaults.borderColor = 'rgba(148,163,184,.35)';
            }
            const colors = {
                'Baik': '#22c55e',
                'Rusak Ringan': '#facc15',
                'Rusak Sedang': '#2563eb',
                'Rusak Berat': '#ef4444',
            };

            const fetchAll = async () => {
                const res = await fetch(@json(route('webgis.roads')), { headers: { 'Accept': 'application/json' } });
                return res.json();
            };

            const kabupatenLampung = @json(\App\Models\Road::kabupatenOptions());

            const build = (json) => {
                const features = json?.features ?? [];
                const byKondisi = {};
                const byKabRusak = {};

                // Inisialisasi semua kabupaten dengan 0
                kabupatenLampung.forEach(kab => {
                    byKabRusak[kab] = 0;
                });

                features.forEach(f => {
                    const p = f.properties || {};
                    const kondisi = p.kondisi || '-';
                    const kab = p.kabupaten || '-';
                    byKondisi[kondisi] = (byKondisi[kondisi] || 0) + 1;
                    if (kondisi !== 'Baik') {
                        byKabRusak[kab] = (byKabRusak[kab] || 0) + 1;
                    }
                });

                const pieEl = document.getElementById('statPie');
                if (pieEl && window.Chart) {
                    const preferredOrder = ['Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'];
                    const labels = preferredOrder.filter(l => Object.prototype.hasOwnProperty.call(byKondisi, l)).concat(
                        Object.keys(byKondisi).filter(l => !preferredOrder.includes(l))
                    );
                    new Chart(pieEl, {
                        type: 'doughnut',
                        data: {
                            labels,
                            datasets: [{
                                data: labels.map(l => byKondisi[l]),
                                backgroundColor: labels.map(l => colors[l] || '#94a3b8'),
                                borderWidth: 0,
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                    });
                }

                const sortedKab = Object.entries(byKabRusak).sort((a,b) => b[1]-a[1]).slice(0, 15);
                const barEl = document.getElementById('statBar');
                if (barEl && window.Chart) {
                    const barWrap = barEl.closest('[style*="height"]');
                    if (barWrap && Array.isArray(sortedKab)) {
                        barWrap.style.height = `${Math.max(360, (sortedKab.length * 22) + 120)}px`;
                    }

                    new Chart(barEl, {
                        type: 'bar',
                        data: {
                            labels: sortedKab.map(x => x[0]),
                            datasets: [{
                                label: 'Jumlah Rusak',
                                data: sortedKab.map(x => x[1]),
                                backgroundColor: 'rgba(250,204,21,.75)',
                                borderRadius: 10,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            scales: { x: { beginAtZero: true }, y: { ticks: { autoSkip: false } } },
                            plugins: { legend: { display: false } }
                        }
                    });
                }

                const baik = byKondisi['Baik'] || 0;
                const ringan = byKondisi['Rusak Ringan'] || 0;
                const sedang = byKondisi['Rusak Sedang'] || 0;
                const berat = byKondisi['Rusak Berat'] || 0;

                const summary = document.getElementById('statSummary');
                summary.innerHTML = `
                    <div class="col-md-3">
                        <div class="gr-hover-up h-100 rounded-4 p-3 text-white" style="background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);box-shadow:0 18px 50px rgba(34,197,94,.16)">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small" style="opacity:.86">Baik</div>
                                    <div class="fs-4 fw-bold">${baik.toLocaleString('id-ID')}</div>
                                </div>
                                <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.22)">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="gr-hover-up h-100 rounded-4 p-3 text-white" style="background:linear-gradient(135deg,#facc15 0%,#eab308 100%);box-shadow:0 18px 50px rgba(250,204,21,.18)">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small" style="opacity:.86;color:rgba(15,23,42,.92)">Rusak Ringan</div>
                                    <div class="fs-4 fw-bold" style="color:rgba(15,23,42,.95)">${ringan.toLocaleString('id-ID')}</div>
                                </div>
                                <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,.26);border:1px solid rgba(255,255,255,.34)">
                                    <i class="bi bi-exclamation-circle" style="color:rgba(15,23,42,.92)"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="gr-hover-up h-100 rounded-4 p-3 text-white" style="background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);box-shadow:0 18px 50px rgba(37,99,235,.20)">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small" style="opacity:.86">Rusak Sedang</div>
                                    <div class="fs-4 fw-bold">${sedang.toLocaleString('id-ID')}</div>
                                </div>
                                <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.22)">
                                    <i class="bi bi-cone-striped"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="gr-hover-up h-100 rounded-4 p-3 text-white" style="background:linear-gradient(135deg,#ef4444 0%,#b91c1c 100%);box-shadow:0 18px 50px rgba(239,68,68,.18)">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small" style="opacity:.86">Rusak Berat</div>
                                    <div class="fs-4 fw-bold">${berat.toLocaleString('id-ID')}</div>
                                </div>
                                <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.22)">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            };

            fetchAll().then(build).catch(() => {});
        })();
    </script>
@endpush
