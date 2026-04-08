@extends('layouts.admin')

@section('title', 'Edit Pengguna - Admin')

@section('content')
<div class="col px-0 pt-0 pb-5 min-vh-100">

    {{-- Sticky Header --}}
    <div class="sticky-top py-3 mb-4" style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.user.index') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Edit Data Pengguna</h4>
                <p class="text-muted mb-0 small">Perbarui profil {{ $user->id_role == 3 ? 'Mitra' : 'Pelanggan' }}</p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 border-0 shadow-sm">
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm rounded-4">
                <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body px-4 py-4 bg-light rounded-4">
                        
                        {{-- TIPE PENGGUNA (DILOCK/DISABLED) --}}
                        <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border">
                            <label class="form-label fw-bold text-muted mb-2">Tipe Pengguna</label>
                            <div>
                                @if($user->id_role == 3)
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fs-6 fw-semibold">
                                        <i class="bi bi-person-badge me-1"></i> Mitra Perental
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fs-6 fw-semibold">
                                        <i class="bi bi-people-fill me-1"></i> Pelanggan Biasa
                                    </span>
                                @endif
                                <div class="form-text small mt-2"><i class="bi bi-info-circle me-1"></i>Tipe pengguna tidak dapat diubah setelah akun dibuat.</div>
                            </div>
                        </div>

                        {{-- FORM DATA AKUN --}}
                        <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border">
                            <p class="small fw-bold text-uppercase text-muted mb-3"><i class="bi bi-person-badge me-1"></i> Data Profil & Login</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control rounded-3" value="{{ old('nama', $user->nama) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Email Login <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control rounded-3" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Ganti Password</label>
                                    <input type="text" name="password" class="form-control rounded-3" placeholder="Isi hanya jika ingin diganti (Min. 6 karakter)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Nomor Telepon/WA <span class="text-danger">*</span></label>
                                    <input type="text" name="no_telepon" class="form-control rounded-3" value="{{ old('no_telepon', $user->no_telepon) }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-dark">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea name="alamat" class="form-control rounded-3" rows="2" required>{{ old('alamat', $user->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- FORM KHUSUS MITRA (Hanya muncul jika user adalah Mitra) --}}
                        @if($user->id_role == 3)
                        <div class="bg-white p-4 rounded-4 shadow-sm border border-primary border-opacity-25">
                            <p class="small fw-bold text-uppercase text-primary mb-3"><i class="bi bi-wallet2 me-1"></i> Data Pencairan Mitra</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-dark">Nomor KTP (NIK) <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_ktp" class="form-control rounded-3 font-monospace" value="{{ old('nomor_ktp', $user->pemilikMobil->nomor_ktp ?? '') }}" required maxlength="16">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-dark">Nama Bank <span class="text-danger">*</span></label>
                                    <select name="nama_bank" class="form-select rounded-3" required>
                                        <option value="" disabled>Pilih Bank...</option>
                                        @php $currBank = old('nama_bank', $user->pemilikMobil->nama_bank ?? ''); @endphp
                                        <option value="BCA" {{ $currBank == 'BCA' ? 'selected' : '' }}>BCA</option>
                                        <option value="Mandiri" {{ $currBank == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                        <option value="BNI" {{ $currBank == 'BNI' ? 'selected' : '' }}>BNI</option>
                                        <option value="BRI" {{ $currBank == 'BRI' ? 'selected' : '' }}>BRI</option>
                                        <option value="BSI" {{ $currBank == 'BSI' ? 'selected' : '' }}>BSI</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-dark">Nomor Rekening <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_rekening" class="form-control rounded-3 font-monospace" value="{{ old('nomor_rekening', $user->pemilikMobil->nomor_rekening ?? '') }}" required>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="card-footer bg-white border-top px-4 py-3 text-end rounded-bottom-4">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-light border fw-semibold rounded-3 px-4 me-2">Batal</a>
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