@extends('layouts.pelanggan')

@section('title', 'Pilih Perjalanan - Roda Kita')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="d-flex align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0"><i class="bi bi-map-fill text-primary me-2"></i>Rencana Perjalanan Saya</h4>
            </div>

            @if(session('error'))
                <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</div>
            @endif

            <div class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 rounded-4 p-4 mb-4">
                <div class="d-flex gap-3">
                    <i class="bi bi-info-circle-fill text-info fs-4"></i>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Rencanakan Liburan Anda!</h6>
                        <p class="mb-0 small text-muted">Pilih kendaraan yang sudah Anda sewa di bawah ini untuk mulai menyusun jadwal destinasi dan waktu perjalanan Anda selama masa sewa.</p>
                    </div>
                </div>
            </div>

            {{-- DAFTAR KARTU PESANAN AKTIF --}}
            @forelse($bookings as $b)
                <div class="card border border-light-subtle shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-light border-bottom-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-dark me-2">#ORD-{{ $b->id }}</span>
                        </div>
                        <div>
                            @if($b->status == 'disewakan')
                                <span class="badge text-primary bg-primary bg-opacity-10 border border-primary px-2 py-1"><i class="bi bi-car-front me-1"></i> SEDANG BERJALAN</span>
                            @else
                                <span class="badge text-info bg-info bg-opacity-10 border border-info px-2 py-1"><i class="bi bi-clock me-1"></i> PERSIAPAN</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="bg-white border rounded-3 p-1 d-flex justify-content-center align-items-center" style="width: 90px; height: 90px;">
                                    @if($b->mobil->gambar)
                                        <img src="{{ asset('storage/' . $b->mobil->gambar) }}" class="w-100 h-100 object-fit-cover rounded-2">
                                    @else
                                        <i class="bi bi-car-front text-muted fs-1"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="fw-bold text-dark mb-1">{{ $b->mobil->brand->nama_brand ?? '' }} {{ $b->mobil->model ?? '' }}</h5>
                                <p class="text-muted small mb-2">
                                    <span class="badge bg-light text-dark border me-1">{{ $b->mobil->plat_nomer ?? '-' }}</span>
                                    Layanan: <strong class="text-primary">{{ str_replace('_', ' ', strtoupper($b->tipe_layanan)) }}</strong>
                                </p>
                                <div class="d-flex align-items-center text-dark small fw-medium">
                                    <i class="bi bi-calendar-range text-primary me-2"></i>
                                    {{ \Carbon\Carbon::parse($b->tanggal_mulai)->format('d M Y') }} 
                                    <i class="bi bi-arrow-right mx-2 text-muted"></i> 
                                    {{ \Carbon\Carbon::parse($b->tanggal_selesai)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="col-12 col-sm-auto mt-3 mt-sm-0 text-sm-end">
                                <a href="{{ route('pelanggan.jadwal.detail', $b->id) }}" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm w-100 w-sm-auto">
                                    Atur Jadwal <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                    <i class="bi bi-emoji-frown fs-1 text-muted opacity-25 d-block mb-3"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Perjalanan Aktif</h5>
                    <p class="text-muted small mb-4">Anda belum memiliki pesanan sewa mobil yang disetujui atau sedang berjalan.</p>
                    <a href="{{ route('pelanggan.riwayatBooking.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-medium">Lihat Status Pesanan</a>
                </div>
            @endforelse

        </div>
    </div>
</div>
@endsection