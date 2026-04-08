@extends('layouts.pelanggan')

@section('title', 'Katalog Mobil - Roda Kita')

@section('content')

    {{-- HERO SECTION --}}
    <div class="bg-primary bg-opacity-10 py-5 border-bottom">
        <div class="container py-4 text-center">
            <h1 class="fw-bold text-dark mb-3">Temukan Mobil Impian untuk Perjalananmu</h1>
            <p class="text-muted mb-4 fs-5">Sewa mobil mudah, aman, dan terpercaya langsung dari mitra pilihan Roda Kita.</p>
            
            {{-- SEARCH & FILTER BAR --}}
            <div class="bg-white p-2 rounded-pill shadow-sm mx-auto" style="max-width: 800px;">
                <div class="row g-0 align-items-center">
                    <div class="col-md-5 px-3 border-end">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-muted px-0"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Cari nama mobil atau merk...">
                        </div>
                    </div>
                    <div class="col-md-4 px-3 border-end d-none d-md-block">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-muted px-0"><i class="bi bi-grid-fill"></i></span>
                            <select id="filterKategori" class="form-select border-0 shadow-none text-muted">
                                <option value="">Semua Kategori</option>
                                @foreach($mobils->pluck('kategori.nama_kategori')->unique() as $kat)
                                    <option value="{{ strtolower($kat) }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-grid px-1 mt-2 mt-md-0">
                        <button class="btn btn-primary rounded-pill fw-bold" onclick="applyFilter()">Terapkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CATALOG GRID --}}
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h4 class="fw-bold text-dark mb-0">Rekomendasi Armada</h4>
            <span class="text-muted small"><span id="countMobil">{{ $mobils->count() }}</span> mobil tersedia</span>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4" id="katalogContainer">
            
            @forelse($mobils as $m)
            <div class="col mobil-item" 
                 data-nama="{{ strtolower(($m->brand->nama_brand ?? '') . ' ' . $m->model) }}"
                 data-kategori="{{ strtolower($m->kategori->nama_kategori ?? '') }}">
                 
                {{-- Tambahkan position-relative di sini --}}
                <div class="card h-100 border-0 shadow-sm rounded-4 car-card overflow-hidden position-relative">
                    
                    {{-- INI KUNCI UTAMANYA: Stretched Link --}}
                    <a href="{{ route('pelanggan.mobil.detail_mobil', $m->id) }}" class="stretched-link"></a>

                    {{-- Foto Mobil --}}
                    <div class="position-relative bg-light" style="height: 200px;">
                        @if($m->gambar)
                            <img src="{{ asset('storage/' . $m->gambar) }}" class="w-100 h-100 object-fit-cover" alt="{{ $m->model }}">
                        @else
                            <div class="w-100 h-100 d-flex justify-content-center align-items-center text-muted">
                                <i class="bi bi-car-front fs-1 opacity-50"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-white text-dark shadow-sm px-2 py-1 border fw-semibold">
                                <i class="bi bi-gear-fill text-primary me-1"></i> {{ $m->transmisi }}
                            </span>
                        </div>
                    </div>

                    {{-- Informasi Mobil --}}
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-muted small fw-semibold text-uppercase letter-spacing-1">{{ $m->brand->nama_brand ?? 'Merk' }}</span>
                                <h5 class="fw-bold text-dark mb-0 mt-1 text-truncate" style="max-width: 180px;">{{ $m->model }}</h5>
                            </div>
                            <span class="badge bg-light text-secondary border">{{ $m->tahun }}</span>
                        </div>

                        <div class="d-flex gap-3 text-muted small mt-2 mb-4 fw-medium">
                            <span title="Kategori"><i class="bi bi-grid-fill me-1 text-secondary"></i> {{ $m->kategori->nama_kategori ?? '-' }}</span>
                            <span title="Kapasitas"><i class="bi bi-people-fill me-1 text-secondary"></i> {{ $m->kapasitas_penumpang }} Kursi</span>
                        </div>

                        <div class="mt-auto border-top pt-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small" style="font-size: 11px;">Mulai dari</div>
                                <div class="fw-bold text-success fs-5 lh-1">Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}<span class="text-muted fs-6 fw-normal">/hr</span></div>
                            </div>
                            {{-- Ubah <a> menjadi <span> agar tidak konflik HTML bersarang, efek klik dihandle oleh stretched-link di atas --}}
                            <span class="btn btn-primary bg-opacity-10 text-primary border-0 fw-bold rounded-3 px-3 py-2">
                                Detail
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 w-100 py-5 text-center">
                <i class="bi bi-car-front-fill display-1 text-muted opacity-25 d-block mb-3"></i>
                <h5 class="fw-bold text-dark">Belum Ada Mobil Tersedia</h5>
                <p class="text-muted">Saat ini belum ada armada mobil yang siap disewa. Silakan kembali lagi nanti.</p>
            </div>
            @endforelse

        </div>

        {{-- Pesan Jika Filter Tidak Ditemukan --}}
        <div id="noResult" class="text-center py-5 d-none">
            <i class="bi bi-search display-1 text-muted opacity-25 d-block mb-3"></i>
            <h5 class="fw-bold text-dark">Mobil Tidak Ditemukan</h5>
            <p class="text-muted">Coba gunakan kata kunci pencarian atau filter kategori yang berbeda.</p>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    function applyFilter() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterKategori = document.getElementById('filterKategori') ? document.getElementById('filterKategori').value.toLowerCase() : '';
        
        const items = document.querySelectorAll('.mobil-item');
        let count = 0;

        items.forEach(item => {
            const nama = item.getAttribute('data-nama');
            const kategori = item.getAttribute('data-kategori');

            const matchSearch = nama.includes(searchInput);
            const matchKategori = filterKategori === '' || kategori === filterKategori;

            if (matchSearch && matchKategori) {
                item.style.display = 'block';
                count++;
            } else {
                item.style.display = 'none';
            }
        });

        // Update jumlah text
        document.getElementById('countMobil').innerText = count;

        // Tampilkan peringatan jika kosong
        if (count === 0 && items.length > 0) {
            document.getElementById('noResult').classList.remove('d-none');
        } else {
            document.getElementById('noResult').classList.add('d-none');
        }
    }

    // Auto trigger saat mengetik di input search
    document.getElementById('searchInput').addEventListener('keyup', applyFilter);
    if(document.getElementById('filterKategori')) {
        document.getElementById('filterKategori').addEventListener('change', applyFilter);
    }
</script>
@endpush