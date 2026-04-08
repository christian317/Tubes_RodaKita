@extends('layouts.admin')

@section('title', 'Dashboard - Admin Roda Kita')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-25 text-warning mb-3" style="width: 45px; height: 45px; font-size: 1.5rem;">
                    <i class="bi bi-car-front-fill"></i>
                </div>
                <h2 class="fw-bold mb-1">24</h2>
                <p class="text-muted small fw-medium mb-3">Total Armada</p>
                <div class="small fw-semibold text-success mt-auto">
                    <i class="bi bi-arrow-up-short"></i> 2 baru bulan ini
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-25 text-info mb-3" style="width: 45px; height: 45px; font-size: 1.5rem;">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <h2 class="fw-bold mb-1">11</h2>
                <p class="text-muted small fw-medium mb-3">Aktif Disewa</p>
                <div class="small fw-semibold text-success mt-auto">
                    <i class="bi bi-arrow-up-short"></i> 46% utilisasi
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-25 text-success mb-3" style="width: 45px; height: 45px; font-size: 1.5rem;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h2 class="fw-bold mb-1">42.8<span class="fs-6 text-muted">jt</span></h2>
                <p class="text-muted small fw-medium mb-3">Pendapatan Bulan Ini</p>
                <div class="small fw-semibold text-success mt-auto">
                    <i class="bi bi-arrow-up-short"></i> +12% vs bulan lalu
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger bg-opacity-25 text-danger mb-3" style="width: 45px; height: 45px; font-size: 1.5rem;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h2 class="fw-bold mb-1">3</h2>
                <p class="text-muted small fw-medium mb-3">Perlu Perhatian</p>
                <div class="small fw-semibold text-danger mt-auto">
                    <i class="bi bi-arrow-down-short"></i> 2 denda · 1 jadwal
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0">Booking Aktif</h5>
                        <span class="text-muted small">Sewa sedang berjalan hari ini</span>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm fw-semibold rounded-3">Lihat Semua</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 px-3">
                            <thead class="text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 fw-medium border-bottom-0 pb-3">Mobil</th>
                                    <th class="fw-medium border-bottom-0 pb-3">Pelanggan</th>
                                    <th class="fw-medium border-bottom-0 pb-3">Periode</th>
                                    <th class="fw-medium border-bottom-0 pb-3">Status</th>
                                    <th class="pe-4 fw-medium border-bottom-0 pb-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-light rounded text-secondary d-flex justify-content-center align-items-center fs-5" style="width: 40px; height: 40px;">
                                                <i class="bi bi-car-front"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size: 14px;">Toyota Avanza</div>
                                                <span class="badge border text-muted fw-normal font-monospace">B 1234 RK</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size: 14px;">Budi Santoso</td>
                                    <td><span class="text-muted" style="font-size: 13px;">5–8 Jul 2026</span></td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3">Aktif</span></td>
                                    <td class="pe-4"><button class="btn btn-light btn-sm fw-semibold text-secondary">Detail</button></td>
                                </tr>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-light rounded text-secondary d-flex justify-content-center align-items-center fs-5" style="width: 40px; height: 40px;">
                                                <i class="bi bi-car-front"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size: 14px;">Honda Jazz</div>
                                                <span class="badge border text-muted fw-normal font-monospace">D 5678 RK</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size: 14px;">Siti Rahayu</td>
                                    <td><span class="text-muted" style="font-size: 13px;">4–7 Jul 2026</span></td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-dark rounded-pill px-3">Kembali Hari Ini</span></td>
                                    <td class="pe-4"><button class="btn btn-light border text-dark btn-sm fw-semibold">Cek Kondisi</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-0">Aktivitas Terbaru</h5>
                        <span class="text-muted small">Log aksi sistem operasional</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3 align-items-start">
                            <div class="bg-success bg-opacity-25 text-success rounded shadow-sm d-flex justify-content-center align-items-center" style="width: 35px; height: 35px;">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 14px;">Booking dikonfirmasi</div>
                                <div class="text-muted small">Siti Rahayu · Honda Jazz · 5 hari</div>
                                <div class="text-muted" style="font-size: 11px;">09:14 · Hari ini</div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 align-items-start">
                            <div class="bg-danger bg-opacity-25 text-danger rounded shadow-sm d-flex justify-content-center align-items-center" style="width: 35px; height: 35px;">
                                <i class="bi bi-exclamation-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark lh-1 mb-1" style="font-size: 14px;">Denda kerusakan dicatat</div>
                                <div class="text-muted small">Budi Santoso · Avanza · Rp 350.000</div>
                                <div class="text-muted" style="font-size: 11px;">08:50 · Hari ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection