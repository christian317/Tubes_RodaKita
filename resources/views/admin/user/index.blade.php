@extends('layouts.admin')

@section('title', 'Manajemen user - Admin')
@section('page_title', 'Manajemen user')
@section('breadcrumb', 'tambah, edit, atau hapus data user')

@section('content')
    <div class="col px-0 pt-0 pb-5 min-vh-100">

        {{-- Sticky Header --}}
        <div class="py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0 text-dark">Manajemen Pengguna</h4>
                    <p class="text-muted mb-0 small">Kelola data pelanggan dan mitra perental</p>
                </div>
                <a href="{{ route('admin.user.create') }}" class="btn btn-primary fw-semibold rounded-3 shadow-sm px-3">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            {{-- TAB HEADER --}}
            <div class="card-header bg-white pt-3 pb-0 px-4 border-bottom-0">
                <ul class="nav nav-tabs border-bottom-0 gap-3" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold text-dark pb-3 px-1 border-0" id="mitra-tab"
                            data-bs-toggle="tab" data-bs-target="#mitra" type="button" role="tab"
                            style="border-bottom: 3px solid transparent !important;">
                            <i class="bi bi-person-badge me-2 text-primary"></i>Mitra Perental
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold text-muted pb-3 px-1 border-0" id="pelanggan-tab"
                            data-bs-toggle="tab" data-bs-target="#pelanggan" type="button" role="tab"
                            style="border-bottom: 3px solid transparent !important;">
                            <i class="bi bi-people-fill me-2 text-success"></i>Pelanggan
                        </button>
                    </li>
                </ul>
            </div>

            {{-- TAB CONTENT --}}
            <div class="card-body p-0 border-top">
                <div class="tab-content" id="userTabsContent">

                    {{-- TAB 1: MITRA --}}
                    <div class="tab-pane fade show active" id="mitra" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-4 py-3 fw-semibold">Nama & Kontak</th>
                                        <th class="py-3 fw-semibold">Alamat</th>
                                        <th class="py-3 fw-semibold">Rekening Bank</th>
                                        <th class="py-3 pe-4 fw-semibold text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top small">
                                    @forelse($mitras as $mitra)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark fs-6">{{ $mitra->nama }}</div>
                                                <div class="text-muted mt-1">{{ $mitra->email }}</div>
                                                <div class="text-muted"><i
                                                        class="bi bi-telephone me-1"></i>{{ $mitra->no_telepon }}</div>
                                            </td>
                                            <td><span class="text-muted">{{ Str::limit($mitra->alamat, 40) }}</span></td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $mitra->pemilikMobil->nama_bank ?? '-' }}
                                                </div>
                                                <span
                                                    class="font-monospace text-muted">{{ $mitra->pemilikMobil->nomor_rekening ?? '-' }}</span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('admin.user.edit', $mitra->id) }}"
                                                        class="btn btn-sm btn-light border fw-medium text-primary rounded-3"
                                                        title="Edit">
                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        <span class="d-none d-xl-inline">Edit</span>
                                                    </a>
                                                    <form action="{{ route('admin.user.destroy', $mitra->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus mitra {{ $mitra->nama }}?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-light border fw-medium text-danger rounded-3"
                                                            title="Hapus">
                                                            <i class="bi bi-trash me-1"></i>
                                                            <span class="d-none d-xl-inline">Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada mitra
                                                terdaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 2: PELANGGAN --}}
                    <div class="tab-pane fade" id="pelanggan" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-4 py-3 fw-semibold">Nama Pelanggan</th>
                                        <th class="py-3 fw-semibold">Email</th>
                                        <th class="py-3 fw-semibold">No. Telepon</th>
                                        <th class="py-3 fw-semibold">Alamat</th>
                                        <th class="py-3 pe-4 fw-semibold text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top small">
                                    @forelse($pelanggans as $pelanggan)
                                        <tr>
                                            <td class="ps-4 py-3 fw-bold text-dark">{{ $pelanggan->nama }}</td>
                                            <td class="text-muted">{{ $pelanggan->email }}</td>
                                            <td class="text-muted">{{ $pelanggan->no_telepon }}</td>
                                            <td><span class="text-muted">{{ Str::limit($pelanggan->alamat, 40) }}</span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('admin.user.edit', $pelanggan->id) }}"
                                                        class="btn btn-sm btn-light border fw-medium text-primary rounded-3"
                                                        title="Edit">
                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        <span class="d-none d-xl-inline">Edit</span>
                                                    </a>
                                                    <form action="{{ route('admin.user.destroy', $pelanggan->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus pelanggan {{ $pelanggan->nama }}?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-light border fw-medium text-danger rounded-3"
                                                            title="Hapus">
                                                            <i class="bi bi-trash me-1"></i>
                                                            <span class="d-none d-xl-inline">Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada pelanggan
                                                terdaftar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Script sederhana untuk memberi efek border biru pada Tab yang sedang aktif
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.addEventListener('show.bs.tab', function(e) {
                document.querySelectorAll('.nav-link').forEach(t => {
                    t.style.borderBottomColor = 'transparent';
                    t.classList.remove('text-dark');
                    t.classList.add('text-muted');
                });
                e.target.style.borderBottomColor = '#0d6efd';
                e.target.classList.remove('text-muted');
                e.target.classList.add('text-dark');
            });
        });
        // Set tab pertama aktif
        document.querySelector('.nav-link.active').style.borderBottomColor = '#0d6efd';
    </script>
@endsection
