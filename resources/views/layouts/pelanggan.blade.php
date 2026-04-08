<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sewa Mobil - Roda Kita')</title>
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Efek hover untuk kartu mobil */
        .car-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .car-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important; }
    </style>
</head>
<body class="bg-light text-dark" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    {{-- TOP NAVBAR --}}
    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top py-3">
        <div class="container">
            {{-- Logo --}}
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('pelanggan.dashboard') }}">
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 1.1rem;">RK</div>
                <span class="fw-bold fs-5 text-dark">Roda Kita</span>
            </a>

            {{-- Toggle Button (Mobile) --}}
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPelanggan">
                <i class="bi bi-list fs-2 text-dark"></i>
            </button>

            {{-- Menu Links --}}
            <div class="collapse navbar-collapse" id="navbarPelanggan">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link active text-primary" href="{{ route('pelanggan.dashboard') }}">Katalog Mobil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#">Pesanan Saya</a>
                    </li>
                </ul>

                {{-- User Profile & Logout --}}
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex justify-content-center align-items-center fw-bold" style="width: 35px; height: 35px;">
                            {{ substr(Auth::user()->nama ?? 'P', 0, 1) }}
                        </div>
                        <span class="fw-semibold text-dark small">{{ Auth::user()->nama ?? 'Pelanggan' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-light border btn-sm rounded-3 text-danger fw-semibold px-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="min-vh-100">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} Roda Kita. Sistem Penyewaan Mobil Terpercaya.
        </div>
    </footer>

</body>
</html>