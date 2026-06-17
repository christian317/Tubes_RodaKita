@extends('layouts.admin')

@section('title', 'Pengajuan Klaim - Roda Kita')
@section('page_title', 'Pengajuan Klaim')
@section('breadcrumb', 'Admin / Pengajuan Klaim')

@section('content')

@if(session('success'))
    <div class="alert alert-success rounded-3">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0"><i class="bi bi-shield-check text-primary me-2"></i>Semua Klaim Asuransi</h5>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 fw-semibold border-bottom-0">Mitra</th>
                        <th class="py-3 fw-semibold border-bottom-0">Mobil</th>
                        <th class="py-3 fw-semibold border-bottom-0">Estimasi</th>
                        <th class="py-3 fw-semibold border-bottom-0">Status</th>
                        <th class="pe-4 py-3 fw-semibold border-bottom-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top">
                    @forelse(\ as \)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark small">{{ \->pemilik->nama ?? '-' }}</div>
                            </td>
                            <td class="py-3">
                                <div class="small text-dark">{{ \->booking->mobil->brand->nama_brand ?? '' }} {{ \->booking->mobil->model ?? '' }}</div>
                                <div class="text-muted small" style="font-size:0.75rem;">#{{ \->id_booking }}</div>
                            </td>
                            <td class="py-3 fw-bold text-dark">Rp {{ number_format(\->estimasi_biaya, 0, ',', '.') }}</td>
                            <td class="py-3">
                                @if(\->status == 'diajukan')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 rounded-pill">Diajukan</span>
                                @elseif(\->status == 'ditinjau')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1 rounded-pill">Ditinjau</span>
                                @elseif(\->status == 'disetujui')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill">Disetujui</span>
                                @elseif(\->status == 'ditolak')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded-pill">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1 rounded-pill">Selesai</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-3"
                                        data-bs-toggle="modal" data-bs-target="#prosesKlaim{{ \->id }}">
                                    <i class="bi bi-pencil-square me-1"></i> Proses
                                </button>

                                <div class="modal fade" id="prosesKlaim{{ \->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('admin.klaim.proses', \->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom px-4 py-3 bg-light rounded-top-4">
                                                    <h5 class="modal-title fw-bold">Proses Klaim</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <strong>Mitra:</strong> {{ \->pemilik->nama ?? '-' }}<br>
                                                        <strong>Mobil:</strong> {{ \->booking->mobil->brand->nama_brand ?? '' }} {{ \->booking->mobil->model ?? '' }}<br>
                                                        <strong>Deskripsi:</strong> {{ \->deskripsi_kerusakan }}<br>
                                                        <strong>Estimasi:</strong> Rp {{ number_format(\->estimasi_biaya, 0, ',', '.') }}
                                                    </div>
                                                    @if(\->foto_bukti && count(\->foto_bukti) > 0)
                                                        <div class="mb-3">
                                                            <strong>Foto:</strong>
                                                            <div class="d-flex gap-2 mt-1">
                                                                @foreach(\->foto_bukti as \)
                                                                    <a href="{{ asset('storage/' . \) }}" target="_blank"><img src="{{ asset('storage/' . \) }}" style="height:60px;width:60px;object-fit:cover;" class="rounded border"></a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Aksi</label>
                                                        <select name="aksi" class="form-select" required>
                                                            <option value="setujui">Setujui</option>
                                                            <option value="tolak">Tolak</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Biaya Disetujui (jika setujui)</label>
                                                        <input type="number" name="biaya_disetujui" class="form-control" min="0" step="0.01">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Catatan</label>
                                                        <textarea name="catatan_klaim" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-top p-3">
                                                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-3 fw-bold">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-shield-check display-1 text-muted opacity-25 d-block mb-3"></i>
                                <h6 class="fw-bold text-dark">Belum ada klaim</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
