@extends('layouts.app')

@section('content')
    <div class="row g-3 g-lg-4 mb-3" data-aos="fade-up">
        <div class="col-md-6 col-xl-3">
            <div class="gr-hover-up h-100 rounded-4 p-4 text-white" style="background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);box-shadow:0 18px 50px rgba(34,197,94,.16)">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small" style="opacity:.86">Baik</div>
                        <div class="fs-2 fw-bold">{{ number_format($stats['baik'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.22)">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="gr-hover-up h-100 rounded-4 p-4 text-white" style="background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);box-shadow:0 18px 50px rgba(37,99,235,.20)">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small" style="opacity:.86">Sedang</div>
                        <div class="fs-2 fw-bold">{{ number_format($stats['rusak_sedang'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.22)">
                        <i class="bi bi-cone-striped"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="gr-hover-up h-100 rounded-4 p-4 text-white" style="background:linear-gradient(135deg,#facc15 0%,#eab308 100%);box-shadow:0 18px 50px rgba(250,204,21,.18)">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small" style="opacity:.86;color:rgba(15,23,42,.92)">Rusak Ringan</div>
                        <div class="fs-2 fw-bold" style="color:rgba(15,23,42,.95)">{{ number_format($stats['rusak_ringan'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(255,255,255,.26);border:1px solid rgba(255,255,255,.34)">
                        <i class="bi bi-exclamation-circle" style="color:rgba(15,23,42,.92)"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="gr-hover-up h-100 rounded-4 p-4 text-white" style="background:linear-gradient(135deg,#ef4444 0%,#b91c1c 100%);box-shadow:0 18px 50px rgba(239,68,68,.18)">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small" style="opacity:.86">Rusak Berat</div>
                        <div class="fs-2 fw-bold">{{ number_format($stats['rusak_berat'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.22)">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-lg-7" data-aos="fade-up">
            <div class="gr-card p-4 mb-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="fw-bold">Kondisi Jalan</div>
                        <div class="small text-muted">Distribusi data berdasarkan kondisi terbaru.</div>
                    </div>
                    <a href="{{ route('admin.roads.index') }}" class="btn btn-sm gr-btn-gold rounded-4">
                        <i class="bi bi-arrow-right-circle me-1"></i> Kelola Data
                    </a>
                </div>
                <div style="height:320px">
                    <canvas id="chartKondisi"></canvas>
                </div>
            </div>
            <div class="gr-card p-4">
                <div class="fw-bold">Kerusakan per Kabupaten</div>
                <div class="small text-muted mb-3">Top kabupaten berdasarkan jumlah ruas kondisi tidak baik.</div>
                <div style="height:320px">
                    <canvas id="chartKabupaten"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5" data-aos="fade-up">
            <div class="gr-card p-4 h-100">
                <div class="fw-bold mb-2">Update Terbaru</div>
                <div class="small text-muted mb-3">Ruas jalan yang terakhir ditambahkan.</div>
                <div class="list-group list-group-flush">
                    @forelse ($recentRoads as $road)
                        <a href="{{ route('admin.roads.show', $road) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $road->nama_ruas }}</div>
                                <div class="small text-muted">{{ $road->kabupaten }} • {{ $road->kondisi }}</div>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    @empty
                        <div class="text-muted">Belum ada data.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            if (window.Chart) {
                Chart.defaults.color = 'rgba(15,23,42,.88)';
                Chart.defaults.borderColor = 'rgba(148,163,184,.35)';
            }
            const el = document.getElementById('chartKondisi');
            if (!el || !window.Chart) return;

            const data = @json($kondisiBreakdown ?? []);
            const labels = Object.keys(data);
            const values = Object.values(data);

            const colors = {
                'Baik': '#22c55e',
                'Rusak Ringan': '#facc15',
                'Rusak Sedang': '#fb923c',
                'Rusak Berat': '#ef4444',
            };

            new Chart(el, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: labels.map(l => colors[l] || '#94a3b8'),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            const kabEl = document.getElementById('chartKabupaten');
            if (!kabEl) return;
            const kab = @json(($rusakKabupaten ?? collect())->map(fn($r) => ['kabupaten' => $r->kabupaten, 'total' => (int) $r->total])->values());

            new Chart(kabEl, {
                type: 'bar',
                data: {
                    labels: kab.map(x => x.kabupaten),
                    datasets: [{
                        label: 'Jumlah Rusak',
                        data: kab.map(x => x.total),
                        backgroundColor: 'rgba(250,204,21,.80)',
                        borderRadius: 10,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });
        })();
    </script>
@endpush
