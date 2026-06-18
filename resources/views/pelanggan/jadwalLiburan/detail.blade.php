@extends('layouts.pelanggan')

@section('title', 'Atur Jadwal - #ORD-' . $booking->id)

@push('styles')
<style>
    /* CSS Khusus untuk Garis Waktu (Timeline) */
    .timeline {
        border-left: 3px solid #0d6efd;
        padding-left: 20px;
        margin-left: 10px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -28px;
        top: 0;
        width: 14px;
        height: 14px;
        background-color: #ffffff;
        border: 3px solid #0d6efd;
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5">
    
    <div class="mb-4">
        <a href="{{ route('pelanggan.jadwal.index') }}" class="btn btn-white border rounded-3 px-3 py-2 text-secondary shadow-sm fw-medium">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Perjalanan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger rounded-3 border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 mt-1 ps-3 small">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        {{-- BAGIAN KIRI: FORM TAMBAH JADWAL --}}
        <div class="col-lg-5">
            {{-- Info Kendaraan Singkat --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary bg-opacity-10">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="bg-white p-3 rounded-3 shadow-sm d-flex justify-content-center align-items-center flex-shrink-0" style="width: 70px; height: 70px;">
                        <i class="bi bi-car-front fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">{{ $booking->mobil->brand->nama_brand ?? '' }} {{ $booking->mobil->model ?? '' }}</h6>
                        <span class="badge bg-white text-dark border shadow-sm">{{ $booking->mobil->plat_nomer ?? '-' }}</span>
                        <div class="small text-muted mt-2 fw-medium">
                            Masa Sewa: <br>
                            <span class="text-primary">{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M Y') }}</span> s/d <span class="text-primary">{{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Input --}}
            <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 100px;">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Destinasi / Kegiatan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pelanggan.jadwal.store', $booking->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                {{-- Kunci inputan date sesuai dengan masa sewa --}}
                                <input type="date" name="tanggal" class="form-control" required
                                       min="{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('Y-m-d') }}" 
                                       max="{{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('Y-m-d') }}">
                            </div>
                            
                            <div class="col-6">
                                <label class="form-label small fw-bold text-dark">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_mulai" class="form-control" required>
                            </div>
                            
                            <div class="col-6">
                                <label class="form-label small fw-bold text-dark">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_selesai" class="form-control" required>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label small fw-bold text-dark">Kegiatan / Aktivitas <span class="text-danger">*</span></label>
                                <input type="text" name="kegiatan" class="form-control" placeholder="Cth: Berenang, Makan Siang, dll" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Nama Tempat / Lokasi (Opsional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                    <input type="text" name="lokasi" class="form-control border-start-0" placeholder="Cth: Pantai Pandawa">
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-3 rounded-3 shadow-sm text-uppercase" style="letter-spacing: 1px;">
                                    <i class="bi bi-calendar-plus me-1"></i> Simpan ke Jadwal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: TIMELINE RENCANA PERJALANAN --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 min-vh-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-map-fill text-primary me-2"></i>Itinerary Perjalanan Anda</h5>
                </div>
                <div class="card-body p-4 p-lg-5">
                    
                    @if($jadwals->isEmpty())
                        <div class="text-center py-5 mt-5">
                            <i class="bi bi-signpost-split fs-1 text-primary opacity-25 d-block mb-3" style="font-size: 4rem !important;"></i>
                            <h5 class="fw-bold text-dark">Jadwal Anda masih kosong</h5>
                            <p class="text-muted small">Silakan gunakan formulir di samping untuk mulai menyusun daftar tempat yang akan Anda kunjungi.</p>
                        </div>
                    @else
                        {{-- Looping per Tanggal --}}
                        @foreach($jadwals as $tanggal => $kegiatans)
                            <div class="mb-5">
                                <h5 class="fw-bold text-white bg-primary d-inline-block px-4 py-2 rounded-pill shadow-sm mb-4">
                                    <i class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
                                </h5>
                                
                                <div class="timeline">
                                    {{-- Looping Kegiatan di hari tersebut --}}
                                    @foreach($kegiatans as $k)
                                        <div class="timeline-item bg-white border p-3 p-lg-4 rounded-4 shadow-sm position-relative transition-hover" style="transition: transform 0.2s;">
                                            
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('pelanggan.jadwal.destroy', $k->id) }}" method="POST" class="position-absolute top-0 end-0 mt-3 me-3">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-light text-danger btn-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;" onclick="return confirm('Yakin ingin menghapus jadwal ini?')" title="Hapus Jadwal">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>

                                            {{-- Waktu --}}
                                            <div class="d-flex align-items-center fw-bold text-primary fs-5 mb-2">
                                                <i class="bi bi-clock-history me-2"></i>
                                                {{ \Carbon\Carbon::parse($k->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($k->jam_selesai)->format('H:i') }} WIB
                                            </div>
                                            
                                            {{-- Kegiatan & Lokasi --}}
                                            <h5 class="fw-bold text-dark mb-1">{{ $k->kegiatan }}</h5>
                                            
                                            @if($k->lokasi)
                                                <div class="d-flex align-items-center text-muted small mt-2 bg-light d-inline-block px-3 py-1 rounded-3">
                                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>{{ $k->lokasi }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection