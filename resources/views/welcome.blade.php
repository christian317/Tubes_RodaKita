<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Roda Kita — Rental Mobil Terpercaya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet" />

    <style>
        :root {
            --rk-navy:   #0f2744;
            --rk-blue:   #1a4a8a;
            --rk-orange: #f97316;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fff; color: var(--rk-navy); }
        h1, h2, h3, h4, h5, h6, .font-display { font-family: 'Outfit', sans-serif; }

        /* Gradient text */
        .text-gradient {
            background: linear-gradient(90deg, #f97316, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Section eyebrow */
        .eyebrow {
            font-size: .72rem;
            font-weight: 700;
            color: var(--rk-orange);
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        /* Floating hero badges */
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }
        .float-1 { animation: float 3.8s ease-in-out infinite; }
        .float-2 { animation: float 3.8s ease-in-out 1.3s infinite; }

        /* SVG car float */
        .car-float { animation: float 4.2s ease-in-out .4s infinite; }

        /* Step number watermark */
        .step-number {
            font-family: 'Outfit', sans-serif;
            font-size: 5.5rem;
            font-weight: 900;
            color: #f1f5f9;
            line-height: 1;
            position: absolute;
            top: 10px; right: 16px;
            user-select: none;
        }

        /* Hover cards */
        .hover-lift { transition: transform .26s ease, box-shadow .26s ease; }
        .hover-lift:hover { transform: translateY(-6px); box-shadow: 0 22px 52px rgba(15,39,68,.12) !important; }

        .car-card { transition: transform .28s ease, box-shadow .28s ease; }
        .car-card:hover { transform: translateY(-7px); box-shadow: 0 24px 56px rgba(15,39,68,.13) !important; }
        .car-card:hover .car-thumb { transform: scale(1.06); }
        .car-card:hover .btn-sewa { background: var(--rk-orange) !important; }
        .car-thumb { object-fit: cover; transition: transform .4s ease; }

        .mitra-box { transition: background .2s, transform .2s; }
        .mitra-box:hover { background: rgba(255,255,255,.13) !important; transform: translateY(-4px); }

        /* Nav link */
        .rk-nav-link { transition: color .15s; }
        .rk-nav-link:hover { color: var(--rk-orange) !important; }

        /* Search input focus */
        .rk-input:focus { border-color: var(--rk-orange) !important; box-shadow: 0 0 0 3px rgba(249,115,22,.12) !important; outline: none; }

        /* Footer link */
        .footer-link { color: rgba(255,255,255,.4); text-decoration: none; font-size: .83rem; display: block; margin-bottom: .45rem; transition: color .15s; }
        .footer-link:hover { color: var(--rk-orange); }

        /* Social btn */
        .social-btn { width:34px; height:34px; border-radius:7px; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.5); text-decoration:none; font-size:.88rem; transition:background .18s, color .18s; }
        .social-btn:hover { background:var(--rk-orange); color:#fff; border-color:var(--rk-orange); }
    </style>
</head>
<body>


{{-- ═══════════════════════════════════
     NAVBAR
════════════════════════════════════ --}}
<nav class="navbar navbar-expand-lg sticky-top py-3"
     style="background:var(--rk-navy);box-shadow:0 2px 20px rgba(15,39,68,.28);">
    <div class="container">

        <a class="navbar-brand text-decoration-none font-display fw-bold fs-4" href="#">
            <span class="text-white">Roda</span><span style="color:var(--rk-orange);">Kita</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMain">
            <i class="bi bi-list fs-3 text-white"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav mx-auto gap-1 mt-3 mt-lg-0">
                <li class="nav-item">
                    <a href="#cara-kerja" class="nav-link rk-nav-link fw-medium px-3"
                       style="color:rgba(255,255,255,.72);">Cara Kerja</a>
                </li>
                <li class="nav-item">
                    {{-- DIARAHKAN KE LOGIN KARENA INI LANDING PAGE --}}
                    <a href="{{ route('login') }}" class="nav-link rk-nav-link fw-medium px-3"
                       style="color:rgba(255,255,255,.72);">Katalog Mobil</a>
                </li>
                <li class="nav-item">
                    <a href="#mitra" class="nav-link rk-nav-link fw-medium px-3"
                       style="color:rgba(255,255,255,.72);">Mitra Kami</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link rk-nav-link fw-medium px-3"
                       style="color:rgba(255,255,255,.72);">Bantuan</a>
                </li>
            </ul>
            <div class="d-flex gap-2 mt-3 mt-lg-0">
                <a href="{{ route('login') }}"
                   class="btn btn-sm fw-semibold px-4 py-2 rounded-3"
                   style="background:transparent;border:1.5px solid rgba(255,255,255,.32);color:#fff;">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="btn btn-sm fw-bold px-4 py-2 rounded-3"
                   style="background:var(--rk-orange);color:#fff;border:none;box-shadow:0 4px 14px rgba(249,115,22,.35);">
                    Daftar Gratis
                </a>
            </div>
        </div>

    </div>
</nav>


{{-- ═══════════════════════════════════
     HERO
════════════════════════════════════ --}}
<section class="position-relative overflow-hidden d-flex align-items-center py-5"
         style="background:linear-gradient(135deg,var(--rk-navy) 0%,var(--rk-blue) 58%,#1e5fac 100%);min-height:90vh;">

    {{-- Glow blobs --}}
    <div class="position-absolute top-0 end-0 rounded-circle"
         style="width:620px;height:620px;background:radial-gradient(circle,rgba(249,115,22,.13) 0%,transparent 70%);transform:translate(100px,-100px);pointer-events:none;"></div>
    <div class="position-absolute bottom-0 start-0 rounded-circle"
         style="width:460px;height:460px;background:radial-gradient(circle,rgba(30,95,172,.38) 0%,transparent 70%);transform:translate(-80px,80px);pointer-events:none;"></div>

    <div class="container position-relative py-5" style="z-index:2;">
        <div class="row align-items-center g-5">

            {{-- Teks kiri --}}
            <div class="col-lg-6">
                <span class="badge rounded-pill px-3 py-2 mb-4 d-inline-flex align-items-center gap-2"
                      style="background:rgba(249,115,22,.2);color:#fb923c;border:1px solid rgba(249,115,22,.36);font-size:.72rem;font-weight:700;letter-spacing:.07em;">
                    <i class="bi bi-shield-check-fill"></i> PLATFORM RENTAL #1 TERPERCAYA
                </span>

                <h1 class="fw-bold text-white mb-3 font-display lh-sm"
                    style="font-size:clamp(2rem,4.5vw,3.6rem);">
                    Sewa Mobil Mudah,<br>
                    <span class="text-gradient">Nyaman & Aman</span>
                </h1>

                <p class="mb-4" style="color:rgba(255,255,255,.65);font-size:1rem;line-height:1.8;max-width:490px;">
                    Roda Kita menghubungkan Anda dengan ratusan armada pilihan dari mitra
                    terverifikasi. Booking online dalam hitungan menit, bayar digital,
                    berangkat tanpa ribet.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5">
                    {{-- DIARAHKAN KE LOGIN KARENA INI LANDING PAGE --}}
                    <a href="{{ route('login') }}"
                       class="btn fw-bold px-4 py-3 rounded-3 d-inline-flex align-items-center gap-2"
                       style="background:var(--rk-orange);color:#fff;border:none;font-size:.97rem;box-shadow:0 8px 24px rgba(249,115,22,.4);">
                        <i class="bi bi-search"></i> Cari Mobil Sekarang
                    </a>
                    <a href="#cara-kerja"
                       class="btn fw-semibold px-4 py-3 rounded-3 d-inline-flex align-items-center gap-2"
                       style="background:rgba(255,255,255,.1);color:#fff;border:1.5px solid rgba(255,255,255,.26);font-size:.97rem;backdrop-filter:blur(6px);">
                        <i class="bi bi-play-circle"></i> Cara Kerja
                    </a>
                </div>

                {{-- Stats --}}
                <div class="d-flex flex-wrap gap-4 pt-4"
                     style="border-top:1px solid rgba(255,255,255,.12);">
                    @foreach([['500+','Armada Aktif'],['120+','Mitra Perental'],['10rb+','Pelanggan Puas'],['4.9★','Rating']] as [$num,$lbl])
                    <div>
                        <div class="fw-bold font-display text-white" style="font-size:1.65rem;line-height:1.1;">{{ $num }}</div>
                        <div style="color:rgba(255,255,255,.48);font-size:.75rem;margin-top:3px;">{{ $lbl }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Ilustrasi kanan --}}
            <div class="col-lg-6 d-none d-lg-block position-relative">

                {{-- SVG Mobil --}}
                <svg class="car-float" viewBox="0 0 560 300" xmlns="http://www.w3.org/2000/svg"
                     style="width:100%;max-width:540px;filter:drop-shadow(0 32px 64px rgba(0,0,0,.45));">
                    <ellipse cx="280" cy="280" rx="228" ry="15" fill="rgba(0,0,0,.22)"/>
                    <rect x="55" y="158" width="450" height="102" rx="18" fill="url(#bg1)"/>
                    <path d="M136 158 C146 94 197 71 265 69 L348 69 C413 69 451 95 459 158Z" fill="url(#bg2)"/>
                    <path d="M159 152 C164 112 197 93 249 90 L249 152Z" fill="rgba(147,210,255,.72)"/>
                    <rect x="259" y="90" width="72" height="62" fill="rgba(147,210,255,.8)"/>
                    <path d="M341 90 C385 93 419 112 437 152 L341 152Z" fill="rgba(147,210,255,.72)"/>
                    <line x1="255" y1="90" x2="255" y2="152" stroke="rgba(255,255,255,.22)" stroke-width="2.5"/>
                    <line x1="335" y1="90" x2="335" y2="152" stroke="rgba(255,255,255,.22)" stroke-width="2.5"/>
                    <rect x="200" y="67" width="162" height="5" rx="2.5" fill="rgba(255,255,255,.14)"/>
                    <ellipse cx="498" cy="192" rx="19" ry="11" fill="#fbbf24"/>
                    <ellipse cx="498" cy="192" rx="12" ry="7"  fill="#fde68a"/>
                    <ellipse cx="68"  cy="192" rx="17" ry="10" fill="#ef4444"/>
                    <ellipse cx="68"  cy="192" rx="11" ry="6"  fill="#fca5a5"/>
                    <rect x="311" y="188" width="30" height="6" rx="3" fill="rgba(255,255,255,.2)"/>
                    <rect x="222" y="188" width="30" height="6" rx="3" fill="rgba(255,255,255,.2)"/>
                    <line x1="284" y1="160" x2="284" y2="258" stroke="rgba(255,255,255,.1)" stroke-width="2"/>
                    <circle cx="154" cy="258" r="42" fill="#0a1929"/>
                    <circle cx="154" cy="258" r="29" fill="#1e3058"/>
                    <circle cx="154" cy="258" r="14" fill="#475569"/>
                    <circle cx="154" cy="258" r="6"  fill="#94a3b8"/>
                    <line x1="154" y1="244" x2="154" y2="272" stroke="#94a3b8" stroke-width="2.5"/>
                    <line x1="140" y1="258" x2="168" y2="258" stroke="#94a3b8" stroke-width="2.5"/>
                    <line x1="144" y1="248" x2="164" y2="268" stroke="#94a3b8" stroke-width="2.5"/>
                    <line x1="164" y1="248" x2="144" y2="268" stroke="#94a3b8" stroke-width="2.5"/>
                    <circle cx="406" cy="258" r="42" fill="#0a1929"/>
                    <circle cx="406" cy="258" r="29" fill="#1e3058"/>
                    <circle cx="406" cy="258" r="14" fill="#475569"/>
                    <circle cx="406" cy="258" r="6"  fill="#94a3b8"/>
                    <line x1="406" y1="244" x2="406" y2="272" stroke="#94a3b8" stroke-width="2.5"/>
                    <line x1="392" y1="258" x2="420" y2="258" stroke="#94a3b8" stroke-width="2.5"/>
                    <line x1="396" y1="248" x2="416" y2="268" stroke="#94a3b8" stroke-width="2.5"/>
                    <line x1="416" y1="248" x2="396" y2="268" stroke="#94a3b8" stroke-width="2.5"/>
                    <defs>
                        <linearGradient id="bg1" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#2563eb"/>
                            <stop offset="100%" stop-color="#0f2744"/>
                        </linearGradient>
                        <linearGradient id="bg2" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#1e4fa0"/>
                            <stop offset="100%" stop-color="#163a76"/>
                        </linearGradient>
                    </defs>
                </svg>

                {{-- Badge mengambang 1 --}}
                <div class="position-absolute bg-white rounded-4 shadow-lg p-3 d-flex align-items-center gap-2 float-1"
                     style="bottom:16%;right:2%;min-width:168px;">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:38px;height:38px;background:#fff7ed;color:var(--rk-orange);font-size:1.1rem;">
                        <i class="bi bi-credit-card-2-front-fill"></i>
                    </div>
                    <div>
                        <div style="font-size:.66rem;color:#94a3b8;font-weight:600;">Pembayaran</div>
                        <div class="fw-bold" style="font-size:.84rem;color:var(--rk-navy);">100% Aman</div>
                    </div>
                </div>

                {{-- Badge mengambang 2 --}}
                <div class="position-absolute bg-white rounded-4 shadow-lg p-3 d-flex align-items-center gap-2 float-2"
                     style="top:6%;left:0%;min-width:168px;">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:38px;height:38px;background:#e8f4fd;color:var(--rk-blue);font-size:1.1rem;">
                        <i class="bi bi-shield-check-fill"></i>
                    </div>
                    <div>
                        <div style="font-size:.66rem;color:#94a3b8;font-weight:600;">Mitra</div>
                        <div class="fw-bold" style="font-size:.84rem;color:var(--rk-navy);">Terverifikasi</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════
     SEARCH BAR MENGAMBANG
════════════════════════════════════ --}}
<div class="container" style="margin-top:-48px;position:relative;z-index:10;">
    <div class="bg-white rounded-4 shadow p-4">
        <div class="row g-3 align-items-end">

            <div class="col-md-4">
                <label class="form-label fw-bold text-muted mb-1"
                       style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;">
                    <i class="bi bi-geo-alt-fill me-1" style="color:var(--rk-orange);"></i>Kota / Lokasi
                </label>
                <input type="text" class="form-control bg-light border rounded-3 rk-input"
                       placeholder="Bandung, Jawa Barat" style="padding:.68rem .9rem;">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold text-muted mb-1"
                       style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;">
                    <i class="bi bi-calendar3 me-1" style="color:var(--rk-orange);"></i>Tanggal Mulai
                </label>
                <input type="date" id="tglMulai" class="form-control bg-light border rounded-3 rk-input"
                       style="padding:.68rem .9rem;">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold text-muted mb-1"
                       style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;">
                    <i class="bi bi-calendar3 me-1" style="color:var(--rk-orange);"></i>Tanggal Selesai
                </label>
                <input type="date" id="tglSelesai" class="form-control bg-light border rounded-3 rk-input"
                       style="padding:.68rem .9rem;">
            </div>

            <div class="col-md-2 d-grid">
                {{-- DIARAHKAN KE LOGIN KARENA INI LANDING PAGE --}}
                <a href="{{ route('login') }}"
                   class="btn fw-bold py-2 rounded-3 d-flex align-items-center justify-content-center gap-2"
                   style="background:var(--rk-orange);color:#fff;border:none;box-shadow:0 6px 18px rgba(249,115,22,.32);font-size:.93rem;padding:.7rem;">
                    <i class="bi bi-search"></i> Cari
                </a>
            </div>

        </div>
    </div>
</div>


{{-- ═══════════════════════════════════
     CARA KERJA
════════════════════════════════════ --}}
<section id="cara-kerja" class="py-5 mt-4" style="background:#f8fafc;">
    <div class="container py-4">

        <div class="text-center mb-5">
            <p class="eyebrow mb-2">Cara Kerja</p>
            <h2 class="fw-bold font-display mb-2" style="color:var(--rk-navy);font-size:clamp(1.7rem,3vw,2.4rem);">
                Sewa Mobil Semudah 3 Langkah
            </h2>
            <p class="text-muted mx-auto" style="max-width:430px;font-size:.95rem;">
                Dari pencarian hingga kunci di tangan, semuanya beres di Roda Kita.
            </p>
        </div>

        <div class="row g-4">
            @foreach([
                ['bi-search',        '01', 'Pilih & Cari Mobil',   'Telusuri ratusan katalog. Filter berdasarkan tipe, kapasitas, dan harga sesuai kebutuhanmu.'],
                ['bi-credit-card-2-front', '02', 'Booking & Bayar','Pilih layanan (lepas kunci atau dengan supir), booking sesuai jadwal, dan bayar aman via Midtrans.'],
                ['bi-car-front',     '03', 'Terima & Jalan!',      'Ambil mobil atau bertemu dengan supir sesuai kesepakatan. Kondisi dicatat, tinggal menikmati perjalanan.'],
            ] as $i => [$icon, $num, $title, $desc])
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4 hover-lift position-relative overflow-hidden
                            {{ $i === 1 ? '' : '' }}"
                     style="{{ $i === 1 ? 'border-top:3px solid var(--rk-orange) !important;' : '' }}">
                    <div class="step-number">{{ $num }}</div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center mb-4"
                         style="width:54px;height:54px;background:#fff7ed;color:var(--rk-orange);font-size:1.45rem;">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <h5 class="fw-bold mb-2 font-display" style="color:var(--rk-navy);font-size:1.05rem;">{{ $title }}</h5>
                    <p class="text-muted mb-0" style="font-size:.9rem;line-height:1.75;">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════
     MOBIL POPULER (dari DB)
════════════════════════════════════ --}}
<section class="py-5">
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-5">
            <div>
                <p class="eyebrow mb-2">Pilihan Terpopuler</p>
                <h2 class="fw-bold font-display mb-0" style="color:var(--rk-navy);font-size:clamp(1.7rem,3vw,2.4rem);">
                    Mobil Tersedia Sekarang
                </h2>
            </div>
            {{-- DIARAHKAN KE LOGIN KARENA INI LANDING PAGE --}}
            <a href="{{ route('login') }}"
               class="btn fw-bold px-4 py-2 rounded-3 d-inline-flex align-items-center gap-2"
               style="background:var(--rk-navy);color:#fff;border:none;font-size:.88rem;">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">

            @forelse($mobilPopuler ?? [] as $m)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative car-card">
                    {{-- DIARAHKAN KE LOGIN --}}
                    <a href="{{ route('login') }}"
                       class="stretched-link" aria-label="{{ $m->model }}"></a>

                    <div class="position-relative" style="height:190px;overflow:hidden;background:linear-gradient(145deg,#f1f5f9,#e8f0fb);">
                        @if($m->gambar)
                            <img src="{{ asset('storage/' . $m->gambar) }}"
                                 class="w-100 h-100 car-thumb" alt="{{ $m->model }}" loading="lazy">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-car-front" style="font-size:3.5rem;color:#cbd5e1;"></i>
                            </div>
                        @endif

                        @if($m->kategori)
                        <span class="position-absolute top-0 start-0 m-2 badge fw-bold px-2 py-1"
                              style="background:var(--rk-navy);font-size:.63rem;border-radius:50px;letter-spacing:.04em;">
                            {{ $m->kategori->nama_kategori }}
                        </span>
                        @endif

                        <span class="position-absolute top-0 end-0 m-2 badge bg-white text-dark border shadow-sm px-2 py-1 fw-semibold"
                              style="font-size:.68rem;border-radius:50px;">
                            <i class="bi bi-gear-fill text-primary me-1"></i>{{ $m->transmisi }}
                        </span>
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        <p class="fw-bold text-muted text-uppercase mb-1" style="font-size:.64rem;letter-spacing:.1em;">
                            {{ $m->brand->nama_brand ?? 'Merk' }}
                        </p>
                        <h6 class="fw-bold mb-3 text-truncate font-display" style="color:var(--rk-navy);font-size:1rem;" title="{{ $m->model }}">
                            {{ $m->model }}
                        </h6>
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <span class="badge bg-light text-secondary border fw-medium" style="font-size:.71rem;">
                                <i class="bi bi-people-fill me-1"></i>{{ $m->kapasitas_penumpang }} Kursi
                            </span>
                            <span class="badge bg-light text-secondary border fw-medium" style="font-size:.71rem;">
                                <i class="bi bi-calendar3 me-1"></i>{{ $m->tahun }}
                            </span>
                        </div>
                        <div class="mt-auto border-top pt-3 d-flex justify-content-between align-items-end">
                            <div>
                                <p class="text-muted mb-1" style="font-size:.67rem;">Mulai dari</p>
                                <p class="fw-bold mb-0 font-display lh-1" style="font-size:1.18rem;color:var(--rk-navy);">
                                    Rp {{ number_format($m->harga_sewa,0,',','.') }}
                                    <span class="fw-normal text-muted" style="font-size:.71rem;">/ hari</span>
                                </p>
                            </div>
                            <span class="btn btn-sm fw-bold rounded-3 px-3 btn-sewa"
                                  style="background:var(--rk-navy);color:#fff;font-size:.8rem;pointer-events:none;position:relative;z-index:6;transition:background .2s;">
                                Sewa <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            {{-- Fallback dummy cards jika $mobilPopuler kosong --}}
            @foreach([
                ['Toyota Avanza','MPV','7','350.000','2563eb','Otomatis'],
                ['Mitsubishi Pajero Sport','SUV','7','850.000','0f2744','Otomatis'],
                ['Honda Brio','City Car','5','250.000','059669','Otomatis'],
                ['Toyota Innova Crysta','MPV','8','650.000','7c3aed','Manual'],
            ] as $d)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative car-card">
                    {{-- DIARAHKAN KE LOGIN --}}
                    <a href="{{ route('login') }}" class="stretched-link"></a>
                    <div class="position-relative" style="height:190px;overflow:hidden;background:linear-gradient(145deg,#f1f5f9,#e8f0fb);">
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-car-front" style="font-size:3.8rem;color:#{{ $d[4] }};opacity:.28;"></i>
                        </div>
                        <span class="position-absolute top-0 start-0 m-2 badge fw-bold px-2 py-1"
                              style="background:var(--rk-navy);font-size:.63rem;border-radius:50px;">{{ $d[1] }}</span>
                        <span class="position-absolute top-0 end-0 m-2 badge bg-white text-dark border shadow-sm px-2 py-1 fw-semibold"
                              style="font-size:.68rem;border-radius:50px;">
                            <i class="bi bi-gear-fill text-primary me-1"></i>{{ $d[5] }}
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <p class="fw-bold text-muted text-uppercase mb-1" style="font-size:.64rem;letter-spacing:.1em;">Toyota / Honda</p>
                        <h6 class="fw-bold mb-3 font-display" style="color:var(--rk-navy);font-size:1rem;">{{ $d[0] }}</h6>
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <span class="badge bg-light text-secondary border fw-medium" style="font-size:.71rem;">
                                <i class="bi bi-people-fill me-1"></i>{{ $d[2] }} Kursi
                            </span>
                            <span class="badge bg-light text-secondary border fw-medium" style="font-size:.71rem;">
                                <i class="bi bi-wind me-1"></i>AC
                            </span>
                        </div>
                        <div class="mt-auto border-top pt-3 d-flex justify-content-between align-items-end">
                            <div>
                                <p class="text-muted mb-1" style="font-size:.67rem;">Mulai dari</p>
                                <p class="fw-bold mb-0 font-display lh-1" style="font-size:1.18rem;color:var(--rk-navy);">
                                    Rp {{ $d[3] }}
                                    <span class="fw-normal text-muted" style="font-size:.71rem;">/ hari</span>
                                </p>
                            </div>
                            <span class="btn btn-sm fw-bold rounded-3 px-3 btn-sewa"
                                  style="background:var(--rk-navy);color:#fff;font-size:.8rem;pointer-events:none;z-index:6;transition:background .2s;">
                                Sewa <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endforelse

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════
     KENAPA RODA KITA
