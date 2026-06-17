@extends('layouts.pelanggan')

@section('title', 'Pesanan Saya - Roda Kita')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- ALERT PESAN SUKSES / ERROR --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0"><i class="bi bi-bag-check-fill text-primary me-2"></i>Pesanan Saya</h4>
            </div>

            {{-- TAB NAVIGASI STATUS --}}
            <div class="bg-white rounded-4 shadow-sm border mb-4 overflow-hidden">
                <ul class="nav nav-pills flex-nowrap overflow-auto p-2" style="white-space: nowrap; scrollbar-width: none;">
                    @php $currentStatus = request('status', 'semua'); @endphp
                    
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'semua' ? 'active fw-bold shadow-sm' : 'text-muted fw-medium' }}" 
                           href="{{ route('pelanggan.riwayatBooking.index', ['status' => 'semua']) }}">Semua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'menunggu_approval' ? 'bg-warning text-dark fw-bold shadow-sm' : 'text-muted fw-medium' }}" 
                           href="{{ route('pelanggan.riwayatBooking.index', ['status' => 'menunggu_approval']) }}">Menunggu Approval</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'menunggu' ? 'bg-info text-dark fw-bold shadow-sm' : 'text-muted fw-medium' }}" 
                           href="{{ route('pelanggan.riwayatBooking.index', ['status' => 'menunggu']) }}">Belum Diambil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'disewakan' ? 'active fw-bold shadow-sm' : 'text-muted fw-medium' }}" 
                           href="{{ route('pelanggan.riwayatBooking.index', ['status' => 'disewakan']) }}">Sedang Disewakan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'selesai' ? 'bg-success text-white fw-bold shadow-sm' : 'text-muted fw-medium' }}" 
                           href="{{ route('pelanggan.riwayatBooking.index', ['status' => 'selesai']) }}">Selesai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'batal' ? 'bg-danger text-white fw-bold shadow-sm' : 'text-muted fw-medium' }}" 
                           href="{{ route('pelanggan.riwayatBooking.index', ['status' => 'batal']) }}">Dibatalkan</a>
                    </li>
                </ul>
            </div>

            {{-- DAFTAR KARTU PESANAN --}}
            @forelse($bookings as $b)
                <div class="card border border-light-subtle shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-light border-bottom-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-dark me-2"><i class="bi bi-shop me-1 text-primary"></i> Roda Kita</span>
                            <span class="text-muted small">| #ORD-{{ $b->id }}</span>
                        </div>
                        <div>
                            @if($b->status == 'menunggu_approval')
                                <span class="badge text-warning bg-warning bg-opacity-10 border border-warning">MENUNGGU APPROVAL</span>
                            @elseif($b->status == 'menunggu' || $b->status == 'dibayar')
                                <span class="badge text-info bg-info bg-opacity-10 border border-info">MENUNGGU PENGAMBILAN</span>
                            @elseif($b->status == 'disewakan')
                                <span class="badge text-primary bg-primary bg-opacity-10 border border-primary">SEDANG DISEWAKAN</span>
                            @elseif($b->status == 'selesai')
                                <span class="badge text-success bg-success bg-opacity-10 border border-success">PESANAN SELESAI</span>
                            @else
                                <span class="badge text-danger bg-danger bg-opacity-10 border border-danger">DIBATALKAN</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="bg-white border rounded-3 p-1 d-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                                    @if($b->mobil->gambar)
                                        <img src="{{ asset('storage/' . $b->mobil->gambar) }}" class="w-100 h-100 object-fit-cover rounded-2">
                                    @else
                                        <i class="bi bi-car-front text-muted fs-1"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="fw-bold text-dark mb-1">{{ $b->mobil->brand->nama_brand ?? '' }} {{ $b->mobil->model ?? '' }}</h5>
                                <p class="text-muted small mb-1">
                                    <span class="badge bg-light text-dark border me-1">{{ $b->mobil->plat_nomer ?? '-' }}</span>
                                    Layanan: <strong class="text-primary">{{ str_replace('_', ' ', strtoupper($b->tipe_layanan)) }}</strong>
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar-event me-1"></i> 
                                    {{ \Carbon\Carbon::parse($b->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($b->tanggal_selesai)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top pb-3 pt-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center rounded-bottom-4 gap-3">
                        <div>
                            <span class="text-muted small d-block mb-1">Total Pesanan</span>
                            <span class="text-success fw-bold fs-5">Rp {{ number_format($b->pembayaran->total_pembayaran ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            
                            @if($b->status == 'selesai')
                                @if(!$b->ulasanMobil)
                                    <button type="button" class="btn btn-warning text-dark fw-bold px-4 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#ulasanModal{{ $b->id }}">
                                        <i class="bi bi-star-fill me-1"></i> Beri Penilaian Mobil
                                    </button>
                                @else
                                    <span class="badge bg-light border text-success px-3 py-2 d-flex align-items-center rounded-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> Ulasan Terkirim
                                    </span>
                                @endif
                            @endif

                            @if(in_array($b->status, ['menunggu', 'dibayar', 'disewakan']))
                                <a href="{{ route('pelanggan.jadwal.detail', $b->id) }}" class="btn btn-success fw-medium px-4 rounded-3 shadow-sm">
                                    <i class="bi bi-calendar-heart me-1"></i> Jadwal Liburan
                                </a>
                            @endif

                            <button type="button" class="btn btn-outline-primary fw-medium px-4 rounded-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $b->id }}">
                                Tampilkan Detail
                            </button>
                        </div>
                    </div>
                </div>

                {{-- MODAL BERI ULASAN --}}
                @if($b->status == 'selesai' && !$b->ulasanMobil)
                <div class="modal fade" id="ulasanModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <form action="{{ route('pelanggan.ulasan.store', $b->id) }}" method="POST">
                                @csrf
                                <div class="modal-header border-bottom px-4 py-3">
                                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-star-half text-warning me-2"></i>Nilai Pengalaman Anda</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4 text-center">
                                    <h6 class="fw-bold mb-1">{{ $b->mobil->brand->nama_brand ?? '' }} {{ $b->mobil->model ?? '' }}</h6>
                                    <p class="text-muted small mb-4">Bagaimana kondisi dan performa mobil ini selama Anda gunakan?</p>
                                    
                                    <div class="mb-4 text-start">
                                        <label class="form-label fw-semibold text-dark small">Beri Rating (Bintang)</label>
                                        <select name="rating" class="form-select form-select-lg border-warning shadow-sm" required>
                                            <option value="" selected disabled>-- Pilih Penilaian --</option>
                                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                            <option value="4">⭐⭐⭐⭐ (Puas)</option>
                                            <option value="3">⭐⭐⭐ (Cukup)</option>
                                            <option value="2">⭐⭐ (Kurang)</option>
                                            <option value="1">⭐ (Sangat Kecewa)</option>
                                        </select>
                                    </div>

                                    <div class="text-start">
                                        <label class="form-label fw-semibold text-dark small">Ceritakan Pengalaman Anda</label>
                                        <textarea name="catatan" class="form-control" rows="3" placeholder="Tulis komentar mengenai kenyamanan mobil, kebersihan, dll..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light border-top">
                                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Nanti Saja</button>
                                    <button type="submit" class="btn btn-warning fw-bold rounded-3 shadow-sm px-4">Kirim Ulasan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                {{-- MODAL DETAIL --}}
                <div class="modal fade" id="detailModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header border-bottom px-4 py-3">
                                <h5 class="modal-title fw-bold text-dark">Rincian Pesanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <h6 class="fw-bold mb-3 text-dark">Informasi Kendaraan</h6>
                                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Unit Mobil</span><span class="fw-medium text-dark">{{ $b->mobil->brand->nama_brand ?? '' }} {{ $b->mobil->model ?? '' }}</span></div>
                                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Plat Nomor</span><span class="fw-medium text-dark">{{ $b->mobil->plat_nomer ?? '-' }}</span></div>
                                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Tipe Layanan</span><span class="fw-medium text-primary">{{ str_replace('_', ' ', strtoupper($b->tipe_layanan)) }}</span></div>
                                
                                <hr class="text-muted opacity-25">
                                
                                <h6 class="fw-bold mb-3 text-dark mt-3">Waktu Sewa</h6>
                                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Mulai</span><span class="fw-medium text-dark">{{ \Carbon\Carbon::parse($b->tanggal_mulai)->format('d M Y, H:i') }} WIB</span></div>
                                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Selesai</span><span class="fw-medium text-dark">{{ \Carbon\Carbon::parse($b->tanggal_selesai)->format('d M Y, H:i') }} WIB</span></div>
                                
                                <hr class="text-muted opacity-25">
                                
                                <h6 class="fw-bold mb-3 text-dark mt-3">Informasi Pembayaran</h6>
                                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Metode</span><span class="fw-medium text-dark">Midtrans Payment Gateway</span></div>
                                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Total Dibayar</span><span class="fw-bold text-success">Rp {{ number_format($b->pembayaran->total_pembayaran ?? 0, 0, ',', '.') }}</span></div>

                                {{-- FITUR BARU: MENAMPILKAN HASIL ULASAN DI DALAM MODAL DETAIL --}}
                                @if($b->ulasanMobil)
                                    <hr class="text-muted opacity-25 mt-4">
                                    <h6 class="fw-bold mb-3 text-dark mt-3"><i class="bi bi-star-fill text-warning me-1"></i> Ulasan Anda</h6>
                                    <div class="p-3 border border-warning border-opacity-25 bg-warning bg-opacity-10 rounded-3 text-dark">
                                        <div class="mb-2 fs-6">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="bi bi-star-fill {{ $i <= $b->ulasanMobil->rating ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                                            @endfor
                                            <span class="text-dark small ms-2 fw-bold">({{ $b->ulasanMobil->rating }}/5)</span>
                                        </div>
                                        <p class="mb-0 small fst-italic">"{{ $b->ulasanMobil->catatan }}"</p>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer bg-light border-top">
                                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                    <i class="bi bi-bag-x fs-1 text-muted opacity-25 d-block mb-3"></i>
                    <h5 class="fw-bold text-dark">Belum ada pesanan</h5>
                    <p class="text-muted small mb-4">Anda belum memiliki pesanan di kategori ini.</p>
                    <a href="{{ route('pelanggan.dashboard') }}" class="btn btn-primary rounded-pill px-4 fw-medium">Cari Mobil Sekarang</a>
                </div>
            @endforelse

            {{-- PAGINATION --}}
            @if($bookings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection