@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3" data-aos="fade-up">
        <div class="text-dark">
            <div class="h4 fw-bold mb-0">Data Jalan</div>
            <div class="text-muted">CRUD, filter, dan export data kerusakan jalan.</div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-dark rounded-4" href="{{ route('admin.roads.export.pdf', request()->query()) }}">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </a>
            <a class="btn btn-outline-dark rounded-4" href="{{ route('admin.roads.export.excel', request()->query()) }}">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a class="btn gr-btn-gold rounded-4" href="{{ route('admin.roads.create') }}">
                <i class="bi bi-plus-circle me-1"></i> Tambah Data
            </a>
        </div>
    </div>

    <div class="gr-card p-4 mb-3" data-aos="fade-up">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Pencarian</label>
                <input type="text" class="form-control rounded-4" name="q" value="{{ $q }}" placeholder="Cari nama ruas / kabupaten / kecamatan...">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Filter Kondisi</label>
                <select class="form-select rounded-4" name="kondisi">
                    <option value="">Semua Kondisi</option>
                    @foreach ($kondisiOptions as $opt)
                        <option value="{{ $opt }}" @selected($kondisi === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-dark rounded-4" type="submit">
                    <i class="bi bi-search me-1"></i> Terapkan
                </button>
            </div>
        </form>
    </div>

    <div class="gr-card p-0 overflow-hidden" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table gr-table mb-0">
                <thead>
                    <tr>
                        <th style="width:70px">Foto</th>
                        <th>Nama Ruas</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th class="text-end">Panjang (Km)</th>
                        <th>Kondisi</th>
                        <th>Prioritas</th>
                        <th class="text-end" style="width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roads as $road)
                        <tr>
                            <td>
                                @if ($road->foto_url)
                                    <img src="{{ $road->foto_url }}" alt="Foto" class="rounded-3" style="width:56px;height:42px;object-fit:cover">
                                @else
                                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:56px;height:42px;background:#f1f5f9;border:1px solid rgba(148,163,184,.25)">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">
                                <a href="{{ route('admin.roads.show', $road) }}" class="text-decoration-none text-dark">
                                    {{ $road->nama_ruas }}
                                </a>
                                <div class="small text-muted">Tahun: {{ $road->tahun ?? '-' }}</div>
                            </td>
                            <td>{{ $road->kabupaten }}</td>
                            <td>{{ $road->kecamatan }}</td>
                            <td class="text-end">{{ number_format((float) $road->panjang, 2, ',', '.') }}</td>
                            <td>
                                @php
                                    $badge = match ($road->kondisi) {
                                        'Baik' => 'success',
                                        'Rusak Ringan' => 'warning',
                                        'Rusak Sedang' => 'primary',
                                        'Rusak Berat' => 'danger',
                                        default => 'secondary',
                                    };
                                    $badgeClass = "text-bg-{$badge}";
                                @endphp
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                                    {{ $road->kondisi }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 text-bg-warning text-dark border border-dark-subtle">
                                    {{ $road->prioritas ?? '-' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark rounded-4" href="{{ route('admin.roads.edit', $road) }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-dark rounded-4" href="{{ route('admin.roads.show', $road) }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form id="delete-road-{{ $road->id }}" method="POST" action="{{ route('admin.roads.destroy', $road) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-4" data-confirm-delete="1" data-form-id="delete-road-{{ $road->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                Tidak ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $roads->links() }}
        </div>
    </div>
@endsection