════════════════════════════════════ --}}
<section class="py-5" style="background:#f8fafc;">
    <div class="container py-4">

        <div class="text-center mb-5">
            <p class="eyebrow mb-2">Keunggulan Kami</p>
            <h2 class="fw-bold font-display mb-0" style="color:var(--rk-navy);font-size:clamp(1.7rem,3vw,2.4rem);">
                Kenapa Pilih Roda Kita?
            </h2>
        </div>

        <div class="row g-4">
            @foreach([
                ['bi-shield-check-fill', '#dbeafe', '#2563eb', 'Mitra Terverifikasi',
                 'Setiap mitra perental sudah melalui seleksi ketat. Armada terawat, identitas terverifikasi.'],
                ['bi-credit-card-2-front-fill', '#fff7ed', '#f97316', 'Pembayaran Aman',
                 'Transaksi diproses oleh Midtrans. Transfer bank, kartu kredit, GoPay, OVO — semua tersedia.'],
                ['bi-clock-fill', '#f0fdf4', '#16a34a', 'Layanan 24/7',
                 'Tim Roda Kita siap membantu kapan saja kamu perlu, bahkan tengah malam sekalipun.'],
                ['bi-star-fill', '#faf5ff', '#9333ea', 'Rating Transparan',
                 'Ulasan asli dari penyewa nyata. Kamu tahu persis kondisi mobil sebelum booking.'],
                ['bi-geo-alt-fill', '#fff7ed', '#f97316', 'Lepas Kunci / Dengan Supir',
                 'Pilih layanan lepas kunci untuk privasi maksimal, atau sewa dengan supir untuk perjalanan yang lebih santai.'],
                ['bi-headset', '#f0fdf4', '#16a34a', 'Bantuan Penuh',
                 'Kami handle semua urusan dengan pemilik mobil. Kamu cukup fokus menikmati perjalanan.'],
            ] as [$icon, $bg, $color, $title, $desc])
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift">
                    <div class="rounded-3 d-flex align-items-center justify-content-center mb-3"
                         style="width:52px;height:52px;background:{{ $bg }};color:{{ $color }};font-size:1.35rem;">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <h6 class="fw-bold mb-2 font-display" style="color:var(--rk-navy);font-size:.97rem;">{{ $title }}</h6>
                    <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.75;">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════
     TESTIMONI
