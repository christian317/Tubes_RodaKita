@extends('layouts.admin')

@section('title', 'Tambah Promo - Roda Kita')
@section('page_title', 'Tambah Promo')
@section('breadcrumb', 'Admin / Promo / Tambah')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-tag-fill text-primary me-2"></i>Buat Kode Promo Baru</h5>
    </div>
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.promo.store') }}" method="POST">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kode Promo</label>
                    <input type="text" name="kode_promo" class="form-control rounded-3" value="{{ old('kode_promo') }}" placeholder="Contoh: RODAKITA10" required>
                    <div class="form-text">Gunakan huruf besar, angka, tanpa spasi.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Tipe Potongan</label>
                    <select name="tipe_potongan" class="form-select rounded-3" required>
                        <option value="persen" {{ old('tipe_potongan') === 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="nominal" {{ old('tipe_potongan') === 'nominal' ? 'selected' : '' }}>Nominal Tunai (Rupiah)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Nominal Potongan</label>
                    <input type="number" name="nominal_potongan" class="form-control rounded-3" value="{{ old('nominal_potongan') }}" placeholder="Contoh: 10 atau 50000" min="1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Minimal Transaksi (Rp)</label>
                    <input type="number" name="minimal_transaksi" class="form-control rounded-3" value="{{ old('minimal_transaksi', 0) }}" placeholder="Contoh: 100000" min="0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Kuota Pemakaian</label>
                    <input type="number" name="kuota" class="form-control rounded-3" value="{{ old('kuota', 100) }}" placeholder="Jumlah kuota voucher" min="1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Tanggal Kadaluarsa</label>
                    <input type="date" name="tanggal_kadaluarsa" class="form-control rounded-3" value="{{ old('tanggal_kadaluarsa') }}" required>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                <a href="{{ route('admin.promo.index') }}" class="btn btn-secondary rounded-3 px-4">Batal</a>
                <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">
                    <i class="bi bi-save me-1"></i> Simpan Promo
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
