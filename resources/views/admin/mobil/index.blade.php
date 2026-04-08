@extends('layouts.admin')

@section('title', 'Manajemen Mobil - Admin')
@section('page_title', 'Manajemen Mobil')
@section('breadcrumb', 'Admin / Manajemen Mobil')

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;">
                        <i class="bi bi-car-front-fill fs-5 text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $mobils->count() }}</div>
                        <div class="text-muted small mt-1">Total Armada</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;">
                        <i class="bi bi-eye-fill fs-5 text-success"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $mobils->where('status_katalog', 1)->count() }}</div>
                        <div class="text-muted small mt-1">Tampil di Katalog</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-secondary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;">
                        <i class="bi bi-eye-slash-fill fs-5 text-secondary"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $mobils->where('status_katalog', 0)->count() }}</div>
                        <div class="text-muted small mt-1">Disembunyikan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-warning bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px;">
                        <i class="bi bi-people-fill fs-5 text-warning"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">{{ $pemiliks->count() }}</div>
                        <div class="text-muted small mt-1">Total Perental</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        {{-- Card Header --}}
        <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-auto">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-collection-fill text-primary me-2"></i>Katalog Armada
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Kelola seluruh unit mobil yang dititipkan mitra</p>
                </div>
                <div class="col-12 col-md ms-md-auto">
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        {{-- Search --}}
                        <div class="input-group input-group-sm rounded-3 border" style="width:220px;">
                            <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control border-0 shadow-none" placeholder="Cari nama, plat...">
                        </div>
                        {{-- Filter Status --}}
                        <select id="filterStatus" class="form-select form-select-sm rounded-3 border" style="width:160px;">
                            <option value="">Semua Status</option>
                            <option value="tampil">Tampil</option>
                            <option value="sembunyikan">Disembunyikan</option>
                        </select>
                        {{-- Tambah --}}
                        <a href="{{ route('admin.mobil.kategori.create') }}" class="btn btn-primary btn-sm fw-semibold rounded-3 px-3">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                        </a>
                        <a href="{{ route('admin.mobil.brand.create') }}" class="btn btn-primary btn-sm fw-semibold rounded-3 px-3">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Brand
                        </a>
                        <a href="{{ route('admin.mobil.create') }}" class="btn btn-primary btn-sm fw-semibold rounded-3 px-3">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Mobil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelMobil">
                    <thead>
                        <tr class="bg-light text-muted small text-uppercase">
                            <th class="ps-4 py-3 fw-semibold border-bottom-0" style="width:30%;">Mobil</th>
                            <th class="py-3 fw-semibold border-bottom-0">Plat Nomor</th>
                            <th class="py-3 fw-semibold border-bottom-0">Pemilik</th>
                            <th class="py-3 fw-semibold border-bottom-0">Harga/Hari</th>
                            <th class="py-3 fw-semibold border-bottom-0">Katalog</th>
                            <th class="py-3 pe-4 fw-semibold border-bottom-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top">
                        @forelse($mobils as $m)
                        <tr class="mobil-row"
                            data-nama="{{ strtolower(($m->brand->nama_brand ?? '') . ' ' . $m->model) }}"
                            data-plat="{{ strtolower($m->plat_nomer) }}"
                            data-status="{{ $m->status_katalog == 1 ? 'tampil' : 'sembunyikan' }}">

                            {{-- Mobil --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 overflow-hidden shadow-sm flex-shrink-0 bg-light d-flex align-items-center justify-content-center" style="width:56px;height:44px;">
                                        @if($m->gambar)
                                            <img src="{{ asset('storage/' . $m->gambar) }}" alt="Mobil" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <i class="bi bi-car-front text-muted fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark lh-sm">{{ $m->brand->nama_brand ?? '-' }} {{ $m->model }}</div>
                                        <div class="text-muted small mt-1">
                                            <span class="badge bg-light text-secondary border fw-normal">{{ $m->tahun }}</span>
                                            <span class="badge bg-light text-secondary border fw-normal ms-1">{{ $m->transmisi }}</span>
                                            <span class="badge bg-light text-secondary border fw-normal ms-1"><i class="bi bi-people me-1"></i>{{ $m->kapasitas_penumpang }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Plat --}}
                            <td>
                                <span class="badge bg-dark bg-opacity-10 text-dark font-monospace px-2 py-1 border fw-semibold fs-6">{{ $m->plat_nomer }}</span>
                            </td>

                            {{-- Pemilik --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-primary small" style="width:30px;height:30px;">
                                        {{ strtoupper(substr($m->pemilik->nama ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="text-dark small fw-medium">{{ $m->pemilik->nama ?? 'Sistem' }}</span>
                                </div>
                            </td>

                            {{-- Harga --}}
                            <td>
                                <span class="fw-bold text-success">Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}</span>
                                <div class="text-muted small">per hari</div>
                            </td>

                            {{-- Status Katalog --}}
                            <td>
                                @if($m->status_katalog == 1)
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 fw-semibold">
                                        <i class="bi bi-eye me-1"></i> Tampil
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2 fw-semibold">
                                        <i class="bi bi-eye-slash me-1"></i> Disembunyikan
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.mobil.edit', $m->id) }}" class="btn btn-sm btn-light border fw-medium text-primary rounded-3" title="Edit">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        <span class="d-none d-xl-inline">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.mobil.destroy', $m->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus mobil {{ $m->brand->nama_brand ?? '' }} {{ $m->model }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border fw-medium text-danger rounded-3" title="Hapus">
                                            <i class="bi bi-trash me-1"></i>
                                            <span class="d-none d-xl-inline">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-car-front fs-1 d-block mb-3 opacity-25"></i>
                                    <p class="fw-semibold mb-1">Belum ada data armada mobil</p>
                                    <p class="small mb-3">Tambahkan mobil pertama untuk memulai</p>
                                    <button class="btn btn-primary btn-sm rounded-3 px-4" data-bs-toggle="modal" data-bs-target="#modalTambahMobil">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Mobil
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- No search result --}}
            <div id="noResult" class="text-center py-5 d-none">
                <i class="bi bi-search fs-1 text-muted opacity-25 d-block mb-3"></i>
                <p class="fw-semibold text-muted mb-1">Tidak ada hasil</p>
                <p class="small text-muted">Coba kata kunci atau filter yang berbeda</p>
            </div>
        </div>

        {{-- Card Footer: Pagination / Info --}}
        @if($mobils->count() > 0)
        <div class="card-footer bg-white border-top rounded-bottom-4 px-4 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span class="text-muted small">
                Menampilkan <span class="fw-semibold text-dark" id="visibleCount">{{ $mobils->count() }}</span>
                dari <span class="fw-semibold text-dark">{{ $mobils->count() }}</span> unit
            </span>
            <div class="d-flex gap-2">
                <span class="badge bg-success bg-opacity-10 text-success border px-3 py-2">
                    <i class="bi bi-eye me-1"></i> {{ $mobils->where('status_katalog', 1)->count() }} Tampil
                </span>
                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-2">
                    <i class="bi bi-eye-slash me-1"></i> {{ $mobils->where('status_katalog', 0)->count() }} Sembunyikan
                </span>
            </div>
        </div>
        @endif

    </div>
@endsection

