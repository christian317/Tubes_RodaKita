@extends('layouts.mitra')

@section('title', 'Ulasan Pelanggan - Roda Kita')
@section('page_title', 'Ulasan Pelanggan')
@section('breadcrumb', 'Mitra / Ulasan Armada')

@section('content')

    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-star-half text-warning me-2"></i>Penilaian Armada Kendaraan Anda</h5>
            <p class="text-muted small mb-0 mt-1">Pantau performa dan tingkat kepuasan pelanggan terhadap masing-masing unit mobil Anda.</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3 fw-semibold border-bottom-0">Unit Mobil</th>
                            <th class="py-3 fw-semibold border-bottom-0 text-center">Total Ulasan</th>
                            <th class="py-3 fw-semibold border-bottom-0 text-center">Rating Rata-rata</th>
                            <th class="py-3 pe-4 fw-semibold border-bottom-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top">
                        @forelse($mobils as $m)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light rounded-3 border overflow-hidden d-flex justify-content-center align-items-center" style="width: 70px; height: 50px;">
                                        @if($m->gambar)
                                            <img src="{{ asset('storage/' . $m->gambar) }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <i class="bi bi-car-front text-muted fs-4"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $m->brand->nama_brand ?? '' }} {{ $m->model }}</div>
                                        <span class="badge bg-light border text-dark font-monospace mt-1" style="font-size: 0.75rem;">{{ $m->plat_nomer }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                @if($m->ulasans_count > 0)
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">{{ $m->ulasans_count }} Penilaian</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill">0 Penilaian</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @if($m->ulasans_avg_rating)
                                    <div class="d-flex align-items-center justify-content-center text-warning fs-5">
                                        <i class="bi bi-star-fill me-2"></i>
                                        <span class="fw-bold text-dark">{{ number_format($m->ulasans_avg_rating, 1) }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Belum ada rating</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <a href="{{ route('mitra.ulasanPelanggan.detail', $m->id) }}" class="btn btn-sm btn-outline-primary fw-bold rounded-3 px-3 shadow-sm">
                                    Lihat Detail Ulasan <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-star display-1 text-muted opacity-25 d-block mb-3"></i>
                                <h6 class="fw-bold text-dark">Anda belum mendaftarkan armada</h6>
                                <p class="text-muted small mb-0">Hubungi admin untuk mendaftarkan armada Anda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection