@extends('layouts.mitra')

@section('title', 'Rincian Komisi Unit - Roda Kita')
@section('page_title', 'Rincian Komisi Unit')
@section('breadcrumb', 'Mitra / Keuangan / Detail')

@section('content')
    <div class="mb-4">
        <a href="{{ route('mitra.komisi.index') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm fw-medium">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Ringkasan Keuangan
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
                    <div class="font-monospace text-muted fs-6">{{ $mobil->plat_nomer }} | <span class="fw-medium text-dark">Rp {{ number_format($mobil->harga_sewa, 0, ',', '.') }} / hari</span></div>
                </div>
                <div class="col-md-3 text-md-end border-start ps-md-4">
                    <div class="text-muted small fw-medium mb-1">Total Komisi Unit Ini</div>
                    <div class="text-success fw-bold display-6" style="font-size: 1.85rem;">Rp {{ number_format($totalKomisiMobil, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT MENGENAI STATUS STATUS TUNGGAKAN ADMIN GLOBAL --}}
    @if($tunggakanAdminGlobal > 0)
        <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 mb-4 d-flex align-items-start gap-3 bg-warning bg-opacity-10 text-dark">
            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning lh-1"></i>
            <div>
                <h6 class="fw-bold text-dark mb-1">Status Pembayaran Admin: Ada Tunggakan Dana Payout</h6>
                <p class="small text-muted mb-0">Sistem mendeteksi komisi Anda secara keseluruhan masih tertahan di rekening perusahaan sebesar <strong>Rp {{ number_format($tunggakanAdminGlobal, 0, ',', '.') }}</strong>. Hubungi admin gudang jika Anda ingin mengajukan klaim transfer pencairan saldo tersebut.</p>
            </div>
        </div>
    @else
        <div class="alert alert-success border-0 shadow-sm rounded-4 p-4 mb-4 d-flex align-items-start gap-3 bg-success bg-opacity-10 text-dark">
            <i class="bi bi-check-circle-fill fs-3 text-success lh-1"></i>
            <div>
                <h6 class="fw-bold text-dark mb-1">Status Pembayaran Admin: Lunas / Bersih</h6>
                <p class="small text-muted mb-0">Seluruh hak komisi yang terkumpul dari seluruh sewa kendaraan Anda saat ini sudah sepenuhnya ditransfer oleh pihak admin ke dalam rekening bank terdaftar Anda.</p>
            </div>
        </div>
    @endif

    {{-- DAFTAR TRANSAKSI PER MOBIL --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-stars text-primary me-2"></i>Histori Log Penyewaan Unit</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3 fw-semibold">Kode Booking</th>
                            <th class="py-3 fw-semibold">Tanggal Rental</th>
                            <th class="py-3 fw-semibold text-center">Durasi Hari</th>
                            <th class="py-3 fw-semibold text-center">Status Dompet</th>
                            <th class="py-3 pe-4 fw-semibold text-end text-success">Hak Komisi Anda</th>
                        </tr>
                    </thead>
                    <tbody class="border-top">
                        @forelse($bookings as $booking)
                            @php
                                $mulai = \Carbon\Carbon::parse($booking->tanggal_mulai);
                                $selesai = \Carbon\Carbon::parse($booking->tanggal_selesai);
                                $hari = ceil($mulai->diffInHours($selesai) / 24) ?: 1;
                            @endphp
                            <tr>
                                <td class="ps-4 py-3 fw-bold text-dark">#ORD-{{ $booking->id }}</td>
                                <td class="py-3">
                                    <span class="small text-dark fw-medium">{{ $mulai->format('d M Y') }}</span> 
                                    <span class="text-muted small">s/d</span> 
                                    <span class="small text-dark fw-medium">{{ $selesai->format('d M Y') }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-light text-dark border px-2.5 py-1.5 font-monospace">{{ $hari }} Hari</span>
                                </td>
                                <td class="py-3 text-center">
                                    {{-- Info status tunggakan komisi --}}
                                    @if($tunggakanAdminGlobal > 0)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2.5 py-1.5">Mengendap di Sistem</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2.5 py-1.5"><i class="bi bi-check2"></i> Sudah Terbayar</span>
                                    @endif
                                </td>
                                <td class="pe-4 py-3 text-end fw-bold text-success">
                                    + Rp {{ number_format($booking->pembayaran->komisi_pemilik ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada log sewa berstatus lunas yang terekam pada unit ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection