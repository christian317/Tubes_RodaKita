@extends('layouts.mitra')

@section('title', 'Pendapatan & Komisi - Roda Kita')
@section('page_title', 'Keuangan Saya')
@section('breadcrumb', 'Mitra / Pendapatan & Komisi')

@section('content')

    @if(session('success'))
        <div class="alert alert-success rounded-3 mb-4 shadow-sm border-0 d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-3 mb-4 shadow-sm border-0 d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    {{-- KARTU RINGKASAN SALDO & INDIKATOR TUNGGAKAN --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-white" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-wallet2 fs-2 text-white"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-white text-opacity-75 fw-medium text-uppercase letter-spacing-1 small">Saldo Berjalan</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($tunggakanAdminGlobal, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    @if($tunggakanAdminGlobal >= 10000)
                        <button type="button" class="btn btn-light rounded-pill fw-bold btn-sm py-2 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#ajukanPencairanModal">
                            <i class="bi bi-arrow-up-right-circle me-1"></i> Cairkan
                        </button>
                    @endif
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
                <i class="bi bi-clock-history me-1"></i> Riwayat & Status Pencairan
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
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-receipt-cutoff text-secondary me-2"></i>Daftar Pengajuan & Transfer Pencairan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 fw-semibold">Tanggal Pengajuan</th>
                                    <th class="py-3 fw-semibold">Nominal</th>
                                    <th class="py-3 fw-semibold">Tujuan Transfer</th>
                                    <th class="py-3 fw-semibold">Status</th>
                                    <th class="py-3 pe-4 fw-semibold text-end">Aksi/Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse($riwayatPencairan as $r)
                                <tr>
                                    <td class="ps-4 py-3 text-muted small">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }} WIB</td>
                                    <td class="py-3 fw-bold text-dark">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</td>
                                    <td class="py-3 small text-dark">
                                        @if($r->nama_bank)
                                            <strong>{{ $r->nama_bank }}</strong><br>
                                            <span class="text-muted">{{ $r->nomor_rekening }} a.n. {{ $r->nama_rekening }}</span>
                                        @else
                                            <span class="text-muted">Transfer Manual (Sistem Lama)</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @if($r->status == 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 rounded-pill"><i class="bi bi-clock"></i> Pending</span>
                                        @elseif($r->status == 'disetujui')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill"><i class="bi bi-check-circle"></i> Disetujui</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded-pill"><i class="bi bi-x-circle"></i> Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        @if($r->status == 'disetujui' && $r->bukti_transfer)
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1" data-bs-toggle="modal" data-bs-target="#buktiModal{{ $r->id }}">
                                                <i class="bi bi-image me-1"></i> Cek Bukti
                                            </button>
                                        @elseif($r->status == 'ditolak' && $r->catatan_admin)
                                            <span class="text-danger small fst-italic" title="{{ $r->catatan_admin }}">Alasan: {{ $r->catatan_admin }}</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- MODAL FILE TRANSFER --}}
                                @if($r->bukti_transfer)
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
                                @endif
                                @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada catatan pengajuan dana.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL AJUKAN PENCAIRAN --}}
    @if($tunggakanAdminGlobal >= 10000)
    <div class="modal fade text-start" id="ajukanPencairanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('mitra.komisi.pencairan') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom bg-light px-4 py-3">
                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-cash-stack text-success me-2"></i>Ajukan Pencairan Dana</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Saldo Berjalan Saat Ini</label>
                            <input type="text" class="form-control bg-light fw-bold text-dark" value="Rp {{ number_format($tunggakanAdminGlobal, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Nominal Pencairan (Min. Rp 10.000)</label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold">Rp</span>
                                <input type="number" name="jumlah" class="form-control" min="10000" max="{{ $tunggakanAdminGlobal }}" required placeholder="Contoh: 150000">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Bank Tujuan</label>
                            <input type="text" name="nama_bank" class="form-control" value="{{ $mitraProfile->nama_bank ?? '' }}" required placeholder="Contoh: BCA / Mandiri / BRI">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Nomor Rekening</label>
                                <input type="text" name="nomor_rekening" class="form-control" value="{{ $mitraProfile->nomor_rekening ?? '' }}" required placeholder="Contoh: 7012345678">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Nama Pemilik Rekening</label>
                                <input type="text" name="nama_rekening" class="form-control" value="{{ Auth::user()->nama ?? '' }}" required placeholder="Contoh: Hendra Wijaya">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold text-dark">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Tulis catatan atau keperluan jika ada..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top p-3">
                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Ajukan Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

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