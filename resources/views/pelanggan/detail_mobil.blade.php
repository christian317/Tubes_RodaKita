@extends('layouts.pelanggan')

@section('title', 'Detail Mobil - ' . $mobil->model)

@section('content')
{{-- 1. PASTE CSS FLATPICKR LANGSUNG DI SINI (Di bawah section content) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
<style>
    /* Agar form input kalendernya kursornya menjadi tangan (bisa diklik) */
    .kalender-input { cursor: pointer; background-color: #fff !important; }
</style>

<div class="container py-4 py-lg-5">
    
    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm fw-medium">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
        </a>
    </div>

    {{-- Tampilkan error jika ada masalah saat diredirect balik dari checkout --}}
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
                    
                    {{-- CSS Khusus untuk memperjelas Kalender Flatpickr --}}
                    <style>
                        /* Tanggal yang sudah di-booking (Merah & Dicoret) */
                        .flatpickr-day.flatpickr-disabled {
                            background-color: #fee2e2 !important;
                            color: #ef4444 !important;
                            text-decoration: line-through;
                            border-color: #fecaca !important;
                            font-weight: bold;
                        }
                        /* Tanggal yang tersedia (Hijau) */
                        .flatpickr-day:not(.flatpickr-disabled):not(.nextMonthDay):not(.prevMonthDay) {
                            background-color: #dcfce7;
                            color: #166534;
                            font-weight: 600;
                        }
                        /* Saat dipilih */
                        .flatpickr-day.selected {
                            background-color: #0d6efd !important;
                            color: white !important;
                        }
                    </style>

                    <form action="{{ route('pelanggan.order.checkout', $mobil->id) }}" method="GET">
                        <div class="mb-3">
                            <input type="text" id="tanggalSewa" name="rentang_tanggal" class="form-control form-control-lg kalender-input border-primary border-opacity-50" placeholder="Ketuk untuk buka kalender..." required readonly>
                            
                            {{-- Legenda Indikator Warna --}}
                            <div class="d-flex gap-3 mt-3 px-1 small fw-medium">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-block rounded-circle" style="width:14px;height:14px;background:#dcfce7;border:1px solid #166534;"></span> Tersedia
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-block rounded-circle" style="width:14px;height:14px;background:#fee2e2;border:1px solid #ef4444;"></span> Dibooking
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-3 shadow-sm py-3">
                                Lanjut Proses Sewa
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- 2. PASTE JAVASCRIPT FLATPICKR LANGSUNG DI BAWAH CONTAINER UTAMA --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Kita terima data tanggal yang sudah dibooking dari Controller
        const tanggalTerkunci = @json($disabledDates ?? []);

        flatpickr("#tanggalSewa", {
            mode: "range", 
            minDate: "today", 
            dateFormat: "Y-m-d", 
            altInput: true, 
            altFormat: "d F Y", 
            locale: "id", 
            disable: tanggalTerkunci, 
            
            // Mencegah pelanggan mem-blok ("melompati") tanggal yang sudah ada isinya
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

                        // Jika rentang pilihan mengurung tanggal yang didisable, batalkan
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