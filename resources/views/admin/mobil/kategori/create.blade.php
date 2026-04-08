@extends('layouts.admin')

@section('title', 'Manajemen Kategori Mobil - Admin')
@section('page_title', 'Manajemen Kategori Mobil')
@section('breadcrumb', 'tambah, edit, atau hapus kategori mobil')

@section('content')
    <div class="col px-0 pt-0 pb-5 min-vh-100">

        {{-- Sticky Header --}}
        <div class="py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.mobil.index') }}"
                    class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- KOLOM KIRI: FORM TAMBAH --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top: 100px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold d-flex align-items-center text-dark">
                            <i class="bi bi-grid-fill me-2 text-primary"></i>Tambah Kategori Baru
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.mobil.kategori.store') }}">
                        @csrf
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">Nama Kategori <span
                                            class="text-danger">*</span></label>
                                    <input name="nama_kategori" type="text" class="form-control rounded-3 py-2"
                                        placeholder="cth. SUV, MPV" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Opsional..."></textarea>
                                </div>
                            </div>
                            <div class="d-grid gap-2" style="margin-top: 15px">
                                <button type="submit" class="btn btn-primary py-2 fw-bold rounded-3 shadow-sm border-0">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Kategori
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: TABEL DATA --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark"><i class="bi bi-list-ul me-2 text-primary"></i>Daftar
                            Kategori</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="ps-4">NAMA KATEGORI</th>
                                    <th>DESKRIPSI</th>
                                    <th class="text-center pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse ($kategoris as $item)
                                    <tr>
                                        <td class="text-dark fw-medium ps-4">{{ $item->nama_kategori }}</td>
                                        <td class="text-muted">{{ $item->deskripsi ?? '-' }}</td>
                                        <td class="pe-4">
                                            <div class="d-flex justify-content-center gap-2">

                                                <a href="{{ route('admin.mobil.kategori.edit', $item->id) }}"
                                                    class="btn btn-sm btn-info bg-opacity-10 border-0 text-info px-2 py-1 rounded-2"
                                                    title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.mobil.kategori.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger bg-opacity-10 border-0 text-danger px-2 py-1 rounded-2">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada data kategori
                                            terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
