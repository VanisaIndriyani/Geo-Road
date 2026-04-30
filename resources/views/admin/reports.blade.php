@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3" data-aos="fade-up">
        <div class="text-dark">
            <div class="h4 fw-bold mb-0">Laporan</div>
            <div class="text-muted">Unduh laporan Data Jalan dalam format PDF atau Excel.</div>
        </div>
        <a class="btn btn-outline-dark rounded-4" href="{{ route('admin.roads.index') }}">
            <i class="bi bi-signpost-2 me-1"></i> Data Jalan
        </a>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-lg-6" data-aos="fade-up">
            <div class="gr-card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:54px;height:54px;background:rgba(250,204,21,.22);border:1px solid rgba(250,204,21,.35)">
                        <i class="bi bi-file-earmark-pdf fs-4" style="color:#0f172a"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">Export PDF</div>
                        <div class="text-muted small">Format siap cetak (A4 landscape) untuk kebutuhan laporan.</div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a class="btn gr-btn-gold rounded-4" href="{{ route('admin.roads.export.pdf') }}">
                        <i class="bi bi-download me-1"></i> Download PDF
                    </a>
                    <a class="btn btn-outline-dark rounded-4" href="{{ route('admin.roads.index') }}">
                        <i class="bi bi-funnel me-1"></i> Filter Data
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-up">
            <div class="gr-card p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-inline-flex align-items-center justify-content-center" style="width:54px;height:54px;background:rgba(234,179,8,.20);border:1px solid rgba(234,179,8,.35)">
                        <i class="bi bi-file-earmark-excel fs-4" style="color:#0f172a"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">Export Excel</div>
                        <div class="text-muted small">Format spreadsheet untuk pengolahan data lanjutan.</div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a class="btn gr-btn-gold rounded-4" href="{{ route('admin.roads.export.excel') }}">
                        <i class="bi bi-download me-1"></i> Download Excel
                    </a>
                    <a class="btn btn-outline-dark rounded-4" href="{{ route('admin.roads.index') }}">
                        <i class="bi bi-funnel me-1"></i> Filter Data
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
