@extends('layouts.pelanggan')

@section('title', 'Katalog Mobil - Roda Kita')

@section('content')

{{-- ══════════ HERO ══════════ --}}
<div style="background: linear-gradient(135deg, #0f2744 0%, #1a4a8a 100%);" class="py-5">
    <div class="container py-4 text-center">
        <span class="badge rounded-pill px-3 py-2 mb-3 fw-semibold"
              style="background:rgba(249,115,22,.2);color:#fb923c;border:1px solid rgba(249,115,22,.35);font-size:.75rem;letter-spacing:.06em;">
            <i class="bi bi-shield-check-fill me-1"></i> PLATFORM RENTAL TERPERCAYA
        </span>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,2.8rem);line-height:1.2;">
            Temukan Mobil yang Pas untuk<br class="d-none d-md-block"/>
            <span style="color:#f97316;">Setiap Perjalananmu</span>
        </h1>
        <p class="mb-0 mx-auto" style="color:rgba(255,255,255,.65);max-width:500px;font-size:.97rem;line-height:1.7;">
            Pilih dari ratusan armada terawat milik mitra terverifikasi Roda Kita.
            Booking online, bayar digital — berangkat tanpa ribet.
        </p>
    </div>
</div>

{{-- ══════════ SEARCH FLOAT ══════════ --}}
<div class="container" style="margin-top:-40px;position:relative;z-index:10;">
    <div class="bg-white rounded-4 shadow p-4">
        <div class="row g-3 align-items-end">

            <div class="col-md-5">
                <label class="form-label text-muted fw-bold" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;">
                    Cari Mobil
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchInput"
                           class="form-control bg-light border-start-0 ps-0 shadow-none"
                           placeholder="Nama mobil, merk…"
                           style="border-radius:0 8px 8px 0;">
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label text-muted fw-bold" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;">
                    Kategori
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-grid-fill"></i>
                    </span>
                    <select id="filterKategori"
                            class="form-select bg-light border-start-0 shadow-none"
                            style="border-radius:0 8px 8px 0;">
                        <option value="">Semua Kategori</option>
                        @foreach($mobils->pluck('kategori.nama_kategori')->unique()->filter() as $kat)
                            <option value="{{ strtolower($kat) }}">{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 d-grid">
                <button class="btn fw-bold py-2 rounded-3"
                        onclick="applyFilter()"
                        style="background:#f97316;color:#fff;border:none;box-shadow:0 6px 18px rgba(249,115,22,.35);">
                    <i class="bi bi-search me-1"></i> Cari Sekarang
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ══════════ CATALOG SECTION ══════════ --}}
<div class="container py-5">

    {{-- Toolbar --}}
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
        <div>
            <p class="mb-1 fw-bold text-uppercase" style="font-size:.72rem;color:#f97316;letter-spacing:.1em;">
                Armada Tersedia
            </p>
            <h4 class="fw-bold mb-0" style="color:#0f2744;">Pilihan Mobil Kami</h4>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted" style="font-size:.87rem;">
                <strong id="countMobil" class="text-dark">{{ $mobils->count() }}</strong> mobil ditemukan
            </span>
            <select class="form-select form-select-sm border shadow-none" style="width:auto;" onchange="applySort(this.value)">
                <option value="">Urutkan: Default</option>
                <option value="harga-asc">Harga Terendah</option>
                <option value="harga-desc">Harga Tertinggi</option>
            </select>
        </div>
    </div>

    {{-- ── Grid ── --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4" id="katalogContainer">

        @forelse($mobils as $m)
        <div class="col mobil-item"
             data-nama="{{ strtolower(($m->brand->nama_brand ?? '') . ' ' . $m->model) }}"
             data-kategori="{{ strtolower($m->kategori->nama_kategori ?? '') }}"
             data-harga="{{ $m->harga_sewa }}">

            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative car-card">

                {{-- Stretched link --}}
                <a href="{{ route('pelanggan.mobil.detail_mobil', $m->id) }}"
                   class="stretched-link"
                   aria-label="{{ $m->brand->nama_brand ?? '' }} {{ $m->model }}"></a>

                {{-- ── Gambar ── --}}
                <div class="position-relative bg-light" style="height:200px;overflow:hidden;">
                    @if($m->gambar)
                        <img src="{{ asset('storage/' . $m->gambar) }}"
                             class="w-100 h-100 car-img"
                             style="object-fit:cover;transition:transform .4s ease;"
                             alt="{{ $m->model }}"
                             loading="lazy">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-light">
                            <i class="bi bi-car-front" style="font-size:3.5rem;opacity:.2;"></i>
                        </div>
                    @endif

                    {{-- Badge kategori --}}
                    @if($m->kategori)
                    <span class="position-absolute top-0 start-0 m-2 badge fw-bold px-2 py-1"
                          style="background:#0f2744;font-size:.65rem;letter-spacing:.05em;border-radius:50px;">
                        {{ strtoupper($m->kategori->nama_kategori) }}
                    </span>
                    @endif

                    {{-- Badge transmisi --}}
                    <span class="position-absolute top-0 end-0 m-2 badge bg-white text-dark border shadow-sm px-2 py-1 fw-semibold"
                          style="font-size:.72rem;border-radius:50px;">
                        <i class="bi bi-gear-fill text-primary me-1"></i>{{ $m->transmisi }}
                    </span>
                </div>

                {{-- ── Body ── --}}
                <div class="card-body p-4 d-flex flex-column">

                    <p class="text-uppercase text-muted mb-1 fw-bold"
                       style="font-size:.67rem;letter-spacing:.1em;">
                        {{ $m->brand->nama_brand ?? 'Merk' }}
                    </p>

                    <h5 class="fw-bold mb-1 text-truncate" style="color:#0f2744;" title="{{ $m->model }}">
                        {{ $m->model }}
                    </h5>

                    {{-- COMPONENT BARU: AKUMULASI BINTANG RATING MOBIL --}}
                    <div class="d-flex align-items-center mb-3" style="font-size: 0.82rem;">
                        @if($m->ulasans_avg_rating)
                            <div class="text-warning d-flex align-items-center me-2">
                                <i class="bi bi-star-fill me-1"></i>
                                <span class="fw-bold text-dark">{{ number_format($m->ulasans_avg_rating, 1) }}</span>
                            </div>
                            <span class="text-muted">({{ $m->ulasans_count }} Ulasan)</span>
                        @else
                            <span class="text-muted opacity-75 fst-italic"><i class="bi bi-star me-1"></i>Belum ada ulasan</span>
                        @endif
                    </div>

                    {{-- Spesifikasi --}}
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-light text-secondary border fw-medium px-2 py-1" style="font-size:.75rem;">
                            <i class="bi bi-people-fill me-1"></i>{{ $m->kapasitas_penumpang }} Kursi
                        </span>
                        <span class="badge bg-light text-secondary border fw-medium px-2 py-1" style="font-size:.75rem;">
                            <i class="bi bi-calendar3 me-1"></i>{{ $m->tahun }}
                        </span>
                        @if($m->warna ?? false)
                        <span class="badge bg-light text-secondary border fw-medium px-2 py-1" style="font-size:.75rem;">
                            <i class="bi bi-palette2 me-1"></i>{{ $m->warna }}
                        </span>
                        @endif
                    </div>

                    {{-- Harga + CTA --}}
                    <div class="mt-auto border-top pt-3 d-flex justify-content-between align-items-end">
                        <div>
                            <p class="text-muted mb-1" style="font-size:.68rem;">Mulai dari</p>
                            <p class="fw-bold mb-0 lh-1" style="font-size:1.2rem;color:#0f2744;">
                                Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}
                                <span class="fw-normal text-muted" style="font-size:.75rem;">/ hari</span>
                            </p>
                        </div>
                        <span class="btn btn-sm fw-bold rounded-3 px-3 py-2 btn-card-action"
                              style="background:#0f2744;color:#fff;font-size:.82rem;pointer-events:none;position:relative;z-index:6;transition:background .2s;">
                            Lihat <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>

                </div>{{-- /card-body --}}
            </div>{{-- /card --}}

        </div>{{-- /col --}}

        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-car-front-fill display-1 text-muted d-block mb-3" style="opacity:.2;"></i>
                <h5 class="fw-bold" style="color:#0f2744;">Belum Ada Mobil Tersedia</h5>
                <p class="text-muted">Saat ini belum ada armada yang siap disewa. Silakan cek kembali nanti.</p>
            </div>
        </div>
        @endforelse

    </div>{{-- /row --}}

    {{-- No result --}}
    <div id="noResult" class="d-none text-center py-5">
        <i class="bi bi-search display-1 text-muted d-block mb-3" style="opacity:.2;"></i>
        <h5 class="fw-bold" style="color:#0f2744;">Mobil Tidak Ditemukan</h5>
        <p class="text-muted">Coba gunakan kata kunci atau kategori yang berbeda.</p>
    </div>

</div>{{-- /container --}}

{{-- Hover style --}}
<style>
    .car-card { transition: transform .25s ease, box-shadow .25s ease; }
    .car-card:hover { transform: translateY(-6px); box-shadow: 0 20px 48px rgba(15,39,68,.13) !important; }
    .car-card:hover .car-img { transform: scale(1.05); }
    .car-card:hover .btn-card-action { background: #f97316 !important; }
</style>

@endsection

@push('scripts')
<script>
    function applyFilter() {
        const q   = document.getElementById('searchInput').value.toLowerCase().trim();
        const kat = (document.getElementById('filterKategori')?.value ?? '').toLowerCase();
        const items = document.querySelectorAll('.mobil-item');
        let count = 0;

        items.forEach(el => {
            const match = el.dataset.nama.includes(q) &&
                          (kat === '' || el.dataset.kategori === kat);
            el.style.display = match ? '' : 'none';
            if (match) count++;
        });

        document.getElementById('countMobil').textContent = count;
        document.getElementById('noResult').classList.toggle('d-none', count > 0 || items.length === 0);
    }

    function applySort(val) {
        const container = document.getElementById('katalogContainer');
        [...container.querySelectorAll('.mobil-item')]
            .sort((a, b) => {
                const ha = +a.dataset.harga, hb = +b.dataset.harga;
                return val === 'harga-asc' ? ha - hb : val === 'harga-desc' ? hb - ha : 0;
            })
            .forEach(el => container.appendChild(el));
        applyFilter();
    }

    document.getElementById('searchInput').addEventListener('keyup', applyFilter);
    document.getElementById('filterKategori')?.addEventListener('change', applyFilter);
</script>
@endpush