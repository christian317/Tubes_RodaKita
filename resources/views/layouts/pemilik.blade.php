<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mitra - Roda Kita')</title>
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light text-dark" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

<div class="container-fluid min-vh-100">
    <div class="row">
        
        <aside class="col-md-3 col-lg-2 d-none d-md-flex flex-column p-3 bg-white border-end position-fixed h-100 shadow-sm" style="z-index: 1000;">
            <div class="d-flex align-items-center mb-4 px-2 mt-2">
                <div class="bg-success text-white rounded d-flex align-items-center justify-content-center fw-bold me-2" style="width: 40px; height: 40px; font-size: 1.2rem;">RK</div>
                <div>
                    <div class="fw-bold fs-5 lh-1 text-dark">Roda Kita</div>
                    <div class="text-muted small">Mitra Pemilik</div>
                </div>
            </div>
            
            <div class="overflow-auto flex-grow-1">
                <p class="text-muted fw-bold small text-uppercase mb-2 px-2" style="letter-spacing: 0.5px;">Menu Utama</p>
                <ul class="nav flex-column mb-3">
                    <li class="nav-item">
                        <a href="{{ route('pemilik.dashboard') }}" class="nav-link bg-success bg-opacity-10 text-success fw-semibold rounded d-flex align-items-center px-3 py-2 mb-1">
                            <i class="bi bi-grid-1x2 me-3 fs-5"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item"><a href="#" class="nav-link text-dark fw-medium rounded d-flex align-items-center px-3 py-2 mb-1"><i class="bi bi-calendar-range text-muted me-3 fs-5"></i> Jadwal Mobil</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-dark fw-medium rounded d-flex align-items-center px-3 py-2 mb-1"><i class="bi bi-wallet2 text-muted me-3 fs-5"></i> Pendapatan & Komisi</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-dark fw-medium rounded d-flex align-items-center px-3 py-2 mb-1"><i class="bi bi-star-half text-muted me-3 fs-5"></i> Ulasan Pelanggan</a></li>
                </ul>
            </div>
            
            <div class="mt-auto pt-3">
                <div class="bg-light p-2 rounded d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-25 text-success d-flex justify-content-center align-items-center fw-bold me-2" style="width: 35px; height: 35px;">
                        {{ substr(Auth::user()->nama ?? 'M', 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark lh-1 text-truncate" style="font-size: 0.85rem; max-width: 110px;">{{ Auth::user()->nama ?? 'Mitra' }}</div>
                        <div class="text-muted text-uppercase mt-1" style="font-size: 0.7rem;">Pemilik Mobil</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 text-danger text-decoration-none" title="Logout"><i class="bi bi-box-arrow-right fs-5"></i></button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="col-md-9 offset-md-3 col-lg-10 offset-lg-2 px-0 d-flex flex-column min-vh-100">
            <header class="bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center sticky-top shadow-sm" style="z-index: 999;">
                <div>
                    <h4 class="fw-bold mb-0 text-dark lh-1">@yield('page_title', 'Dashboard')</h4>
                    <span class="text-muted small">@yield('breadcrumb', 'Mitra / Dashboard')</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative text-dark fs-5" style="cursor: pointer;"><i class="bi bi-bell"></i></div>
                </div>
            </header>
            
            <div class="p-4">
                @yield('content')
            </div>
        </main>
    </div>
</div>

</body>
</html>