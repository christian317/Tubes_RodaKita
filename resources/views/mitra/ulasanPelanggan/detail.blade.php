@extends('layouts.mitra')

@section('title', 'Detail Ulasan Unit - Roda Kita')
@section('page_title', 'Detail Ulasan Pelanggan')
@section('breadcrumb', 'Mitra / Ulasan Armada / Detail')

@section('content')
    <div class="mb-4">
        <a href="{{ route('mitra.ulasanPelanggan.index') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm fw-medium">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Armada
        </a>
    </div>

    {{-- KOTAK INFORMASI MOBIL YANG DIPILIH --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4 bg-white">
            <div class="row align-items-center g-4">
                <div class="col-auto">
                    <div class="bg-light rounded-4 border overflow-hidden d-flex justify-content-center align-items-center" style="width: 140px; height: 100px;">
                        @if($mobil->gambar)
                            <img src="{{ asset('storage/' . $mobil->gambar) }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <i class="bi bi-car-front text-muted fs-1"></i>
                        @endif
                    </div>
                </div>
                <div class="col-md">
                    <span class="badge bg-primary bg-opacity-10 text-primary border px-3 py-1.5 rounded-pill small fw-bold mb-2">{{ strtoupper($mobil->kategori->nama_kategori ?? 'Mobil') }}</span>
                    <h3 class="fw-bold text-dark mb-1">{{ $mobil->brand->nama_brand ?? '' }} {{ $mobil->model }}</h3>
                    <div class="font-monospace text-muted fs-6">{{ $mobil->plat_nomer }}</div>
                </div>
                <div class="col-md-4 text-md-end border-start ps-md-4">
                    <div class="d-flex align-items-center justify-content-md-end mb-1">
                        <div class="text-warning fs-4 me-2">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <span class="fw-bold text-dark display-6">{{ number_format($mobil->ulasans_avg_rating ?? 0, 1) }}</span>
                        <span class="text-muted ms-2 fs-5">/ 5.0</span>
                    </div>
                    <div class="text-muted small fw-medium">Berdasarkan {{ $mobil->ulasans_count }} Penilaian</div>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR KARTU ULASAN --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0">Semua Ulasan ({{ $mobil->ulasans_count }})</h5>
    </div>

    <div class="row g-4">
        @forelse($ulasans as $ulasan)
            <div class="col-md-6">
                <div class="card border border-light-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    {{-- Sensor Nama Pelanggan untuk Privasi --}}
                                    <div class="fw-bold text-dark mb-0">{{ substr($ulasan->booking->user->nama ?? 'Anonim', 0, 3) }}***</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($ulasan->created_at)->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-warning mb-1" style="font-size: 0.85rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $ulasan->rating ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <span class="badge bg-light border text-dark">#ORD-{{ $ulasan->id_booking }}</span>
                            </div>
                        </div>
                        <div class="bg-light p-3 rounded-3 text-dark" style="font-size: 0.95rem;">
                            "{{ $ulasan->catatan }}"
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                    <i class="bi bi-chat-square-text display-1 text-muted opacity-25 d-block mb-3"></i>
                    <h6 class="fw-bold text-dark">Belum ada ulasan</h6>
                    <p class="text-muted small mb-0">Armada ini belum mendapatkan ulasan dari pelanggan.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection