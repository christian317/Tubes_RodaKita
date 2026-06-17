@extends('layouts.admin')

@section('title', 'Verifikasi Pengguna - Roda Kita')
@section('page_title', 'Verifikasi Pengguna')
@section('breadcrumb', 'Admin / Verifikasi Pengguna')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-person-check text-primary me-2"></i>Daftar Permohonan Verifikasi</h5>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success rounded-3 mx-4 mt-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold border-bottom-0">Pengguna</th>
                        <th class="py-3 fw-semibold border-bottom-0">Dokumen</th>
                        <th class="py-3 fw-semibold border-bottom-0">Tanggal</th>
                        <th class="pe-4 py-3 fw-semibold border-bottom-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top">
                    @forelse($verifikasis as $v)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark">{{ $v->user->nama ?? '-' }}</div>
                                <div class="small text-muted">{{ $v->user->email ?? '-' }}</div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex gap-2">
                                    @if($v->foto_ktp)
                                        <a href="{{ asset('storage/' . $v->foto_ktp) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-3">KTP</a>
                                    @endif
                                    @if($v->foto_sim)
                                        <a href="{{ asset('storage/' . $v->foto_sim) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-3">SIM</a>
                                    @endif
                                    @if($v->foto_selfie)
                                        <a href="{{ asset('storage/' . $v->foto_selfie) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-3">Selfie</a>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-muted">{{ $v->created_at ? $v->created_at->format('d M Y H:i') : '-' }}</td>
                            <td class="pe-4 py-3 text-end">
                                <form action="{{ route('admin.verifikasi.approve', $v->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success fw-bold rounded-3 px-3">
                                        <i class="bi bi-check-lg me-1"></i> Setujui
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger fw-bold rounded-3 px-3"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $v->id }}">
                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                </button>

                                <div class="modal fade" id="rejectModal{{ $v->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('admin.verifikasi.reject', $v->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom px-4 py-3 bg-light rounded-top-4">
                                                    <h5 class="modal-title fw-bold">Tolak Verifikasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <label class="form-label fw-bold">Catatan Penolakan</label>
                                                    <textarea name="catatan_verifikasi" class="form-control" rows="3" required></textarea>
                                                </div>
                                                <div class="modal-footer bg-light border-top p-3">
                                                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger rounded-3 fw-bold">Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-person-check display-1 text-muted opacity-25 d-block mb-3"></i>
                                <h6 class="fw-bold text-dark">Tidak ada permohonan verifikasi</h6>
                                <p class="text-muted small mb-0">Semua pengguna sudah terverifikasi atau belum ada yang mengajukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