════════════════════════════════════ --}}
<section class="py-5">
    <div class="container py-4">

        <div class="text-center mb-5">
            <p class="eyebrow mb-2">Testimoni</p>
            <h2 class="fw-bold font-display mb-0" style="color:var(--rk-navy);font-size:clamp(1.7rem,3vw,2.4rem);">
                Apa Kata Pelanggan Kami
            </h2>
        </div>

        <div class="row g-4">
            @foreach([
                ['AR','Andi Ramadhan','Bandung','linear-gradient(135deg,#f97316,#fbbf24)','★★★★★',
                 'Prosesnya cepat banget! Pesan dengan fitur lepas kunci, proses ambil mobil mulus. Kondisi bersih, AC dingin. Pasti sewa lagi!'],
                ['SK','Sari Kusuma','Surabaya','linear-gradient(135deg,#059669,#10b981)','★★★★★',
                 'Liburan keluarga ke Bali jadi tenang karena sudah booking jauh-jauh hari. Pembayaran via GoPay juga mudah banget.'],
                ['DL','Dewi Lestari','Jakarta','linear-gradient(135deg,#2563eb,#60a5fa)','★★★★☆',
                 'Innova-nya nyaman banget untuk perjalanan jauh ke Yogya. Tidak ada masalah selama 3 hari. Sangat recommended!'],
            ] as [$init,$name,$city,$grad,$stars,$review])
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift">
                    <div class="mb-3" style="color:#f59e0b;letter-spacing:.06em;">{{ $stars }}</div>
                    <p class="text-muted mb-4" style="font-size:.91rem;line-height:1.78;font-style:italic;">
                        "{{ $review }}"
                    </p>
                    <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0 font-display"
                             style="width:42px;height:42px;background:{{ $grad }};font-size:.88rem;">
                            {{ $init }}
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:.88rem;color:var(--rk-navy);">{{ $name }}</div>
                            <div class="text-muted" style="font-size:.75rem;">
                                <i class="bi bi-geo-alt me-1"></i>{{ $city }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════
     CTA MITRA PERENTAL
════════════════════════════════════ --}}
<section id="mitra" class="py-5 position-relative overflow-hidden"
         style="background:linear-gradient(135deg,var(--rk-navy) 0%,var(--rk-blue) 100%);">

    <div class="position-absolute top-0 end-0 rounded-circle"
         style="width:500px;height:500px;background:radial-gradient(circle,rgba(249,115,22,.14) 0%,transparent 70%);transform:translate(100px,-100px);pointer-events:none;"></div>

    <div class="container py-4 position-relative" style="z-index:2;">

        <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-2 mb-3 fw-bold d-inline-block"
                  style="background:rgba(249,115,22,.2);color:#fb923c;border:1px solid rgba(249,115,22,.34);font-size:.7rem;letter-spacing:.07em;">
                BERGABUNG BERSAMA KAMI
            </span>
            <h2 class="fw-bold font-display text-white mb-2" style="font-size:clamp(1.7rem,3vw,2.4rem);">
                Punya Armada Mobil?<br>Titipkan ke Roda Kita
            </h2>
            <p style="color:rgba(255,255,255,.6);font-size:.97rem;max-width:460px;margin:0 auto;">
                Kami urus semuanya — dari promosi hingga pelanggan.
                Kamu cukup pantau jadwal dan terima penghasilan.
            </p>
        </div>

        <div class="row g-4 mb-5">
            @foreach([
                ['bi-cash-stack',      'rgba(249,115,22,.2)', '#fb923c', 'Pendapatan Transparan',
                 'Komisi 70% per transaksi langsung terekam. Tidak ada biaya tersembunyi. Proses withdraw mudah.'],
                ['bi-calendar2-check', 'rgba(96,165,250,.2)', '#60a5fa', 'Jadwal Terpantau',
                 'Dashboard kalender menampilkan kapan mobil disewa dan kapan standby. Mudah dipantau kapanpun.'],
                ['bi-star',            'rgba(52,211,153,.2)', '#34d399', 'Pantau Ulasan',
                 'Ketahui feedback pelanggan untuk tiap mobilmu. Ambil tindakan sebelum masalah membesar.'],
            ] as [$icon, $bg, $color, $title, $desc])
            <div class="col-md-4">
                <div class="rounded-4 p-4 mitra-box"
                     style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);">
                    <div class="rounded-3 d-flex align-items-center justify-content-center mb-3"
                         style="width:50px;height:50px;background:{{ $bg }};color:{{ $color }};font-size:1.4rem;">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <h6 class="fw-bold text-white mb-2 font-display" style="font-size:.97rem;">{{ $title }}</h6>
                    <p style="color:rgba(255,255,255,.56);font-size:.87rem;line-height:1.75;margin-bottom:0;">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}"
               class="btn fw-bold px-5 py-3 rounded-3 d-inline-flex align-items-center gap-2"
               style="background:var(--rk-orange);color:#fff;border:none;font-size:.97rem;box-shadow:0 8px 28px rgba(249,115,22,.4);">
                <i class="bi bi-person-plus-fill"></i> Daftarkan Armada Saya
            </a>
            <p class="mt-3 mb-0" style="color:rgba(255,255,255,.38);font-size:.78rem;">
                Gratis daftar &nbsp;·&nbsp; Tidak ada biaya awal &nbsp;·&nbsp; Proses cepat
            </p>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════
     FOOTER
