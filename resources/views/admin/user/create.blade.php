@extends('layouts.admin')

@section('title', 'Tambah User - Admin')
@section('page_title', 'Tambah User')
@section('breadcrumb', 'tambah, edit, atau hapus data user')

@section('content')
<div class="col px-0 pt-0 pb-5 min-vh-100">

    <div class="py-3 mb-4" style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.user.index') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Tambah Pengguna</h4>
                <p class="text-muted mb-0 small">Buat akun untuk Mitra atau Pelanggan</p>
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
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    <div class="card-body px-4 py-4 bg-light rounded-4">
                        
                        {{-- PEMILIHAN TIPE PENGGUNA --}}
                        <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border border-primary border-opacity-25">
                            <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Pilih Tipe Pengguna</label>
                            <div class="d-flex gap-4">
                                <div class="form-check form-check-inline fs-6">
                                    <input class="form-check-input" type="radio" name="id_role" id="roleMitra" value="3" checked onchange="toggleMitraForm()">
                                    <label class="form-check-label fw-semibold" for="roleMitra" style="cursor:pointer;">Mitra Perental</label>
                                </div>
                                <div class="form-check form-check-inline fs-6">
                                    <input class="form-check-input" type="radio" name="id_role" id="rolePelanggan" value="2" onchange="toggleMitraForm()">
                                    <label class="form-check-label fw-semibold" for="rolePelanggan" style="cursor:pointer;">Pelanggan Biasa</label>
                                </div>
                            </div>
                        </div>

                        {{-- FORM DATA AKUN --}}
                        <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border">
                            <p class="small fw-bold text-uppercase text-muted mb-3"><i class="bi bi-person-badge me-1"></i> Data Profil & Login</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control rounded-3" value="{{ old('nama') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Email Login <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control rounded-3" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control rounded-3" placeholder="Min. 6 karakter" required minlength="6">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nomor Telepon/WA <span class="text-danger">*</span></label>
                                    <input type="text" name="no_telepon" class="form-control rounded-3" value="{{ old('no_telepon') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea name="alamat" class="form-control rounded-3" rows="2" required>{{ old('alamat') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- FORM KHUSUS MITRA (Disembunyikan jika pelanggan dipilih) --}}
                        <div id="formMitraArea" class="bg-white p-4 rounded-4 shadow-sm border">
                            <p class="small fw-bold text-uppercase text-muted mb-3"><i class="bi bi-wallet2 me-1"></i> Data Pencairan (Khusus Mitra)</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Nomor KTP (NIK) <span class="text-danger">*</span></label>
                                    <input type="text" id="inputKtp" name="nomor_ktp" class="form-control rounded-3 font-monospace" value="{{ old('nomor_ktp') }}" maxlength="16">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Nama Bank <span class="text-danger">*</span></label>
                                    <select id="inputBank" name="nama_bank" class="form-select rounded-3">
                                        <option value="" disabled selected>Pilih Bank...</option>
                                        <option value="BCA">BCA</option><option value="Mandiri">Mandiri</option>
                                        <option value="BNI">BNI</option><option value="BRI">BRI</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Nomor Rekening <span class="text-danger">*</span></label>
                                    <input type="text" id="inputRekening" name="nomor_rekening" class="form-control rounded-3 font-monospace" value="{{ old('nomor_rekening') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-white border-top px-4 py-3 text-end rounded-bottom-4">
                        <button type="submit" class="btn btn-primary fw-semibold rounded-3 px-4 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // Logika untuk menampilkan/menyembunyikan form mitra
    function toggleMitraForm() {
        const roleMitra = document.getElementById('roleMitra').checked;
        const formMitraArea = document.getElementById('formMitraArea');
        const inputKtp = document.getElementById('inputKtp');
        const inputBank = document.getElementById('inputBank');
        const inputRekening = document.getElementById('inputRekening');

        if (roleMitra) {
            formMitraArea.style.display = 'block';
            // Wajibkan diisi jika Mitra
            inputKtp.setAttribute('required', 'required');
            inputBank.setAttribute('required', 'required');
            inputRekening.setAttribute('required', 'required');
        } else {
            formMitraArea.style.display = 'none';
            // Hapus wajib isi jika Pelanggan (agar form bisa disubmit)
            inputKtp.removeAttribute('required');
            inputBank.removeAttribute('required');
            inputRekening.removeAttribute('required');
        }
    }
    
    // Jalankan sekali saat halaman dimuat (untuk handle old input)
    document.addEventListener('DOMContentLoaded', toggleMitraForm);
</script>
@endsection