@extends('layouts.admin')

@section('title', 'Manajemen Promo - Roda Kita')
@section('page_title', 'Manajemen Promo')
@section('breadcrumb', 'Admin / Manajemen Promo')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="bi bi-tags text-primary me-2"></i>Daftar Kode Promo & Voucher</h5>
    <a href="{{ route('admin.promo.create') }}" class="btn btn-primary fw-bold rounded-3 px-4 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Promo
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success rounded-3 mx-4 mt-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold border-bottom-0">Kode Promo</th>
                        <th class="py-3 fw-semibold border-bottom-0">Diskon</th>
                        <th class="py-3 fw-semibold border-bottom-0">Minimal Transaksi</th>
                        <th class="py-3 fw-semibold border-bottom-0">Kuota</th>
                        <th class="py-3 fw-semibold border-bottom-0">Kadaluarsa</th>
                        <th class="pe-4 py-3 fw-semibold border-bottom-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top">
                    @forelse($promos as $promo)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold font-monospace px-3 py-2 fs-6 rounded-3">
                                    {{ $promo->kode_promo }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($promo->tipe_potongan === 'persen')
                                    <span class="fw-bold text-success">{{ number_format($promo->nominal_potongan, 0) }}%</span>
                                @else
                                    <span class="fw-bold text-success">Rp {{ number_format($promo->nominal_potongan, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="py-3 text-dark fw-medium">
                                Rp {{ number_format($promo->minimal_transaksi, 0, ',', '.') }}
                            </td>
                            <td class="py-3">
                                <span class="badge bg-secondary rounded-pill px-3">{{ $promo->kuota }}</span>
                            </td>
                            <td class="py-3 text-muted">
                                {{ \Carbon\Carbon::parse($promo->tanggal_kadaluarsa)->format('d M Y') }}
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <a href="{{ route('admin.promo.edit', $promo->id) }}" class="btn btn-sm btn-outline-warning fw-bold rounded-3 px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold rounded-3 px-3">
                                        <i class="bi bi-trash3 me-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-tags display-1 text-muted opacity-25 d-block mb-3"></i>
                                <h6 class="fw-bold text-dark">Belum ada kode promo</h6>
                                <p class="text-muted small mb-0">Klik tombol "Tambah Promo" untuk membuat voucher diskon sewa mobil.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
