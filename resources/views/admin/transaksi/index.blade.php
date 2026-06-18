@extends('layouts.admin')

@section('title', 'Keuangan & Bagi Hasil - Admin')
@section('page_title', 'Keuangan & Komisi')
@section('breadcrumb', 'Admin / Keuangan')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm"><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- KARTU RINGKASAN --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-wallet2 fs-1 text-white"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white text-opacity-75 fw-medium text-uppercase letter-spacing-1 small">Total Profit Perusahaan (30%)</p>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($profitPerusahaan, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning text-dark">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-50 p-3 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-cash-stack fs-1 text-dark"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-dark text-opacity-75 fw-medium text-uppercase letter-spacing-1 small">Saldo Mitra Belum Dicairkan (70%)</p>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($totalHutangMitra, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB NAVIGASI --}}
    <ul class="nav nav-pills mb-4" id="keuanganTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold px-4 rounded-pill me-2 shadow-sm" id="pengajuan-tab" data-bs-toggle="tab" data-bs-target="#pengajuan-pane" type="button">
                Pengajuan Pending
                @php $pendingCount = $riwayatPencairan->where('status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 rounded-pill bg-white border text-secondary me-2" id="saldo-tab" data-bs-toggle="tab" data-bs-target="#saldo-pane" type="button">Saldo & Hak Komisi</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 rounded-pill bg-white border text-secondary" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat-pane" type="button"><i class="bi bi-clock-history me-1"></i> Riwayat & Laporan</button>
        </li>
    </ul>

    <div class="tab-content">
        
        {{-- TAB 1: PENGAJUAN PENDING --}}
        <div class="tab-pane fade show active" id="pengajuan-pane">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-hourglass-split text-warning me-2"></i>Daftar Pengajuan Pencairan Dana Pending</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 fw-semibold">Tanggal Pengajuan</th>
                                    <th class="py-3 fw-semibold">Nama Mitra</th>
                                    <th class="py-3 fw-semibold">Tujuan Transfer</th>
                                    <th class="py-3 fw-semibold">Nominal</th>
                                    <th class="py-3 pe-4 fw-semibold text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse($riwayatPencairan->where('status', 'pending') as $r)
                                <tr>
                                    <td class="ps-4 py-3 text-muted small">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }} WIB</td>
                                    <td class="py-3">
                                        <div class="fw-bold text-dark">{{ $r->pemilik->user->nama ?? '-' }}</div>
                                        <div class="small text-muted">{{ $r->pemilik->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 small text-dark">
                                        <strong>{{ $r->nama_bank ?? '-' }}</strong><br>
                                        <span class="text-muted">{{ $r->nomor_rekening ?? '-' }} a.n. {{ $r->nama_rekening ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 fw-bold text-dark">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</td>
                                    <td class="pe-4 py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-primary fw-bold rounded-3 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#prosesPencairanModal{{ $r->id }}">
                                            <i class="bi bi-pencil-square me-1"></i> Proses
                                        </button>
                                    </td>
                                </tr>

                                {{-- MODAL PROSES PENCAIRAN --}}
                                <div class="modal fade text-start" id="prosesPencairanModal{{ $r->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('admin.transaksi.proses_pencairan', $r->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header border-bottom bg-light">
                                                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-shield-check text-primary me-2"></i> Proses Pengajuan Pencairan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <strong>Mitra:</strong> {{ $r->pemilik->user->nama ?? '-' }}<br>
                                                        <strong>Bank:</strong> {{ $r->nama_bank }} ({{ $r->nomor_rekening }} a.n. {{ $r->nama_rekening }})<br>
                                                        <strong>Nominal:</strong> Rp {{ number_format($r->jumlah, 0, ',', '.') }}<br>
                                                        @if($r->catatan)
                                                            <strong>Catatan Mitra:</strong> "{{ $r->catatan }}"
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Pilih Tindakan <span class="text-danger">*</span></label>
                                                        <select name="aksi" class="form-select" id="aksiSelect{{ $r->id }}" required onchange="toggleFormFields({{ $r->id }})">
                                                            <option value="setujui">Setujui & Transfer</option>
                                                            <option value="tolak">Tolak Pengajuan</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3" id="buktiTransferField{{ $r->id }}">
                                                        <label class="form-label fw-semibold">Unggah Bukti Transfer / Resi <span class="text-danger">*</span></label>
                                                        <input type="file" name="bukti_transfer" class="form-control" accept="image/*" id="buktiInput{{ $r->id }}" required>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold" id="catatanLabel{{ $r->id }}">Catatan Admin (Opsional)</label>
                                                        <textarea name="catatan_admin" class="form-control" rows="2" placeholder="Tulis alasan jika ditolak, atau memo transfer jika disetujui..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-top">
                                                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4 shadow-sm"><i class="bi bi-check-lg me-1"></i> Simpan & Kirim</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-check-circle display-6 d-block mb-2 opacity-25 text-success"></i>Tidak ada pengajuan pencairan pending.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- TAB 2: DAFTAR MITRA & SALDO --}}
        <div class="tab-pane fade" id="saldo-pane">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Hak Komisi Mitra Rental</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 fw-semibold">Nama Mitra</th>
                                    <th class="py-3 fw-semibold">Informasi Bank</th>
                                    <th class="py-3 fw-semibold">Total Pendapatan (70%)</th>
                                    <th class="py-3 fw-semibold text-danger">Saldo Aktif (Siap Cair)</th>
                                    <th class="py-3 pe-4 fw-semibold text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse($mitras as $m)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $m->user->nama ?? '-' }}</div>
                                        <div class="small text-muted">{{ $m->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-bold text-primary">{{ $m->nama_bank }}</div>
                                        <div class="font-monospace small bg-light d-inline-block px-2 border rounded">{{ $m->nomor_rekening }}</div>
                                    </td>
                                    <td class="py-3 text-success fw-medium">Rp {{ number_format($m->total_pendapatan, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        @if($m->saldo_berjalan > 0)
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 fs-6">Rp {{ number_format($m->saldo_berjalan, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted small"><i class="bi bi-check-all text-success"></i> Saldo Kosong</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-primary fw-bold rounded-3 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#transferModal{{ $m->id_user }}" {{ $m->saldo_berjalan <= 0 ? 'disabled' : '' }}>
                                            <i class="bi bi-send-fill me-1"></i> Transfer
                                        </button>
                                    </td>
                                </tr>

                                {{-- MODAL TRANSFER DANA --}}
                                <div class="modal fade text-start" id="transferModal{{ $m->id_user }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('admin.transaksi.transfer') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id_pemilik_mobil" value="{{ $m->id_user }}">
                                                <div class="modal-header border-bottom bg-light">
                                                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-bank2 text-primary me-2"></i> Transfer Komisi Mitra</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 rounded-3 mb-4">
                                                        Transfer saldo ke rekening: <br>
                                                        <strong>{{ $m->nama_bank }} - {{ $m->nomor_rekening }}</strong> (a.n {{ $m->user->nama }})
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Jumlah yang Ditransfer (Rp) <span class="text-danger">*</span></label>
                                                        <input type="number" name="jumlah_transfer" class="form-control form-control-lg fw-bold text-success" value="{{ $m->saldo_berjalan }}" max="{{ $m->saldo_berjalan }}" required>
                                                        <div class="form-text">Maksimal penarikan: Rp {{ number_format($m->saldo_berjalan, 0, ',', '.') }}</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Unggah Bukti Transfer / Mutasi <span class="text-danger">*</span></label>
                                                        <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label fw-semibold">Catatan (Opsional)</label>
                                                        <textarea name="catatan" class="form-control" rows="2" placeholder="Cth: Pencairan komisi bulan ini..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-top">
                                                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4 shadow-sm" onclick="return confirm('Yakin sudah melakukan transfer?')"><i class="bi bi-check-lg me-1"></i> Konfirmasi Transfer Selesai</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada mitra terdaftar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 3: RIWAYAT TRANSFER --}}
        <div class="tab-pane fade" id="riwayat-pane">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-receipt text-secondary me-2"></i>Riwayat Bukti Pencairan Ke Mitra</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 fw-semibold">Tanggal</th>
                                    <th class="py-3 fw-semibold">Penerima (Mitra)</th>
                                    <th class="py-3 fw-semibold">Tujuan Transfer</th>
                                    <th class="py-3 fw-semibold">Jumlah Cair</th>
                                    <th class="py-3 fw-semibold">Status</th>
                                    <th class="py-3 pe-4 fw-semibold text-end">Detail/Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse($riwayatPencairan->whereIn('status', ['disetujui', 'ditolak']) as $r)
                                <tr>
                                    <td class="ps-4 py-3 text-muted small">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }}</td>
                                    <td class="py-3 fw-bold text-dark">{{ $r->pemilik->user->nama ?? '-' }}</td>
                                    <td class="py-3 small text-dark">
                                        @if($r->nama_bank)
                                            <strong>{{ $r->nama_bank }}</strong><br>
                                            <span class="text-muted">{{ $r->nomor_rekening }}</span>
                                        @else
                                            <span class="text-muted">Transfer Manual</span>
                                        @endif
                                    </td>
                                    <td class="py-3 fw-bold {{ $r->status == 'disetujui' ? 'text-danger' : 'text-dark' }}">
                                        {{ $r->status == 'disetujui' ? '-' : '' }} Rp {{ number_format($r->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3">
                                        @if($r->status == 'disetujui')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded-pill">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        @if($r->bukti_transfer)
                                            <a href="{{ asset('storage/' . $r->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-3 shadow-sm"><i class="bi bi-image me-1"></i> Bukti</a>
                                        @elseif($r->catatan_admin)
                                            <span class="small text-danger fst-italic" title="{{ $r->catatan_admin }}">{{ Str::limit($r->catatan_admin, 20) }}</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>Belum ada riwayat pencairan dana.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Script untuk memindah-mindahkan tab style --}}
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

        function toggleFormFields(id) {
            var select = document.getElementById('aksiSelect' + id);
            var buktiField = document.getElementById('buktiTransferField' + id);
            var buktiInput = document.getElementById('buktiInput' + id);
            var catatanLabel = document.getElementById('catatanLabel' + id);
            
            if (select.value === 'tolak') {
                buktiField.style.display = 'none';
                buktiInput.removeAttribute('required');
                catatanLabel.innerHTML = 'Alasan Penolakan <span class="text-danger">*</span>';
                document.querySelector('#prosesPencairanModal' + id + ' textarea[name="catatan_admin"]').setAttribute('required', 'required');
            } else {
                buktiField.style.display = 'block';
                buktiInput.setAttribute('required', 'required');
                catatanLabel.innerHTML = 'Catatan Admin (Opsional)';
                document.querySelector('#prosesPencairanModal' + id + ' textarea[name="catatan_admin"]').removeAttribute('required');
            }
        }
    </script>
@endsection