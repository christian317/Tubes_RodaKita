@extends('layouts.pelanggan')

@section('title', 'Detail Mobil - ' . $mobil->model)

@section('content')
{{-- 1. PASTE CSS FLATPICKR LANGSUNG DI SINI --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
<style>
    .kalender-input { cursor: pointer; background-color: #fff !important; }
</style>

<div class="container py-4 py-lg-5">
    
    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm fw-medium">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger mb-4 shadow-sm rounded-3">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row g-4 g-lg-5">
        {{-- KOLOM KIRI: FOTO MOBIL --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-sticky" style="top: 100px;">
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

        {{-- KOLOM KANAN: DETAIL INFORMASI & KALENDER --}}
        <div class="col-lg-5">
            <div class="mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary border px-3 py-2 rounded-pill fw-semibold mb-2">
                    <i class="bi bi-car-front-fill me-1"></i> Roda Kita
                </span>
                <div class="text-muted fw-bold text-uppercase letter-spacing-1 mt-2">{{ $mobil->brand->nama_brand ?? 'Merk Tidak Diketahui' }}</div>
                <h2 class="fw-bold text-dark display-6 mb-3">{{ $mobil->model }}</h2>
            </div>

            {{-- Ringkasan Rating di Atas Harga --}}
            @php
                $totalUlasan = $mobil->ulasans->count();
                $avgRating = $totalUlasan > 0 ? $mobil->ulasans->avg('rating') : 0;
            @endphp
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning fs-5 me-2">
                    <i class="bi bi-star-fill"></i>
                </div>
                <span class="fw-bold fs-5 text-dark me-2">{{ number_format($avgRating, 1) }}</span>
                <a href="#sectionUlasan" class="text-primary small text-decoration-none">({{ $totalUlasan }} Penilaian)</a>
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

            {{-- AREA CEK KETERSEDIAAN (KALENDER) --}}
            <div class="card border border-primary border-opacity-25 shadow-sm rounded-4 mt-4 bg-primary bg-opacity-10">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark"><i class="bi bi-calendar2-range text-primary me-2"></i>Cek Ketersediaan Mobil</h5>
                    
                    <style>
                        .flatpickr-day.flatpickr-disabled { background-color: #fee2e2 !important; color: #ef4444 !important; text-decoration: line-through; border-color: #fecaca !important; font-weight: bold; }
                        .flatpickr-day:not(.flatpickr-disabled):not(.nextMonthDay):not(.prevMonthDay) { background-color: #dcfce7; color: #166534; font-weight: 600; }
                        .flatpickr-day.selected { background-color: #0d6efd !important; color: white !important; }
                    </style>

                    <form action="{{ route('pelanggan.order.checkout', $mobil->id) }}" method="GET">
                        <div class="mb-3">
                            <input type="text" id="tanggalSewa" name="rentang_tanggal" class="form-control form-control-lg kalender-input border-primary border-opacity-50" placeholder="Ketuk untuk buka kalender..." required readonly>
                            <div class="d-flex gap-3 mt-3 px-1 small fw-medium">
                                <div class="d-flex align-items-center gap-2"><span class="d-inline-block rounded-circle" style="width:14px;height:14px;background:#dcfce7;border:1px solid #166534;"></span> Tersedia</div>
                                <div class="d-flex align-items-center gap-2"><span class="d-inline-block rounded-circle" style="width:14px;height:14px;background:#fee2e2;border:1px solid #ef4444;"></span> Dibooking</div>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-3 shadow-sm py-3">Lanjut Proses Sewa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SEGMEN ULASAN PELANGGAN ALA SHOPEE --}}
    <div id="sectionUlasan" class="mt-5 pt-4 border-top">
        <div class="row align-items-center mb-4">
            <div class="col-sm-6">
                <h4 class="fw-bold text-dark mb-0">Ulasan Pelanggan</h4>
            </div>
            <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                <div class="d-flex align-items-center justify-content-sm-end">
                    <span class="display-6 fw-bold text-dark me-2">{{ number_format($avgRating, 1) }}</span>
                    <div class="text-start">
                        <div class="text-warning fs-6">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill {{ $i <= round($avgRating) ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                            @endfor
                        </div>
                        <div class="small text-muted">{{ $totalUlasan }} Penilaian</div>
                    </div>
                </div>
            </div>
        </div>

        @if($totalUlasan > 0)
            <div class="row g-3">
                {{-- Menampilkan hanya 2 ulasan terbaru di halaman depan --}}
                @foreach($mobil->ulasans->sortByDesc('created_at')->take(2) as $ulasan)
                    <div class="col-md-6">
                        <div class="card border border-light-subtle rounded-4 h-100 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold text-dark">
                                        {{-- Sensor nama pelanggan seperti di marketplace (Joh***) --}}
                                        {{ substr($ulasan->booking->user->nama ?? 'Anonim', 0, 3) }}***
                                    </div>
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($ulasan->created_at)->diffForHumans() }}</div>
                                </div>
                                <div class="text-warning small mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $ulasan->rating ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <p class="mb-0 text-muted fst-italic" style="font-size: 0.95rem;">"{{ $ulasan->catatan }}"</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Lihat Semua Ulasan (Memanggil Modal) --}}
            @if($totalUlasan > 2)
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-outline-primary fw-medium px-5 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalSemuaUlasan">
                        Lihat Semua Ulasan ({{ $totalUlasan }})
                    </button>
                </div>
            @endif
        @else
            <div class="text-center p-5 bg-light rounded-4 border">
                <i class="bi bi-chat-square-text fs-1 text-muted opacity-50 d-block mb-3"></i>
                <h6 class="fw-bold text-dark">Belum ada ulasan</h6>
                <p class="text-muted small mb-0">Jadilah yang pertama menyewa dan menilai armada ini!</p>
            </div>
        @endif
    </div>

</div>

{{-- MODAL SEMUA ULASAN (Scrollable) --}}
<div class="modal fade" id="modalSemuaUlasan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark">Semua Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($mobil->ulasans->sortByDesc('created_at') as $ulasan)
                        <div class="list-group-item p-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="fw-bold text-dark">
                                    {{ substr($ulasan->booking->user->nama ?? 'Anonim', 0, 3) }}***
                                </div>
                                <div class="text-muted small">{{ \Carbon\Carbon::parse($ulasan->created_at)->format('d M Y') }}</div>
                            </div>
                            <div class="text-warning small mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= $ulasan->rating ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                                @endfor
                            </div>
                            <p class="mb-0 text-dark" style="font-size: 0.95rem;">{{ $ulasan->catatan }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-3">
                <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- 2. PASTE JAVASCRIPT FLATPICKR LANGSUNG DI BAWAH CONTAINER UTAMA --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tanggalTerkunci = @json($disabledDates ?? []);

        flatpickr("#tanggalSewa", {
            mode: "range", 
            minDate: "today", 
            dateFormat: "Y-m-d", 
            altInput: true, 
            altFormat: "d F Y", 
            locale: "id", 
            disable: tanggalTerkunci, 
            
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const start = selectedDates[0];
                    const end = selectedDates[1];
                    
                    let isInvalid = false;
                    tanggalTerkunci.forEach(function(range) {
                        const dStart = new Date(range.from);
                        const dEnd = new Date(range.to);
                        dStart.setHours(0,0,0,0);
                        dEnd.setHours(0,0,0,0);

                        if (start < dEnd && end > dStart) {
                            isInvalid = true;
                        }
                    });

                    if (isInvalid) {
                        alert("Pilihan Tidak Valid! Anda tidak bisa melompati tanggal yang sudah dibooking oleh pelanggan lain.");
                        instance.clear(); 
                    }
                }
            }
        });
    });
</script>
@endsection