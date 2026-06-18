@extends('layouts.mitra')

@section('title', 'Klaim Asuransi - Roda Kita')
@section('page_title', 'Klaim Asuransi')
@section('breadcrumb', 'Mitra / Klaim Asuransi')

@section('content')

@if(session('success'))
    <div class="alert alert-success rounded-3">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0"><i class="bi bi-shield-exclamation text-danger me-2"></i>Daftar Klaim Asuransi</h5>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold border-bottom-0">Booking</th>
                        <th class="py-3 fw-semibold border-bottom-0">Deskripsi</th>
                        <th class="py-3 fw-semibold border-bottom-0">Estimasi</th>
                        <th class="py-3 fw-semibold border-bottom-0">Status</th>
                        <th class="pe-4 py-3 fw-semibold border-bottom-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top">
                    @forelse($klaims as $klaim)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark small">{{ $klaim->booking->mobil->brand->nama_brand ?? '' }} {{ $klaim->booking->mobil->model ?? '' }}</div>
                                <div class="text-muted small">#{{ $klaim->id_booking }}</div>
                            </td>
                            <td class="py-3 text-dark small">{{ Str::limit($klaim->deskripsi_kerusakan, 60) }}</td>
                            <td class="py-3 fw-bold text-dark">Rp {{ number_format($klaim->estimasi_biaya, 0, ',', '.') }}</td>
                            <td class="py-3">
                                @if($klaim->status == 'diajukan')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill">Diajukan</span>
                                @elseif($klaim->status == 'ditinjau')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2 rounded-pill">Ditinjau</span>
                                @elseif($klaim->status == 'disetujui')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">Disetujui</span>
                                @elseif($klaim->status == 'ditolak')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill">Selesai</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <a href="{{ route('mitra.klaim.detail', $klaim->id) }}" class="btn btn-sm btn-outline-primary rounded-3">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-shield-exclamation display-1 text-muted opacity-25 d-block mb-3"></i>
                                <h6 class="fw-bold text-dark">Belum ada klaim</h6>
                                <p class="text-muted small mb-0">Ajukan klaim dari halaman Monitoring Mobil untuk booking yang selesai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
