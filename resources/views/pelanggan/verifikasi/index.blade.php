@extends('layouts.pelanggan')

@section('title', 'Verifikasi Akun - Roda Kita')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-1">Verifikasi Identitas</h4>
                    <p class="text-muted mb-0">Lengkapi dokumen untuk meningkatkan kepercayaan dan mengakses layanan Lepas Kunci.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info rounded-3">{{ session('info') }}</div>
            @endif

            @if($verifikasi)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Status Verifikasi</h5>
                        @if($verifikasi->status == 'unverified')
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill fs-6">
                                <i class="bi bi-clock me-1"></i> Belum Verifikasi
                            </span>
                        @elseif($verifikasi->status == 'pending')
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill fs-6">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi Admin
                            </span>
                        @elseif($verifikasi->status == 'verified')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fs-6">
                                <i class="bi bi-check-circle me-1"></i> Terverifikasi
                            </span>
                        @elseif($verifikasi->status == 'rejected')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill fs-6">
                                <i class="bi bi-x-circle me-1"></i> Ditolak
                            </span>
                            @if($verifikasi->catatan_verifikasi)
                                <div class="mt-3 p-3 bg-danger bg-opacity-10 rounded-3">
                                    <strong>Catatan:</strong> {{ $verifikasi->catatan_verifikasi }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('pelanggan.verifikasi.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">1. Foto KTP</label>
                            <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
                            <div class="form-text">Format JPG/PNG, maks 5MB</div>
                            @error('foto_ktp') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">2. Foto SIM</label>
                            <input type="file" name="foto_sim" class="form-control" accept="image/*" required>
                            <div class="form-text">Format JPG/PNG, maks 5MB</div>
                            @error('foto_sim') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">3. Foto Selfie</label>
                            <input type="file" name="foto_selfie" class="form-control" accept="image/*" required>
                            <div class="form-text">Foto wajah jelas untuk pencocokan dengan KTP. Format JPG/PNG, maks 5MB</div>
                            @error('foto_selfie') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary fw-bold rounded-3 px-5 py-2">
                            <i class="bi bi-upload me-2"></i> Unggah & Verifikasi
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
