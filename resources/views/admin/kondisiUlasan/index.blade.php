@extends('layouts.admin')

@section('title', 'Riwayat Kondisi & Ulasan - Admin')
@section('page_title', 'Kondisi & Ulasan')
@section('breadcrumb', 'Admin / Kondisi & Ulasan')

{{-- Panggil CSS Fancybox untuk Fitur Swipe Gambar --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@endpush

@section('content')

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-6">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-journal-check text-primary me-2"></i>Arsip Inspeksi & Ulasan
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Daftar transaksi selesai beserta laporan fisik dan rating.</p>
                </div>
                <div class="col-12 col-md-6 text-md-end">
                    <form action="{{ route('admin.kondisiUlasan.index') }}" method="GET" class="d-inline-block">
                        <div class="input-group input-group-sm rounded-3 border" style="width: 250px;">
                            <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 shadow-none" placeholder="Cari ID, Plat, Pelanggan...">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3 fw-semibold border-bottom-0">ID Order</th>
                            <th class="py-3 fw-semibold border-bottom-0">Pelanggan</th>
                            <th class="py-3 fw-semibold border-bottom-0">Mobil</th>
                            <th class="py-3 fw-semibold border-bottom-0">Selesai Pada</th>
                            <th class="py-3 pe-4 fw-semibold border-bottom-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top">
                        @forelse($riwayat as $r)
                        <tr>
                            <td class="ps-4 py-3 fw-bold text-dark">#ORD-{{ $r->id }}</td>
                            <td class="py-3 fw-medium text-dark">{{ $r->user->nama ?? '-' }}</td>
                            <td class="py-3">
                                <div class="fw-bold text-dark">{{ $r->mobil->brand->nama_brand ?? '' }} {{ $r->mobil->model ?? '' }}</div>
                                <span class="badge bg-light border text-dark">{{ $r->mobil->plat_nomer ?? '-' }}</span>
                            </td>
                            <td class="py-3 small text-muted">{{ \Carbon\Carbon::parse($r->updated_at)->format('d M Y, H:i') }} WIB</td>
                            <td class="pe-4 py-3 text-end">
                                <button type="button" class="btn btn-sm btn-primary rounded-3 shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#laporanModal{{ $r->id }}">
                                    <i class="bi bi-file-text me-1"></i> Buka Laporan
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block opacity-25 mb-3"></i>
                                Belum ada riwayat penyewaan yang selesai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($riwayat->hasPages())
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-center">
            {{ $riwayat->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- MODAL LAPORAN KONDISI & ULASAN --}}
    @foreach($riwayat as $r)
    <div class="modal fade" id="laporanModal{{ $r->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="bi bi-shield-check text-success me-2"></i>Laporan Transaksi #ORD-{{ $r->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        
                        {{-- KOLOM KIRI: DATA FISIK KENDARAAN --}}
                        <div class="col-lg-7 border-end">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-car-front-fill text-primary me-2"></i>Inspeksi Fisik Kendaraan</h6>
                            
                            <div class="row g-3">
                                {{-- Kondisi Keluar --}}
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-white h-100">
                                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-box-arrow-up me-1"></i> Saat Mobil Keluar</h6>
                                        <div class="small mb-1"><span class="text-muted">Odometer:</span> <strong class="float-end">{{ number_format($r->kondisiPengambilan->odometer ?? 0) }} KM</strong></div>
                                        <div class="small mb-1"><span class="text-muted">Bensin:</span> <strong class="float-end">{{ $r->kondisiPengambilan->indikator_bensin ?? '-' }}</strong></div>
                                        <div class="small mb-1"><span class="text-muted">Eksterior:</span> <strong class="float-end">{{ $r->kondisiPengambilan->kondisi_eksterior ?? '-' }}</strong></div>
                                        <div class="small mb-3"><span class="text-muted">Interior:</span> <strong class="float-end">{{ $r->kondisiPengambilan->kondisi_interior ?? '-' }}</strong></div>
                                        
                                        <div class="small text-muted fw-bold mb-2">Foto Dokumentasi (Klik):</div>
                                        @if($r->kondisiPengambilan && is_array($r->kondisiPengambilan->foto_kendaraan))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($r->kondisiPengambilan->foto_kendaraan as $path)
                                                    {{-- FANCYBOX TRIGGER --}}
                                                    <a href="{{ asset('storage/' . $path) }}" data-fancybox="keluar-{{ $r->id }}" data-caption="Foto Mobil Keluar">
                                                        <img src="{{ asset('storage/' . $path) }}" class="rounded border shadow-sm" style="height: 60px; width: 60px; object-fit: cover;">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Kondisi Kembali --}}
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 bg-white h-100">
                                        <h6 class="fw-bold text-success mb-3"><i class="bi bi-box-arrow-in me-1"></i> Saat Mobil Kembali</h6>
                                        <div class="small mb-1"><span class="text-muted">Odometer:</span> <strong class="float-end">{{ number_format($r->kondisiPengembalian->odometer ?? 0) }} KM</strong></div>
                                        <div class="small mb-1"><span class="text-muted">Bensin:</span> <strong class="float-end">{{ $r->kondisiPengembalian->indikator_bensin ?? '-' }}</strong></div>
                                        <div class="small mb-1"><span class="text-muted">Eksterior:</span> <strong class="float-end">{{ $r->kondisiPengembalian->kondisi_eksterior ?? '-' }}</strong></div>
                                        <div class="small mb-3"><span class="text-muted">Interior:</span> <strong class="float-end">{{ $r->kondisiPengembalian->kondisi_interior ?? '-' }}</strong></div>
                                        
                                        <div class="small text-muted fw-bold mb-2">Foto Dokumentasi (Klik):</div>
                                        @if($r->kondisiPengembalian && is_array($r->kondisiPengembalian->foto_kendaraan))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($r->kondisiPengembalian->foto_kendaraan as $path)
                                                    {{-- FANCYBOX TRIGGER --}}
                                                    <a href="{{ asset('storage/' . $path) }}" data-fancybox="masuk-{{ $r->id }}" data-caption="Foto Mobil Kembali">
                                                        <img src="{{ asset('storage/' . $path) }}" class="rounded border shadow-sm border-success" style="height: 60px; width: 60px; object-fit: cover;">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Catatan Denda --}}
                                @if(isset($r->kondisiPengembalian->denda) && $r->kondisiPengembalian->denda > 0)
                                    <div class="col-12 mt-2">
                                        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 rounded-3 mb-0">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Denda Dikenakan</span>
                                                <span class="fw-bold text-danger fs-5">Rp {{ number_format($r->kondisiPengembalian->denda, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="small text-dark mt-1">Catatan Klaim: <strong>{{ $r->kondisiPengembalian->catatan ?? '-' }}</strong></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- KOLOM KANAN: ULASAN & RATING --}}
                        <div class="col-lg-5">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-star-half text-warning me-2"></i>Arsip Ulasan</h6>

                            {{-- Ulasan dari Admin ke Pelanggan --}}
                            <div class="bg-light border rounded-3 p-3 mb-3">
                                <div class="text-muted small mb-2 text-uppercase fw-bold">Dari Admin Ke Pelanggan</div>
                                <h6 class="fw-bold text-dark mb-1">{{ $r->user->nama ?? 'Pelanggan' }}</h6>
                                @if($r->ulasanPelanggan)
                                    <div class="text-warning fs-6 mb-2">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= $r->ulasanPelanggan->rating ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0 small fst-italic text-dark">"{{ $r->ulasanPelanggan->catatan }}"</p>
                                @else
                                    <div class="small text-muted fst-italic">Belum ada penilaian untuk pelanggan ini.</div>
                                @endif
                            </div>

                            {{-- Ulasan dari Pelanggan ke Mobil --}}
                            <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 p-3">
                                <div class="text-muted small mb-2 text-uppercase fw-bold">Dari Pelanggan Ke Mobil</div>
                                <h6 class="fw-bold text-dark mb-1">{{ $r->mobil->brand->nama_brand ?? '' }} {{ $r->mobil->model ?? '' }}</h6>
                                @if($r->ulasanMobil)
                                    <div class="text-warning fs-6 mb-2">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= $r->ulasanMobil->rating ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0 small fst-italic text-dark">"{{ $r->ulasanMobil->catatan }}"</p>
                                @else
                                    <div class="small text-muted fst-italic">Pelanggan belum memberikan ulasan untuk mobil ini.</div>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
                
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection

{{-- Panggil JS Fancybox --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Fancybox.bind("[data-fancybox]", {
                // Konfigurasi tambahan fancybox jika diperlukan
                Thumbs : { type: "modern" },
                Toolbar: {
                    display: {
                        left: ["infobar"],
                        middle: [
                            "zoomIn",
                            "zoomOut",
                            "toggle1to1",
                            "rotateCCW",
                            "rotateCW",
                            "flipX",
                            "flipY",
                        ],
                        right: ["slideshow", "thumbs", "close"],
                    },
                },
            });
        });
    </script>
@endpush