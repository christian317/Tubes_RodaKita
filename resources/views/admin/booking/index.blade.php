@extends('layouts.admin')

@section('title', 'Manajemen Jadwal & Booking - Admin')
@section('page_title', 'Jadwal & Pemesanan')
@section('breadcrumb', 'Admin / Jadwal & Pemesanan')

@section('content')

    {{-- CSS Dipanggil Langsung --}}
    <style>
        #calendar {
            background-color: #ffffff;
            border-radius: 1rem;
            min-height: 750px;
        }
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            padding: 4px 6px;
            font-size: 0.85em;
            border: none;
            color: #fff !important;
        }
        .fc-toolbar-title { font-weight: 700 !important; color: #1e293b; font-size: 1.5rem !important; }
        .fc-dayGridMonth-view .fc-daygrid-day-number { color: #475569; text-decoration: none; padding: 8px; }
        .fc-theme-standard td, .fc-theme-standard th { border-color: #e2e8f0; }
    </style>

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
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $bookings->count() }}</div>
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
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $bookings->where('status', 'menunggu_approval')->count() }}</div>
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
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $bookings->whereIn('status', ['menunggu', 'dibayar', 'disewakan'])->count() }}</div>
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
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $bookings->where('status', 'selesai')->count() }}</div>
                        <div class="text-muted small mt-1">Telah Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 1: TABEL DAFTAR PEMESANAN --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-auto">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-list-check text-primary me-2"></i>Daftar Pengajuan & Transaksi
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Klik tombol detail untuk melihat info lengkap dan melakukan persetujuan.</p>
                </div>
                <div class="col-12 col-md ms-md-auto">
                    <div class="input-group input-group-sm rounded-3 border ms-auto" style="width:250px;">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Cari pemesan...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0" id="tabelBooking">
                    <thead class="sticky-top bg-light">
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
                            @if ($b->status == 'menunggu_approval')
                                <tr class="booking-row" data-nama="{{ strtolower(($b->user->nama ?? '') . ' ' . ($b->mobil->plat_nomer ?? '')) }}">
                                    {{-- ID & Tanggal --}}
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark mb-1">#ORD-{{ $b->id }}</div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($b->tanggal_mulai)->format('d M y') }} - 
                                            {{ \Carbon\Carbon::parse($b->tanggal_selesai)->format('d M y') }}
                                        </div>
                                    </td>

                                    {{-- Pemesan & Layanan --}}
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

                                    {{-- Mobil --}}
                                    <td class="py-3">
                                        <div class="fw-bold text-dark small">{{ $b->mobil->brand->nama_brand ?? '-' }} {{ $b->mobil->model ?? '-' }}</div>
                                        <span class="badge bg-dark bg-opacity-10 text-dark font-monospace px-2 py-1 border mt-1">{{ $b->mobil->plat_nomer ?? '-' }}</span>
                                    </td>

                                    {{-- Status --}}
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

                                    {{-- Aksi: Tombol Buka Modal --}}
                                    <td class="pe-4 py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-primary rounded-3 shadow-sm fw-medium px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $b->id }}">
                                            <i class="bi bi-list-ul me-1"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @empty
                        <tr id="emptyRow">
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>
                                    <p class="fw-semibold mb-1">Belum ada data pemesanan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="noResult" class="text-center py-5 d-none"><p class="text-muted mb-0">Pencarian tidak ditemukan.</p></div>
        </div>
    </div>

    {{-- BAGIAN 2: KALENDER JADWAL (HANYA YANG SUDAH DISETUJUI) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-calendar-month text-primary me-2"></i>Kalender Internal Armada
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Menampilkan rekap jadwal harian armada yang sedang disewa.</p>
                </div>
            </div>
        </div>
        <div class="card-body p-4 bg-light rounded-bottom-4">
            {{-- Tombol Rahasia Untuk Membuka Modal Kalender dari Javascript (Anti-Bug Bootstrap) --}}
            <button id="btnOpenDailyModal" class="d-none" data-bs-toggle="modal" data-bs-target="#dailyBookingsModal"></button>
            
            <div id='calendar' class="shadow-sm border"></div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- DAFTAR SEMUA MODAL DITEMPATKAN DI SINI (LUAR TABEL)        --}}
    {{-- ========================================================== --}}

    {{-- 1. MODAL REKAP HARIAN DARI KALENDER --}}
    <div class="modal fade" id="dailyBookingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-calendar-day text-primary me-2"></i>Jadwal Tanggal: <span id="dailyModalTitle" class="text-primary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">ID Pesanan</th>
                                    <th class="py-3">Unit Mobil</th>
                                    <th class="py-3">Penyewa</th>
                                    <th class="py-3">Status</th>
                                    <th class="pe-4 py-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="dailyModalBody">
                                {{-- Diisi otomatis oleh Javascript --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup Kalender</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MODAL DETAIL UNTUK MASING-MASING BOOKING --}}
    @foreach($bookings as $b)
    <div class="modal fade" id="detailModal{{ $b->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $b->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold" id="detailModalLabel{{ $b->id }}">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i> Detail Pesanan #ORD-{{ $b->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-3">Informasi Pelanggan</h6>
                            <table class="table table-sm table-borderless">
                                <tr><td class="text-muted w-50">Nama</td><td class="fw-bold">{{ $b->user->nama ?? '-' }}</td></tr>
                                <tr><td class="text-muted">No. Telp</td><td class="fw-bold">{{ $b->user->no_telepon ?? '-' }}</td></tr>
                                <tr><td class="text-muted">Layanan</td><td class="fw-bold text-primary">{{ str_replace('_', ' ', strtoupper($b->tipe_layanan)) }}</td></tr>
                            </table>

                            <h6 class="fw-bold text-dark mb-3 mt-4">Jadwal & Biaya</h6>
                            <table class="table table-sm table-borderless">
                                <tr><td class="text-muted w-50">Mobil</td><td class="fw-bold">{{ $b->mobil->brand->nama_brand ?? '' }} {{ $b->mobil->model ?? '' }}</td></tr>
                                <tr><td class="text-muted">Plat</td><td class="fw-bold"><span class="badge bg-light border text-dark">{{ $b->mobil->plat_nomer ?? '-' }}</span></td></tr>
                                <tr><td class="text-muted">Pengambilan</td><td class="fw-bold">{{ \Carbon\Carbon::parse($b->tanggal_mulai)->format('d M Y') }}</td></tr>
                                <tr><td class="text-muted">Pengembalian</td><td class="fw-bold">{{ \Carbon\Carbon::parse($b->tanggal_selesai)->format('d M Y') }}</td></tr>
                                <tr><td class="text-muted">Total Bayar</td><td class="fw-bold text-success fs-5">Rp {{ number_format($b->pembayaran->total_pembayaran ?? 0, 0, ',', '.') }}</td></tr>
                            </table>
                        </div>

                        <div class="col-md-6 border-start">
                            <h6 class="fw-bold text-dark mb-3">Dokumen KTP</h6>
                            @if($b->tipe_layanan == 'lepas_kunci' && $b->foto_ktp)
                                <img src="{{ asset('storage/' . $b->foto_ktp) }}" class="img-fluid rounded-3 border shadow-sm w-100" style="max-height:200px; object-fit:cover;">
                            @elseif($b->tipe_layanan == 'dengan_supir')
                                <div class="bg-light p-4 rounded-3 text-center text-muted h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-person-badge fs-1 mb-2 opacity-50"></i>
                                    <small>Layanan dengan supir tidak memerlukan KTP.</small>
                                </div>
                            @else
                                <div class="alert alert-warning small">KTP tidak dilampirkan.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-3 d-flex justify-content-between">
                    <div>
                        <span class="text-muted small me-2">Status:</span>
                        <span class="badge bg-dark bg-opacity-10 text-dark border px-2 py-1">{{ strtoupper($b->status) }}</span>
                    </div>

                    <div class="d-flex gap-2">
                        @if($b->status == 'menunggu_approval')
                            <form action="{{ route('admin.booking.reject', $b->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger fw-bold" onclick="return confirm('Yakin tolak pesanan ini?')">Tolak & Batal</button>
                            </form>
                            <form action="{{ route('admin.booking.approve', $b->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success fw-bold shadow-sm" onclick="return confirm('Setujui pesanan ini?')"><i class="bi bi-check-lg me-1"></i> Approve</button>
                            </form>
                        @elseif($b->status == 'menunggu' || $b->status == 'dibayar')
                            <form action="{{ route('admin.booking.updateStatus', $b->id) }}" method="POST">
                                @csrf <input type="hidden" name="status" value="disewakan">
                                <button type="submit" class="btn btn-primary fw-bold shadow-sm" onclick="return confirm('Mobil diambil pelanggan?')"><i class="bi bi-key me-1"></i> Serahkan Mobil</button>
                            </form>
                        @elseif($b->status == 'disewakan')
                            <form action="{{ route('admin.booking.updateStatus', $b->id) }}" method="POST">
                                @csrf <input type="hidden" name="status" value="selesai">
                                <button type="submit" class="btn btn-success fw-bold shadow-sm" onclick="return confirm('Terima mobil dari pelanggan?')"><i class="bi bi-flag me-1"></i> Selesaikan Sewa</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup Detail</button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

