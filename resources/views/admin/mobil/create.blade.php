@extends('layouts.admin')

@section('title', 'Manajemen Mobil - Admin')
@section('page_title', 'Tambah Mobil')
@section('breadcrumb', 'Tambah data mobil')

@section('content')
    <div class="py-3 mb-4" style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.mobil.index') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-car-front-fill text-primary me-2"></i>Formulir Tambah Mobil
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Lengkapi data unit mobil yang dititipkan mitra</p>
                </div>
            </div>

            <form action="{{ route('admin.mobil.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body px-4 py-4 bg-light">
                    
                    {{-- Section: Info Mobil --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border">
                        <p class="small fw-bold text-uppercase text-muted mb-3 letter-spacing-1">
                            <i class="bi bi-info-circle me-1"></i> Informasi Mobil
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Brand (Merk) <span class="text-danger">*</span></label>
                                <select name="id_brand" class="form-select rounded-3" required>
                                    <option value="" disabled selected>Pilih Merk...</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama_brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Model / Nama Mobil <span class="text-danger">*</span></label>
                                <input type="text" name="model" class="form-control rounded-3" placeholder="cth. Avanza Veloz" required/>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-dark">Kategori <span class="text-danger">*</span></label>
                                <select name="id_kategori" class="form-select rounded-3" required>
                                    <option value="" disabled selected>Pilih Kategori...</option>
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-dark">Transmisi <span class="text-danger">*</span></label>
                                <select name="transmisi" class="form-select rounded-3" required>
                                    <option value="Otomatis">Otomatis</option>
                                    <option value="Manual">Manual</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-dark">Penumpang <span class="text-danger">*</span></label>
                                <input type="number" name="kapasitas_penumpang" class="form-control rounded-3 text-center" placeholder="7" min="2" max="15" required/>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-dark">Tahun <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" class="form-control rounded-3 text-center" placeholder="2022" required/>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Kepemilikan & Harga --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border">
                        <p class="small fw-bold text-uppercase text-muted mb-3 letter-spacing-1">
                            <i class="bi bi-person-badge me-1"></i> Kepemilikan & Harga
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Plat Nomor <span class="text-danger">*</span></label>
                                <input type="text" name="plat_nomer" class="form-control rounded-3 font-monospace text-uppercase" placeholder="cth. B 1234 RK" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Pemilik (Mitra) <span class="text-danger">*</span></label>
                                <select name="id_pemilik_mobil" class="form-select rounded-3" required>
                                    <option value="" disabled selected>Pilih Mitra...</option>
                                    @foreach($pemiliks as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Harga Sewa per Hari <span class="text-danger">*</span></label>
                                <div class="input-group rounded-3">
                                    <span class="input-group-text bg-light border-end-0 text-muted small">Rp</span>
                                    <input type="number" name="harga_sewa" class="form-control border-start-0 rounded-end-3" placeholder="350000" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Media --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm border">
                        <p class="small fw-bold text-uppercase text-muted mb-3 letter-spacing-1">
                            <i class="bi bi-images me-1"></i> Media & Tampilan
                        </p>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">Foto Utama Mobil <span class="text-danger">*</span></label>
                                <input type="file" name="gambar" class="form-control rounded-3" accept="image/*" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Fitur unggulan, kondisi khusus..."></textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="form-check form-switch fs-6 d-flex align-items-center gap-3">
                                    <input class="form-check-input mt-0" style="width:2.5em;height:1.3em;" type="checkbox" name="status_katalog" id="checkTampil" checked/>
                                    <div>
                                        <label class="form-check-label fw-semibold small text-dark mb-0" for="checkTampil" style="cursor:pointer;">Tampilkan di katalog pelanggan</label>
                                        <div class="text-muted small" style="font-size:12px;">Mobil akan langsung muncul di halaman sewa</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-white border-top px-4 py-3 text-end rounded-bottom-4">
                    <button type="submit" class="btn btn-primary fw-semibold rounded-3 px-4">
                        <i class="bi bi-save me-1"></i> Simpan Data Mobil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection