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
                <div class="fw-bold mb-1">Kerusakan per Kabupaten</div>
                <div class="text-muted small mb-3">Jumlah ruas dengan kondisi tidak baik.</div>
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
                'Rusak Sedang': '#fb923c',
                'Rusak Berat': '#ef4444',
            };

            const fetchAll = async () => {
                const res = await fetch(@json(route('webgis.roads')), { headers: { 'Accept': 'application/json' } });
                return res.json();
            };

            const build = (json) => {
                const features = json?.features ?? [];
                const byKondisi = {};
                const byKabRusak = {};
                features.forEach(f => {
                    const p = f.properties || {};
                    const kondisi = p.kondisi || '-';
                    const kab = p.kabupaten || '-';
                    byKondisi[kondisi] = (byKondisi[kondisi] || 0) + 1;
                    if (kondisi !== 'Baik') byKabRusak[kab] = (byKabRusak[kab] || 0) + 1;
                });

                const pieEl = document.getElementById('statPie');
                if (pieEl && window.Chart) {
                    const labels = Object.keys(byKondisi);
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

                const sortedKab = Object.entries(byKabRusak).sort((a,b) => b[1]-a[1]).slice(0, 10);
                const barEl = document.getElementById('statBar');
                if (barEl && window.Chart) {
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
                        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
                    });
                }

                const total = features.length;
                const baik = byKondisi['Baik'] || 0;
                const rusak = total - baik;
                const berat = byKondisi['Rusak Berat'] || 0;

                const summary = document.getElementById('statSummary');
                summary.innerHTML = `
                    <div class="col-md-3"><div class="p-3 rounded-4 border bg-light"><div class="text-muted small">Total</div><div class="fw-bold fs-4">${total.toLocaleString('id-ID')}</div></div></div>
                    <div class="col-md-3"><div class="p-3 rounded-4 border bg-light"><div class="text-muted small">Baik</div><div class="fw-bold fs-4">${baik.toLocaleString('id-ID')}</div></div></div>
                    <div class="col-md-3"><div class="p-3 rounded-4 border bg-light"><div class="text-muted small">Rusak</div><div class="fw-bold fs-4">${rusak.toLocaleString('id-ID')}</div></div></div>
                    <div class="col-md-3"><div class="p-3 rounded-4 border bg-light"><div class="text-muted small">Rusak Berat</div><div class="fw-bold fs-4">${berat.toLocaleString('id-ID')}</div></div></div>
                `;
            };

            fetchAll().then(build).catch(() => {});
        })();
    </script>
@endpush
