@extends('layouts.mitra')

@section('title', 'Pendapatan & Komisi - Roda Kita')
@section('page_title', 'Keuangan Saya')
@section('breadcrumb', 'Mitra / Pendapatan & Komisi')

@section('content')

    {{-- KARTU RINGKASAN SALDO & INDIKATOR TUNGGAKAN --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            @if($tunggakanAdminGlobal > 0)
                <div class="card border-0 shadow-sm rounded-4 h-100 text-white" style="background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);">
            @else
                <div class="card border-0 shadow-sm rounded-4 h-100 text-white" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
            @endif
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-exclamation-circle fs-1 text-white"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white text-opacity-75 fw-medium text-uppercase letter-spacing-1 small">Tunggakan Belum Dibayar Admin</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($tunggakanAdminGlobal, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-cash-stack fs-1 text-white"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white text-opacity-75 fw-medium text-uppercase letter-spacing-1 small">Total Pendapatan (All Time)</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalPendapatanGlobal, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-bank text-secondary fs-1"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted fw-medium text-uppercase letter-spacing-1 small">Total Telah Dicairkan Admin</p>
                        <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalDicairkanGlobal, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB NAVIGASI --}}
    <ul class="nav nav-pills mb-4" id="keuanganTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold px-4 rounded-pill me-2 shadow-sm" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat-pane" type="button">
                Pendapatan Per Armada
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 rounded-pill bg-white border text-secondary" id="pencairan-tab" data-bs-toggle="tab" data-bs-target="#pencairan-pane" type="button">
                <i class="bi bi-clock-history me-1"></i> Riwayat Pencairan Dana
            </button>
        </li>
    </ul>

    <div class="tab-content">
        
        {{-- TAB 1: DAFTAR MOBIL --}}
        <div class="tab-pane fade show active" id="riwayat-pane">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-car-front-fill text-primary me-2"></i>Komisi Berdasarkan Unit Kendaraan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 fw-semibold">Armada Mobil</th>
                                    <th class="py-3 fw-semibold">Kategori</th>
                                    <th class="py-3 fw-semibold text-center">Total Komisi Terkumpul (70%)</th>
                                    <th class="py-3 pe-4 fw-semibold text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse($mobils as $m)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-light rounded-3 border overflow-hidden d-flex justify-content-center align-items-center" style="width: 60px; height: 45px;">
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
                                    <td class="py-3 text-muted small">{{ $m->kategori->nama_kategori ?? '-' }} ({{ $m->transmisi }})</td>
                                    <td class="py-3 text-center fw-bold text-success">Rp {{ number_format($m->total_pendapatan, 0, ',', '.') }}</td>
                                    <td class="pe-4 py-3 text-end">
                                        <a href="{{ route('mitra.komisi.detail', $m->id) }}" class="btn btn-sm btn-primary fw-bold rounded-3 px-3 shadow-sm">
                                            <i class="bi bi-eye-fill me-1"></i> Lihat Rincian Komisi
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada armada mobil terdaftar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: RIWAYAT PENCAIRAN --}}
        <div class="tab-pane fade" id="pencairan-pane">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-receipt-cutoff text-secondary me-2"></i>Bukti Setoran Dana Transfer dari Admin</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 fw-semibold">Tanggal Terima</th>
                                    <th class="py-3 fw-semibold">Jumlah Cair</th>
                                    <th class="py-3 fw-semibold">Catatan Berita</th>
                                    <th class="py-3 pe-4 fw-semibold text-end">Status & Berkas Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse($riwayatPencairan as $r)
                                <tr>
                                    <td class="ps-4 py-3 text-muted small">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }} WIB</td>
                                    <td class="py-3 fw-bold text-dark">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</td>
                                    <td class="py-3 small text-muted fst-italic">"{{ $r->catatan ?? '-' }}"</td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-flex flex-column align-items-end gap-1">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 mb-1"><i class="bi bi-check-all"></i> Sudah Ditransfer</span>
                                            <button type="button" class="btn btn-xs btn-outline-primary rounded-3 px-3 py-1" data-bs-toggle="modal" data-bs-target="#buktiModal{{ $r->id }}">
                                                <i class="bi bi-image me-1"></i> Cek Bukti Transfer
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL FILE TRANSFER --}}
                                <div class="modal fade" id="buktiModal{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-bottom bg-light px-4 py-3">
                                                <h5 class="modal-title fw-bold text-dark">Bukti Resi Transfer Admin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-0 bg-dark text-center">
                                                <img src="{{ asset('storage/' . $r->bukti_transfer) }}" class="img-fluid w-100" style="max-height: 70vh; object-fit: contain;">
                                            </div>
                                            <div class="modal-footer bg-light border-top p-3 justify-content-between">
                                                <span class="fw-bold text-dark">Nominal: Rp {{ number_format($r->jumlah, 0, ',', '.') }}</span>
                                                <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada catatan pengiriman dana dari admin.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.querySelectorAll('#keuanganTab button[data-bs-toggle="tab"]').forEach(btn => {
            btn.addEventListener('show.bs.tab', event => {
                document.querySelectorAll('#keuanganTab button').forEach(b => {
                    b.classList.remove('bg-white', 'border', 'text-secondary');
                    if (!b.classList.contains('active')) b.classList.add('bg-white', 'border', 'text-secondary');
                });
                event.target.classList.remove('bg-white', 'border', 'text-secondary');
            });
        });
    </script>
@endsection