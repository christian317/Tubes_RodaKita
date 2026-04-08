@extends('layouts.admin')

@section('title', 'Manajemen Kategori Mobil - Admin')
@section('page_title', 'Edit Kategori Mobil')
@section('breadcrumb', 'perbarui data kategori mobil')

@section('content')
<div class="col px-0 pt-0 pb-5 min-vh-100">

    {{-- Sticky Header --}}
    <div class="py-3 mb-4" style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.mobil.kategori.create') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="fw-bold d-flex align-items-center text-dark">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Form Edit Kategori
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.mobil.kategori.update', $kategori->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Nama Kategori <span class="text-danger">*</span></label>
                            <input name="nama_kategori" type="text" class="form-control rounded-3 py-2" value="{{ $kategori->nama_kategori }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="form-control rounded-3" rows="4">{{ $kategori->deskripsi }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-light px-4 py-3 text-end rounded-bottom-4 border-top">
                        <a href="{{ route('admin.mobil.kategori.create') }}" class="btn btn-light border fw-semibold rounded-3 px-4 me-2">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4 shadow-sm">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection