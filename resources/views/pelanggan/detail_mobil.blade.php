@extends('layouts.pelanggan')

@section('title', 'Detail Mobil - ' . $mobil->model)

@section('content')
<div class="container py-4 py-lg-5">
    
    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm fw-medium">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
        </a>
    </div>

    <div class="row g-4 g-lg-5">
        {{-- KOLOM KIRI: FOTO MOBIL --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="top: 100px;">
                <div class="bg-light" style="height: 400px;">
                    @if($mobil->gambar)
                        <img src="{{ asset('storage/' . $mobil->gambar) }}" class="w-100 h-100 object-fit-cover" alt="{{ $mobil->model }}">
                    @else
                        <div class="w-100 h-100 d-flex justify-content-center align-items-center text-muted flex-column">
                            <i class="bi bi-car-front display-1 opacity-25"></i>
                            <span class="mt-3 fw-medium">Tidak ada foto</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DETAIL INFORMASI --}}
        <div class="col-lg-5">
            <div class="mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary border px-3 py-2 rounded-pill fw-semibold mb-2">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ $mobil->status_mobil }}
                </span>
                <div class="text-muted fw-bold text-uppercase letter-spacing-1 mt-2">{{ $mobil->brand->nama_brand ?? 'Merk Tidak Diketahui' }}</div>
                <h2 class="fw-bold text-dark display-6 mb-3">{{ $mobil->model }}</h2>
            </div>

            <div class="d-flex align-items-end mb-4 pb-3 border-bottom">
                <div>
                    <div class="text-muted fw-medium mb-1">Harga Sewa</div>
                    <div class="text-success fw-bold lh-1" style="font-size: 2.5rem;">
                        Rp {{ number_format($mobil->harga_sewa, 0, ',', '.') }}<span class="fs-5 text-muted fw-normal">/hari</span>
                    </div>
                </div>
            </div>

            {{-- Grid Spesifikasi --}}
            <h6 class="fw-bold text-dark mb-3">Spesifikasi Kendaraan</h6>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="bg-light rounded-3 p-3 d-flex align-items-center gap-3 border">
                        <i class="bi bi-grid-fill fs-4 text-primary"></i>
                        <div>
                            <div class="small text-muted mb-0 lh-1">Kategori</div>
                            <div class="fw-bold text-dark">{{ $mobil->kategori->nama_kategori ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light rounded-3 p-3 d-flex align-items-center gap-3 border">
                        <i class="bi bi-gear-fill fs-4 text-primary"></i>
                        <div>
                            <div class="small text-muted mb-0 lh-1">Transmisi</div>
                            <div class="fw-bold text-dark">{{ $mobil->transmisi }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light rounded-3 p-3 d-flex align-items-center gap-3 border">
                        <i class="bi bi-people-fill fs-4 text-primary"></i>
                        <div>
                            <div class="small text-muted mb-0 lh-1">Kapasitas</div>
                            <div class="fw-bold text-dark">{{ $mobil->kapasitas_penumpang }} Orang</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light rounded-3 p-3 d-flex align-items-center gap-3 border">
                        <i class="bi bi-calendar-check-fill fs-4 text-primary"></i>
                        <div>
                            <div class="small text-muted mb-0 lh-1">Tahun</div>
                            <div class="fw-bold text-dark">{{ $mobil->tahun }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <h6 class="fw-bold text-dark mb-2">Deskripsi & Catatan</h6>
            <div class="bg-light p-3 rounded-3 border mb-4 text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                {{ $mobil->deskripsi ?: 'Tidak ada deskripsi khusus untuk mobil ini.' }}
            </div>

            {{-- Info Mitra (Opsional, bisa dihilangkan jika tidak ingin dilihat pelanggan) --}}
            <div class="d-flex align-items-center gap-3 mb-4 p-3 border rounded-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold fs-5" style="width: 45px; height: 45px;">
                    {{ substr($mobil->pemilik->nama ?? 'M', 0, 1) }}
                </div>
                <div>
                    <div class="small text-muted lh-1 mb-1">Disediakan oleh Mitra</div>
                    <div class="fw-bold text-dark">{{ $mobil->pemilik->nama ?? 'Roda Kita' }}</div>
                </div>
            </div>

            <div class="d-grid mt-4">
                <a href="{{ route('pelanggan.order.checkout', $mobil->id) }}" class="btn btn-primary btn-lg fw-bold rounded-3 shadow-sm py-3">
                    Sewa Mobil Ini Sekarang
                </a>
            </div>

        </div>
    </div>
</div>
@endsection