════════════════════════════════════ --}}
<footer class="py-5" style="background:#080f1c;">
    <div class="container">

        <div class="row g-5 pb-4">

            <div class="col-lg-4">
                <div class="fw-bold font-display mb-2" style="font-size:1.35rem;">
                    <span class="text-white">Roda</span><span style="color:var(--rk-orange);">Kita</span>
                </div>
                <p style="color:rgba(255,255,255,.42);font-size:.86rem;line-height:1.78;max-width:268px;">
                    Platform rental mobil terpercaya yang menghubungkan penyewa dengan mitra perental terbaik di Indonesia.
                </p>
                <div class="d-flex gap-2 mt-3">
                    @foreach(['instagram','facebook','whatsapp','twitter-x'] as $s)
                    <a href="#" class="social-btn"><i class="bi bi-{{ $s }}"></i></a>
                    @endforeach
                </div>
            </div>

            @foreach([
                'Layanan'  => ['Katalog Mobil','Cara Booking','Harga & Tarif','Area Layanan'],
                'Mitra'    => ['Daftar Mitra','Cara Kerja Mitra','Komisi & Bagi Hasil','Syarat Mitra'],
                'Bantuan'  => ['FAQ','Hubungi Kami','Kebijakan Privasi','Syarat & Ketentuan'],
            ] as $heading => $links)
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold mb-3" style="color:rgba(255,255,255,.82);font-size:.86rem;">{{ $heading }}</h6>
                @foreach($links as $link)
                <a href="#" class="footer-link">{{ $link }}</a>
                @endforeach
            </div>
            @endforeach

            <div class="col-6 col-lg-2">
                <h6 class="fw-bold mb-3" style="color:rgba(255,255,255,.82);font-size:.86rem;">Akun</h6>
                <a href="{{ route('login') }}" class="footer-link">Masuk</a>
                <a href="{{ route('register') }}" class="footer-link">Daftar Pelanggan</a>
                <a href="{{ route('register') }}" class="footer-link">Daftar Mitra</a>
            </div>

        </div>

        <hr style="border-color:rgba(255,255,255,.08);margin:0 0 20px;">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="mb-0" style="color:rgba(255,255,255,.26);font-size:.78rem;">
                © {{ date('Y') }} RodaKita. Semua hak dilindungi undang-undang.
            </p>
            <p class="mb-0" style="color:rgba(255,255,255,.26);font-size:.78rem;">
                Dibuat dengan <span style="color:var(--rk-orange);">❤</span> di Indonesia
            </p>
        </div>

    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="date"]').forEach(el => el.min = today);

    document.getElementById('tglMulai')?.addEventListener('change', function () {
        document.getElementById('tglSelesai').min = this.value;
    });
</script>

</body>
</html>