@extends('layouts.mitra')

@section('title', 'Detail Klaim - Roda Kita')
@section('page_title', 'Detail Klaim')
@section('breadcrumb', 'Mitra / Klaim Asuransi / Detail')

@section('content')

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-6">
                <h5 class="fw-bold mb-1">{{ \->booking->mobil->brand->nama_brand ?? '' }} {{ \->booking->mobil->model ?? '' }}</h5>
                <p class="text-muted small">Booking #{{ \->id_booking }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                @if(\->status == 'diajukan')
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill fs-6">Diajukan</span>
                @elseif(\->status == 'disetujui')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fs-6">Disetujui</span>
                @elseif(\->status == 'ditolak')
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill fs-6">Ditolak</span>
                @endif
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <strong>Deskripsi Kerusakan:</strong>
            <p class="mb-0">{{ \->deskripsi_kerusakan }}</p>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Estimasi Biaya:</strong>
                <p class="fw-bold fs-5 text-dark">Rp {{ number_format(\->estimasi_biaya, 0, ',', '.') }}</p>
            </div>
            @if(\->biaya_disetujui)
                <div class="col-md-4">
                    <strong>Biaya Disetujui:</strong>
                    <p class="fw-bold fs-5 text-success">Rp {{ number_format(\->biaya_disetujui, 0, ',', '.') }}</p>
                </div>
            @endif
            <div class="col-md-4">
                <strong>Tanggal Diajukan:</strong>
                <p>{{ \->submitted_at ? \Carbon\Carbon::parse(\->submitted_at)->format('d M Y H:i') : '-' }}</p>
            </div>
        </div>
        @if(\->catatan_klaim)
            <div class="p-3 bg-light rounded-3">
                <strong>Catatan:</strong>
                <p class="mb-0">{{ \->catatan_klaim }}</p>
            </div>
        @endif
        @if(\->foto_bukti && count(\->foto_bukti) > 0)
            <hr>
            <strong>Foto Bukti:</strong>
            <div class="row mt-2 g-2">
                @foreach(\->foto_bukti as \)
                    <div class="col-md-3">
                        <a href="{{ asset('storage/' . \) }}" target="_blank">
                            <img src="{{ asset('storage/' . \) }}" class="img-fluid rounded-3 border" style="height:120px;object-fit:cover;">
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<a href="{{ route('mitra.klaim.index') }}" class="btn btn-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>

@endsection