@endsection

@push('scripts')
    {{-- KODE JAVASCRIPT --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika Pencarian Tabel
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.booking-row');
            if(searchInput) {
                searchInput.addEventListener('input', function() {
                    const term = this.value.toLowerCase();
                    rows.forEach(row => {
                        const nama = row.getAttribute('data-nama');
                        row.style.display = nama.includes(term) ? '' : 'none';
                    });
                });
            }

            // Inisialisasi FullCalendar
            var calendarEl = document.getElementById('calendar');
            var eventsData = @json($events);

            // Mengelompokkan booking berdasarkan hari
            let occupiedDates = {};
            
            eventsData.forEach(evt => {
                let d = new Date(evt.start);
                let endD = new Date(evt.end); 
                
                while(d < endD) {
                    let year = d.getFullYear();
                    let month = String(d.getMonth() + 1).padStart(2, '0');
                    let day = String(d.getDate()).padStart(2, '0');
                    let dateStr = `${year}-${month}-${day}`;

                    if(!occupiedDates[dateStr]) {
                        occupiedDates[dateStr] = [];
                    }
                    occupiedDates[dateStr].push(evt);

                    d.setDate(d.getDate() + 1);
                }
            });

            // Ubah format untuk kalender
            let summaryEvents = Object.keys(occupiedDates).map(dateStr => {
                let count = occupiedDates[dateStr].length;
                return {
                    title: count + ' Pesanan (Detail)',
                    start: dateStr,
                    allDay: true,
                    color: '#0d6efd',
                    extendedProps: {
                        date: dateStr,
                        bookings: occupiedDates[dateStr]
                    }
                };
            });

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu'
                },
                events: summaryEvents,
                
                eventClick: function(info) {
                    let props = info.event.extendedProps;
                    let bookings = props.bookings;
                    
                    let dateObj = new Date(props.date);
                    let options = { day: 'numeric', month: 'long', year: 'numeric' };
                    document.getElementById('dailyModalTitle').innerText = dateObj.toLocaleDateString('id-ID', options);
                    
                    let tbody = document.getElementById('dailyModalBody');
                    tbody.innerHTML = '';
                    
                    bookings.forEach(b => {
                        let platSplit = b.title.split(' | ');
                        let platNomer = platSplit[0] || '-';
                        let namaPenyewa = platSplit[1] || 'Sistem';
                        
                        let tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="ps-4 fw-bold">#ORD-${b.id}</td>
                            <td>
                                <div class="fw-bold text-dark">${b.extendedProps.mobil}</div>
                                <span class="badge bg-light border text-dark mt-1">${platNomer}</span>
                            </td>
                            <td class="fw-medium">${namaPenyewa}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary border">${b.extendedProps.status.toUpperCase()}</span></td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-sm btn-primary rounded-3 shadow-sm px-3" 
                                    data-bs-dismiss="modal" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailModal${b.id}">
                                    Detail & Aksi
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                    
                    // Membuka Modal Harian menggunakan tombol pemicu rahasia
                    document.getElementById('btnOpenDailyModal').click();
                }
            });

            calendar.render();
        });
    </script>
@endpush