@extends('layouts.admin')

@section('title', 'Manajemen Jadwal & Booking - Admin')
@section('page_title', 'Jadwal & Pemesanan')
@section('breadcrumb', 'Admin / Jadwal & Pemesanan')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;">
                        <i class="bi bi-receipt fs-5 text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $totalPesanan }}</div>
                        <div class="text-muted small mt-1">Total Pesanan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-warning bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;">
                        <i class="bi bi-shield-exclamation fs-5 text-warning"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $perluPersetujuan }}</div>
                        <div class="text-muted small mt-1">Perlu Persetujuan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-info bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;">
                        <i class="bi bi-car-front fs-5 text-info"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $aktifDisewakan }}</div>
                        <div class="text-muted small mt-1">Aktif & Disewakan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;">
                        <i class="bi bi-check2-all fs-5 text-success"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $telahSelesai }}</div>
                        <div class="text-muted small mt-1">Telah Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB NAVIGASI STATUS --}}
    <div class="bg-white rounded-4 shadow-sm border mb-4 overflow-hidden">
        <ul class="nav nav-pills flex-nowrap overflow-auto p-2" style="white-space: nowrap; scrollbar-width: none;">
            @php $currentStatus = request('status', session('with_tab_status', 'semua')); @endphp
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'semua' ? 'active fw-bold shadow-sm' : 'text-muted fw-medium' }}" href="{{ route('admin.booking.index', array_merge(request()->except('page'), ['status' => 'semua'])) }}">Semua Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'menunggu_approval' ? 'bg-warning text-dark fw-bold shadow-sm' : 'text-muted fw-medium' }}" href="{{ route('admin.booking.index', array_merge(request()->except('page'), ['status' => 'menunggu_approval'])) }}">Menunggu Approval</a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'menunggu' ? 'bg-info text-dark fw-bold shadow-sm' : 'text-muted fw-medium' }}" href="{{ route('admin.booking.index', array_merge(request()->except('page'), ['status' => 'menunggu'])) }}">Menunggu Diambil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'disewakan' ? 'active fw-bold shadow-sm' : 'text-muted fw-medium' }}" href="{{ route('admin.booking.index', array_merge(request()->except('page'), ['status' => 'disewakan'])) }}">Sedang Disewakan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'selesai' ? 'bg-success text-white fw-bold shadow-sm' : 'text-muted fw-medium' }}" href="{{ route('admin.booking.index', array_merge(request()->except('page'), ['status' => 'selesai'])) }}">Selesai</a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 {{ $currentStatus == 'batal' ? 'bg-danger text-white fw-bold shadow-sm' : 'text-muted fw-medium' }}" href="{{ route('admin.booking.index', array_merge(request()->except('page'), ['status' => 'batal'])) }}">Dibatalkan</a>
            </li>
        </ul>
    </div>

    {{-- KOTAK FILTER & TABEL UTAMA --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
            <form method="GET" action="{{ route('admin.booking.index') }}" id="filterForm">
                <input type="hidden" name="status" value="{{ $currentStatus }}">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-xl-4">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-list-check text-primary me-2"></i>Daftar Transaksi</h5>
                        <p class="text-muted small mb-0 mt-1">Kelola status, penyerahan kunci, rekam fisik & ulasan</p>
                    </div>
                    <div class="col-12 col-xl-8">
                        <div class="row g-2 justify-content-xl-end">
                            <div class="col-12 col-sm-4 col-md-3">
                                <div class="input-group input-group-sm rounded-3 border">
                                    <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 shadow-none" placeholder="Nama, plat, model...">
                                </div>
                            </div>
                            <div class="col-6 col-sm-3 col-md-2">
                                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control form-control-sm rounded-3 border text-muted">
                            </div>
                            <div class="col-6 col-sm-3 col-md-2">
                                <select name="bulan" class="form-select form-select-sm rounded-3 border">
                                    <option value="">Pilih Bulan</option>
                                    @foreach([1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $num => $name)
                                        <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-sm-3 col-md-2">
                                <select name="tahun" class="form-select form-select-sm rounded-3 border">
                                    <option value="">Pilih Tahun</option>
                                    @for($y = date('Y') + 1; $y >= date('Y') - 2; $y--)
                                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-6 col-sm-3 col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm rounded-3 w-50"><i class="bi bi-funnel-fill"></i></button>
                                <a href="{{ route('admin.booking.index', ['status' => $currentStatus]) }}" class="btn btn-light border btn-sm rounded-3 w-50"><i class="bi bi-arrow-counterclockwise"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelBooking">
                    <thead class="bg-light">
                        <tr class="text-muted small text-uppercase">
                            <th class="ps-4 py-3 fw-semibold border-bottom-0">ID / Tgl Sewa</th>
                            <th class="py-3 fw-semibold border-bottom-0">Pemesan & Layanan</th>
                            <th class="py-3 fw-semibold border-bottom-0">Unit Mobil</th>
                            <th class="py-3 fw-semibold border-bottom-0">Status</th>
                            <th class="py-3 pe-4 fw-semibold border-bottom-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top">
                        @forelse($bookings as $b)
                        <tr class="booking-row">
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark mb-1">#ORD-{{ $b->id }}</div>
                                <div class="text-muted small">{{ \Carbon\Carbon::parse($b->tanggal_mulai)->format('d M y') }} - {{ \Carbon\Carbon::parse($b->tanggal_selesai)->format('d M y') }}</div>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-dark lh-sm mb-1">{{ $b->user->nama ?? 'Sistem User' }}</div>
                                <div>
                                    @if($b->tipe_layanan == 'lepas_kunci')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 fw-normal">Lepas Kunci</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 fw-normal">Dengan Supir</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-dark small">{{ $b->mobil->brand->nama_brand ?? '-' }} {{ $b->mobil->model ?? '-' }}</div>
                                <span class="badge bg-dark bg-opacity-10 text-dark font-monospace px-2 py-1 border mt-1">{{ $b->mobil->plat_nomer ?? '-' }}</span>
                            </td>
                            <td class="py-3">
                                @if($b->status == 'menunggu_approval')
                                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-semibold"><i class="bi bi-hourglass-split me-1"></i> Menunggu Approval</span>
                                @elseif($b->status == 'menunggu' || $b->status == 'dibayar')
                                    <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2 fw-semibold"><i class="bi bi-clock me-1"></i> Menunggu Diambil</span>
                                @elseif($b->status == 'disewakan')
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 fw-semibold"><i class="bi bi-car-front-fill me-1"></i> Disewakan</span>
                                @elseif($b->status == 'selesai')
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 fw-semibold"><i class="bi bi-flag-fill me-1"></i> Selesai</span>
                                @else
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 fw-semibold"><i class="bi bi-x-circle me-1"></i> Dibatalkan</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <button type="button" class="btn btn-sm btn-primary rounded-3 shadow-sm fw-medium px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $b->id }}">
                                    <i class="bi bi-list-ul me-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted"><i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i><p class="fw-semibold mb-1">Tidak ada data transaksi</p></div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bookings->hasPages())
        <div class="card-footer bg-white border-top p-3 d-flex justify-content-center">{{ $bookings->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- MODAL DETAIL --}}
    @foreach($bookings as $b)
    <div class="modal fade" id="detailModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i> Detail Pesanan #ORD-{{ $b->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <ul class="nav nav-tabs mb-4" id="modalTab{{ $b->id }}" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold small" id="info-tab{{ $b->id }}" data-bs-toggle="tab" data-bs-target="#info-pane{{ $b->id }}" type="button" role="tab">1. Info Pemesanan</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold small text-success" id="inspeksi-tab{{ $b->id }}" data-bs-toggle="tab" data-bs-target="#inspeksi-pane{{ $b->id }}" type="button" role="tab"><i class="bi bi-shield-check me-1"></i> 2. Form & Kondisi Mobil</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="modalTabContent{{ $b->id }}">
                        {{-- PANE 1: RINGKASAN DATA PEMESANAN --}}
                        <div class="tab-pane fade show active" id="info-pane{{ $b->id }}" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-dark mb-3">Informasi Pelanggan</h6>
                                    <table class="table table-sm table-borderless small">
                                        <tr><td class="text-muted w-50">Nama</td><td class="fw-bold">{{ $b->user->nama ?? '-' }}</td></tr>
                                        <tr><td class="text-muted">No. Telp</td><td class="fw-bold">{{ $b->user->no_telepon ?? '-' }}</td></tr>
                                        <tr><td class="text-muted">Layanan</td><td class="fw-bold text-primary">{{ str_replace('_', ' ', strtoupper($b->tipe_layanan)) }}</td></tr>
                                    </table>
                                    <h6 class="fw-bold text-dark mb-3 mt-4">Jadwal & Biaya</h6>
                                    <table class="table table-sm table-borderless small">
                                        <tr><td class="text-muted w-50">Mobil</td><td class="fw-bold">{{ $b->mobil->brand->nama_brand ?? '' }} {{ $b->mobil->model ?? '' }}</td></tr>
                                        <tr><td class="text-muted">Plat</td><td class="fw-bold"><span class="badge bg-light border text-dark">{{ $b->mobil->plat_nomer ?? '-' }}</span></td></tr>
                                        <tr><td class="text-muted">Pengambilan</td><td class="fw-bold">{{ \Carbon\Carbon::parse($b->tanggal_mulai)->format('d M Y, H:i') }}</td></tr>
                                        <tr><td class="text-muted">Pengembalian</td><td class="fw-bold">{{ \Carbon\Carbon::parse($b->tanggal_selesai)->format('d M Y, H:i') }}</td></tr>
                                        <tr><td class="text-muted">Total Bayar</td><td class="fw-bold text-success fs-5">Rp {{ number_format($b->pembayaran->total_pembayaran ?? 0, 0, ',', '.') }}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6 border-start">
                                    <h6 class="fw-bold text-dark mb-3">Dokumen KTP Pelanggan</h6>
                                    @if($b->tipe_layanan == 'lepas_kunci' && $b->foto_ktp)
                                        <img src="{{ asset('storage/' . $b->foto_ktp) }}" class="img-fluid rounded-3 border shadow-sm w-100" style="max-height:220px; object-fit:cover;">
                                    @else
                                        <div class="bg-light p-4 rounded-3 text-center text-muted"><i class="bi bi-person-badge fs-2 opacity-50 mb-2 d-block"></i><small>Bebas syarat upload KTP.</small></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- PANE 2: LOGIKA FORM & DATA KONDISI MOBIL --}}
                        <div class="tab-pane fade" id="inspeksi-pane{{ $b->id }}" role="tabpanel">
                            
                            {{-- FASE A: HANDOVER (MOBIL DIALIRKAN KELUAR) --}}
                            @if($b->status == 'menunggu' || $b->status == 'dibayar')
                                <form action="{{ route('admin.booking.serahkan', $b->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-box-arrow-up text-primary me-1"></i> Rekam Kondisi Mobil Keluar (Pengambilan)</h6>
                                    <div class="row g-3 small">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Odometer Awal (KM) <span class="text-danger">*</span></label>
                                            <input type="number" name="odometer" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Ketersediaan Bensin <span class="text-danger">*</span></label>
                                            <select name="indikator_bensin" class="form-select form-select-sm" required>
                                                <option value="Full Tank">Full Tank</option>
                                                <option value="3/4 Tank">3/4 Tank</option>
                                                <option value="1/2 Tank">1/2 Tank</option>
                                                <option value="1/4 Tank">1/4 Tank</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Detail Kondisi Eksterior <span class="text-danger">*</span></label>
                                            <input type="text" name="kondisi_eksterior" class="form-control form-control-sm" placeholder="Contoh: Mulus / Lecet bemper depan" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Detail Kondisi Interior <span class="text-danger">*</span></label>
                                            <input type="text" name="kondisi_interior" class="form-control form-control-sm" placeholder="Contoh: Bersih / AC dingin" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Catatan Tambahan</label>
                                            <textarea name="catatan" class="form-control form-control-sm" rows="2"></textarea>
                                        </div>
                                        
                                        <div class="col-12 mt-3">
                                            <label class="form-label fw-bold text-secondary mb-1">Dokumentasi Foto Fisik Kendaraan (Pilih banyak file sekaligus) <span class="text-danger">*</span></label>
                                            <input type="file" name="foto[]" class="form-control form-control-sm" accept="image/*" multiple required>
                                            <small class="text-muted"><i class="bi bi-info-circle"></i> Tahan tombol Ctrl (Windows) / Cmd (Mac) saat memilih file.</small>
                                        </div>
                                    </div>
                                    <div class="text-end mt-4"><button type="submit" class="btn btn-primary fw-bold rounded-3 shadow-sm px-4"><i class="bi bi-key-fill me-1"></i> Konfirmasi & Serahkan Mobil</button></div>
                                </form>

                            {{-- FASE B: RETURN CHECK (MOBIL DIKEMBALIKAN PELANGGAN) --}}
                            @elseif($b->status == 'disewakan')
                                @if($b->kondisiPengambilan)
                                    <div class="bg-light border rounded-3 p-3 mb-4 small text-dark">
                                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-info-circle me-1"></i> Perbandingan Kondisi Awal Saat Keluar</h6>
                                        <div class="row g-2 mb-2">
                                            <div class="col-6 col-md-3">KM Awal: <strong>{{ number_format($b->kondisiPengambilan->odometer) }} KM</strong></div>
                                            <div class="col-6 col-md-3">Bensin Awal: <strong>{{ $b->kondisiPengambilan->indikator_bensin }}</strong></div>
                                            <div class="col-6 col-md-3">Eksterior: <strong>{{ $b->kondisiPengambilan->kondisi_eksterior }}</strong></div>
                                            <div class="col-6 col-md-3">Interior: <strong>{{ $b->kondisiPengambilan->kondisi_interior }}</strong></div>
                                        </div>
                                        @if(is_array($b->kondisiPengambilan->foto_kendaraan))
                                            <div class="d-flex gap-2 mt-2 overflow-auto pb-2">
                                                @foreach($b->kondisiPengambilan->foto_kendaraan as $path)
                                                    <img src="{{ asset('storage/' . $path) }}" class="rounded border" style="height: 60px; width: 60px; object-fit: cover;">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <form action="{{ route('admin.booking.terima', $b->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h6 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="bi bi-box-arrow-in text-success me-1"></i> Rekam Kondisi Mobil Masuk (Pengembalian)</h6>
                                    <div class="row g-3 small">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Odometer Kembali (KM) <span class="text-danger">*</span></label>
                                            <input type="number" name="odometer" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Ketersediaan Bensin <span class="text-danger">*</span></label>
                                            <select name="indikator_bensin" class="form-select form-select-sm" required>
                                                <option value="Full Tank">Full Tank</option>
                                                <option value="3/4 Tank">3/4 Tank</option>
                                                <option value="1/2 Tank">1/2 Tank</option>
                                                <option value="1/4 Tank">1/4 Tank</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Denda Kerusakan / BBM (Rp)</label>
                                            <input type="number" name="denda" class="form-control form-control-sm" placeholder="Isi 0 jika aman" value="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Kondisi Eksterior Kembali <span class="text-danger">*</span></label>
                                            <input type="text" name="kondisi_eksterior" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Kondisi Interior Kembali <span class="text-danger">*</span></label>
                                            <input type="text" name="kondisi_interior" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Catatan Klaim Kerusakan</label>
                                            <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Tulis deskripsi denda jika ada"></textarea>
                                        </div>
                                        
                                        <!-- INPUT MULTIPLE FOTO PENGEMBALIAN -->
                                        <div class="col-12 mt-3">
                                            <label class="form-label fw-bold text-secondary mb-1">Dokumentasi Foto Pengembalian (Bisa pilih banyak file sekaligus) <span class="text-danger">*</span></label>
                                            <input type="file" name="foto[]" class="form-control form-control-sm" accept="image/*" multiple required>
                                        </div>

                                        <!-- TAMBAHAN FORM ULASAN PELANGGAN -->
                                        <div class="col-12 mt-4 border-top pt-3">
                                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-star-fill text-warning me-1"></i> Penilaian Untuk Pelanggan</h6>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold">Rating Pelanggan <span class="text-danger">*</span></label>
                                            <select name="rating_pelanggan" class="form-select form-select-sm" required>
                                                <option value="5">⭐⭐⭐⭐⭐ (5/5) Sangat Baik</option>
                                                <option value="4">⭐⭐⭐⭐ (4/5) Baik</option>
                                                <option value="3">⭐⭐⭐ (3/5) Cukup</option>
                                                <option value="2">⭐⭐ (2/5) Kurang</option>
                                                <option value="1">⭐ (1/5) Buruk</option>
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label fw-semibold">Catatan / Perilaku Pelanggan <span class="text-danger">*</span></label>
                                            <input type="text" name="catatan_pelanggan" class="form-control form-control-sm" placeholder="Contoh: Mobil dikembalikan tepat waktu dan bersih" required>
                                        </div>

                                    </div>
                                    <div class="text-end mt-4"><button type="submit" class="btn btn-success fw-bold rounded-3 shadow-sm px-4"><i class="bi bi-flag-fill me-1"></i> Selesaikan Sewa & Beri Penilaian</button></div>
                                </form>

                            {{-- FASE C: ARSIP DATA LENGKAP YANG SUDAH SELESAI --}}
                            @elseif($b->status == 'selesai')
                                <div class="row g-3 small">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light">
                                            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-box-arrow-up"></i> Kondisi Saat Keluar</h6>
                                            KM: <strong>{{ number_format($b->kondisiPengambilan->odometer ?? 0) }} KM</strong><br>
                                            Bensin: <strong>{{ $b->kondisiPengambilan->indikator_bensin ?? '-' }}</strong><br>
                                            Eksterior: <strong>{{ $b->kondisiPengambilan->kondisi_eksterior ?? '-' }}</strong><br>
                                            Interior: <strong>{{ $b->kondisiPengambilan->kondisi_interior ?? '-' }}</strong><br>
                                            Catatan: <span class="text-muted">"{{ $b->kondisiPengambilan->catatan ?? '-' }}"</span>
                                            
                                            @if($b->kondisiPengambilan && is_array($b->kondisiPengambilan->foto_kendaraan))
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                @foreach($b->kondisiPengambilan->foto_kendaraan as $path)
                                                    <img src="{{ asset('storage/' . $path) }}" class="rounded border" style="height: 50px; width: 50px; object-fit: cover;">
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light">
                                            <h6 class="fw-bold text-success mb-2"><i class="bi bi-box-arrow-in"></i> Kondisi Saat Kembali</h6>
                                            KM: <strong>{{ number_format($b->kondisiPengembalian->odometer ?? 0) }} KM</strong><br>
                                            Bensin: <strong>{{ $b->kondisiPengembalian->indikator_bensin ?? '-' }}</strong><br>
                                            Eksterior: <strong>{{ $b->kondisiPengembalian->kondisi_eksterior ?? '-' }}</strong><br>
                                            Interior: <strong>{{ $b->kondisiPengembalian->kondisi_interior ?? '-' }}</strong><br>
                                            Denda: <strong class="text-danger">Rp {{ number_format($b->kondisiPengembalian->denda ?? 0,0,',','.') }}</strong><br>
                                            Catatan: <span class="text-muted">"{{ $b->kondisiPengembalian->catatan ?? '-' }}"</span>
                                            
                                            @if($b->kondisiPengembalian && is_array($b->kondisiPengembalian->foto_kendaraan))
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                @foreach($b->kondisiPengembalian->foto_kendaraan as $path)
                                                    <img src="{{ asset('storage/' . $path) }}" class="rounded border" style="height: 50px; width: 50px; object-fit: cover;">
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- ARSIP ULASAN ADMIN KE PELANGGAN --}}
                                    @if($b->ulasanPelanggan)
                                        <div class="col-12 mt-3">
                                            <div class="p-3 border border-warning border-opacity-50 bg-warning bg-opacity-10 rounded-3 text-dark">
                                                <h6 class="fw-bold text-warning mb-2"><i class="bi bi-star-fill me-1"></i> Penilaian Anda untuk Pelanggan Ini</h6>
                                                Rating: <strong>{{ $b->ulasanPelanggan->rating }} / 5 Bintang</strong><br>
                                                Catatan: <span class="text-muted">"{{ $b->ulasanPelanggan->catatan }}"</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-center p-3 text-muted small mt-3"><i class="bi bi-lock-fill me-1"></i> Arsip data rekaman kondisi mobil terkunci permanen.</div>
                            @else
                                <div class="text-center p-4 text-muted small"><i class="bi bi-slash-circle me-1"></i> Tidak ada pencatatan kondisi untuk pemesanan yang batal.</div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- FOOTER MODAL --}}
                <div class="modal-footer bg-light border-top p-3 d-flex justify-content-between">
                    <div>
                        <span class="text-muted small me-2">Alur Utama:</span>
                        <span class="badge bg-dark bg-opacity-10 text-dark border px-2 py-1">{{ strtoupper($b->status) }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        @if($b->status == 'menunggu_approval')
                            <form action="{{ route('admin.booking.reject', $b->id) }}" method="POST">
                                @csrf <button type="submit" class="btn btn-outline-danger btn-sm fw-bold rounded-3" onclick="return confirm('Yakin tolak?')">Tolak</button>
                            </form>
                            <form action="{{ route('admin.booking.approve', $b->id) }}" method="POST">
                                @csrf <button type="submit" class="btn btn-success btn-sm fw-bold shadow-sm rounded-3" onclick="return confirm('Setujui?')"><i class="bi bi-check-lg me-1"></i> Approve</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

@endsection