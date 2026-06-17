@extends('layouts.mitra') {{-- Sesuaikan dengan nama file layout Anda --}}

@section('title', 'Monitoring Armada - Roda Kita')
@section('page_title', 'Monitoring Armada')
@section('breadcrumb', 'Mitra / Monitoring Mobil')

@push('styles')
    <style>
        /* Mengatur agar kotak tanggal memiliki posisi untuk menampung elemen silang */
        .fc .fc-daygrid-day {
            position: relative;
        }

        /* Kelas untuk tanggal yang sudah dibooking */
        .booked-cell {
            background-color: #dc3545 !important;
            /* Membuat silang besar dari sudut ke sudut dengan 2 gradient diagonal */
            background-image: 
                linear-gradient(to top right, transparent 49%, #ffffff 50%, #ffffff 52%, transparent 53%),
                linear-gradient(to top left, transparent 49%, #ffffff 50%, #ffffff 52%, transparent 53%) !important;
            background-size: 100% 100% !important; /* Memastikan silang memenuhi 100% kotak */
            color: white !important;
        }
        
        /* Tambahan: Pastikan nomor hari tetap terlihat jelas */
        .booked-cell .fc-daygrid-day-number {
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
    </style>
@endpush

@section('content')

    {{-- KARTU STATISTIK --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-car-front-fill fs-3 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Total Armada</div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $totalMobil }} <span
                                class="fs-6 fw-normal text-muted">Unit</span></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Siap Disewa</div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $tersedia }} <span
                                class="fs-6 fw-normal text-muted">Unit</span></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-key-fill fs-3 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Sedang Jalan (Disewa)</div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $sedangDisewa }} <span
                                class="fs-6 fw-normal text-muted">Unit</span></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL MONITORING MOBIL --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div
            class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-list-ul text-success me-2"></i>Daftar Kendaraan Anda</h5>
                <p class="text-muted small mb-0 mt-1">Pantau status real-time dan riwayat sewa masing-masing unit.</p>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3 fw-semibold border-bottom-0">Unit Mobil</th>
                            <th class="py-3 fw-semibold border-bottom-0">Kategori & Transmisi</th>
                            <th class="py-3 fw-semibold border-bottom-0">Status Real-time</th>
                            <th class="py-3 fw-semibold border-bottom-0">Harga / Hari</th>
                            <th class="py-3 pe-4 fw-semibold border-bottom-0 text-end">Jadwal & Riwayat</th>
                        </tr>
                    </thead>
                    <tbody class="border-top">
                        @forelse($mobils as $m)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light rounded-3 border overflow-hidden d-flex justify-content-center align-items-center"
                                            style="width: 80px; height: 60px;">
                                            @if ($m->gambar)
                                                <img src="{{ asset('storage/' . $m->gambar) }}"
                                                    class="w-100 h-100 object-fit-cover">
                                            @else
                                                <i class="bi bi-car-front text-muted fs-3"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $m->brand->nama_brand ?? '' }}
                                                {{ $m->model }}</div>
                                            <span
                                                class="badge bg-light border text-dark font-monospace mt-1">{{ $m->plat_nomer }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="text-dark fw-medium">{{ $m->kategori->nama_kategori ?? '-' }}</div>
                                    <div class="small text-muted">{{ $m->transmisi }}</div>
                                </td>
                                <td class="py-3">
                                    @if ($m->status_mobil == 'sewa')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill"><i
                                                class="bi bi-check-circle-fill me-1"></i> Tersedia / Stanby</span>
                                    @else
                                        <span
                                            class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill"><i
                                                class="bi bi-cone-striped me-1"></i> Sedang Disewa</span>
                                    @endif
                                </td>
                                <td class="py-3 fw-bold text-dark">
                                    Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary fw-bold rounded-3 px-3 shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#jadwalModal{{ $m->id }}">
                                    <button type="button"
                                        class="btn btn-sm btn-danger fw-bold rounded-3 px-3 shadow-sm ms-1"
                                        data-bs-toggle="modal" data-bs-target="#klaimModal{{ $m->id }}">
                                        <i class="bi bi-shield-exclamation me-1"></i> Ajukan Klaim
                                    </button>
                                        <i class="bi bi-calendar-week me-1"></i> Cek Jadwal
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL KALENDER (Hanya Blok Warna Merah) --}}
                            <div class="modal fade" id="jadwalModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-bottom px-4 py-3 bg-light rounded-top-4">
                                            <div>
                                                <h5 class="modal-title fw-bold text-dark mb-1"><i
                                                        class="bi bi-calendar2-range text-primary me-2"></i> Jadwal Sewa
                                                    Unit</h5>
                                                <div class="small text-muted">{{ $m->brand->nama_brand ?? '' }}
                                                    {{ $m->model }} ({{ $m->plat_nomer }})</div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 bg-white">

                                            @php
                                                $events = [];
                                                foreach ($m->bookings as $b) {
                                                    $events[] = [
                                                        'title' => 'Disewa',
                                                        'start' => \Carbon\Carbon::parse($b->tanggal_mulai)->format(
                                                            'Y-m-d\TH:i:s',
                                                        ),
                                                        'end' => \Carbon\Carbon::parse($b->tanggal_selesai)->format(
                                                            'Y-m-d\TH:i:s',
                                                        ),
                                                        'classNames' => ['booked-event'], // Menambahkan class CSS
                                                        'backgroundColor' => '#dc3545',
                                                        'borderColor' => 'transparent', // Menghilangkan border
                                                    ];
                                                }
                                            @endphp

                                            <div id="calendar-{{ $m->id }}"
                                                data-events-json="{{ json_encode($events) }}"></div>

                                        </div>
                                        <div class="modal-footer bg-light border-top p-3 d-flex justify-content-between">
                                            <div class="d-flex align-items-center gap-2 small fw-medium">
                                                <span class="d-inline-block rounded"
                                                    style="width:14px;height:14px;background-color:#dc3545;"></span> Tanggal
                                                Tidak Tersedia
                                            </div>
                                            <button type="button" class="btn btn-secondary rounded-3 px-4"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- MODAL KLAIM ASURANSI --}}
                            <div class="modal fade" id="klaimModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <form action="{{ route('mitra.klaim.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id_booking" value="{{ optional($m->bookings->first())->id ?? '' }}">
                                            <div class="modal-header border-bottom px-4 py-3 bg-light rounded-top-4">
                                                <div>
                                                    <h5 class="modal-title fw-bold text-dark mb-1"><i class="bi bi-shield-exclamation text-danger me-2"></i> Ajukan Klaim Asuransi</h5>
                                                    <div class="small text-muted">{{ $m->brand->nama_brand ?? "" }} {{ $m->model }} ({{ $m->plat_nomer }})</div>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Deskripsi Kerusakan</label>
                                                    <textarea name="deskripsi_kerusakan" class="form-control" rows="3" required placeholder="Jelaskan kondisi kerusakan..."></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Estimasi Biaya Perbaikan (Rp)</label>
                                                    <input type="number" name="estimasi_biaya" class="form-control" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Foto Bukti</label>
                                                    <input type="file" name="foto_bukti[]" class="form-control" multiple accept="image/*">
                                                    <div class="form-text">Upload foto kerusakan (opsional, maks 5MB per file)</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light border-top p-3">
                                                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-3 fw-bold"><i class="bi bi-send me-1"></i> Ajukan Klaim</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-car-front display-1 text-muted opacity-25 d-block mb-3"></i>
                                    <h6 class="fw-bold text-dark">Anda belum mendaftarkan mobil</h6>
                                    <p class="text-muted small mb-0">Hubungi admin untuk mendaftarkan armada Anda ke dalam
                                        sistem Roda Kita.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- CDN Library FullCalendar --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/id.global.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const semuaModalJadwal = document.querySelectorAll('.modal');

            semuaModalJadwal.forEach(modal => {
                modal.addEventListener('shown.bs.modal', function() {
                    const idMobil = this.id.replace('jadwalModal', '');
                    const wadahKalender = document.getElementById('calendar-' + idMobil);

                    if (wadahKalender && !wadahKalender.classList.contains('fc')) {
                        const dataEvents = JSON.parse(wadahKalender.getAttribute(
                            'data-events-json'));

                        const kalenderObj = new FullCalendar.Calendar(wadahKalender, {
                            locale: 'id',
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth'
                            },
                            // Menggunakan dayCellDidMount untuk memanipulasi kotak tanggal langsung
                            dayCellDidMount: function(arg) {
                                const dateStr = arg.date.toISOString().split('T')[
                                0]; // Format YYYY-MM-DD
                                const bookedDates = dataEvents.map(e => ({
                                    start: e.start.split('T')[0],
                                    end: e.end.split('T')[0]
                                }));

                                // Cek apakah tanggal ini masuk dalam rentang booking
                                bookedDates.forEach(range => {
                                    if (dateStr >= range.start && dateStr <=
                                        range.end) {
                                        arg.el.classList.add('booked-cell');
                                    }
                                });
                            },
                            eventDisplay: 'none', // Sembunyikan event default agar tidak menumpuk
                            dateClick: function(info) {
                                // Cek jika diklik di sel yang kelasnya 'booked-cell'
                                if (info.dayEl.classList.contains('booked-cell')) {
                                    alert("Tanggal ini sudah dibooking!");
                                }
                            }
                        });
                        kalenderObj.render();

                        kalenderObj.render();
                    }
                });
            });
        });
    </script>
@endpush

