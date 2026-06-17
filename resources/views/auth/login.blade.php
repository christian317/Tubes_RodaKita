<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Roda Kita</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:   #0f2744;
            --blue:   #1a4a8a;
            --orange: #f97316;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
        }

        /* ── Panel kiri (branding) ── */
        .left-panel {
            width: 45%;
            background: linear-gradient(145deg, var(--navy) 0%, var(--blue) 60%, #1e5fac 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249,115,22,.16) 0%, transparent 70%);
            top: -100px; right: -100px;
            pointer-events: none;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(30,95,172,.45) 0%, transparent 70%);
            bottom: -80px; left: -80px;
            pointer-events: none;
        }

        /* ── Panel kanan (form) ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: #f8fafc;
            overflow-y: auto;
        }
        .form-box {
            width: 100%;
            max-width: 420px;
        }

        /* ── Input styling ── */
        .rk-input {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            font-size: .92rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fff;
            width: 100%;
            color: var(--navy);
            transition: border-color .2s, box-shadow .2s;
        }
        .rk-input:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(249,115,22,.12);
        }
        .rk-input::placeholder { color: #94a3b8; }
        .input-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .95rem;
            pointer-events: none;
        }

        /* ── Submit btn ── */
        .btn-rk {
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .96rem;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 6px 20px rgba(249,115,22,.32);
        }
        .btn-rk:hover {
            background: #ea6b08;
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(249,115,22,.42);
        }
        .btn-rk:active { transform: translateY(0); }

        /* ── Toggle password ── */
        .toggle-pw {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            transition: color .15s;
        }
        .toggle-pw:hover { color: var(--navy); }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            font-size: .8rem;
            margin: 18px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Floating badge ── */
        @keyframes floatBadge {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-8px); }
        }
        .float-a { animation: floatBadge 3.6s ease-in-out infinite; }
        .float-b { animation: floatBadge 3.6s ease-in-out 1.2s infinite; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { background: linear-gradient(145deg, #e0f2fe, #e0e7ff); }
        }
    </style>
</head>
<body>

{{-- ══ PANEL KIRI ══ --}}
<div class="left-panel">
    <div class="position-relative" style="z-index:2;text-align:center;">

        {{-- Logo --}}
        <div class="mb-5">
            <span style="font-family:'Outfit',sans-serif;font-size:2.2rem;font-weight:900;color:#fff;">Roda</span><span style="font-family:'Outfit',sans-serif;font-size:2.2rem;font-weight:900;color:var(--orange);">Kita</span>
            <div style="font-size:.72rem;color:rgba(255,255,255,.45);font-weight:600;text-transform:uppercase;letter-spacing:.12em;margin-top:4px;">Platform Rental Terpercaya</div>
        </div>

        {{-- SVG Mobil --}}
        <svg viewBox="0 0 400 220" xmlns="http://www.w3.org/2000/svg"
             style="width:100%;max-width:360px;filter:drop-shadow(0 20px 40px rgba(0,0,0,.4));margin-bottom:28px;">
            <ellipse cx="200" cy="210" rx="170" ry="11" fill="rgba(0,0,0,.2)"/>
            <rect x="30" y="115" width="340" height="82" rx="14" fill="url(#lg1)"/>
            <path d="M95 115 C102 72 135 55 185 53 L240 53 C286 53 314 72 320 115Z" fill="url(#lg2)"/>
            <path d="M110 110 C114 80 138 67 172 65 L172 110Z" fill="rgba(147,210,255,.72)"/>
            <rect x="180" y="65" width="52" height="45" fill="rgba(147,210,255,.8)"/>
            <path d="M240 65 C268 67 286 82 292 110 L240 110Z" fill="rgba(147,210,255,.72)"/>
            <line x1="176" y1="65" x2="176" y2="110" stroke="rgba(255,255,255,.22)" stroke-width="2"/>
            <line x1="236" y1="65" x2="236" y2="110" stroke="rgba(255,255,255,.22)" stroke-width="2"/>
            <ellipse cx="360" cy="148" rx="14" ry="8" fill="#fbbf24"/>
            <ellipse cx="44"  cy="148" rx="12" ry="7" fill="#ef4444"/>
            <circle cx="105" cy="196" r="32" fill="#0a1929"/>
            <circle cx="105" cy="196" r="21" fill="#1e3058"/>
            <circle cx="105" cy="196" r="10" fill="#475569"/>
            <circle cx="105" cy="196" r="4"  fill="#94a3b8"/>
            <circle cx="295" cy="196" r="32" fill="#0a1929"/>
            <circle cx="295" cy="196" r="21" fill="#1e3058"/>
            <circle cx="295" cy="196" r="10" fill="#475569"/>
            <circle cx="295" cy="196" r="4"  fill="#94a3b8"/>
            <defs>
                <linearGradient id="lg1" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#2563eb"/>
                    <stop offset="100%" stop-color="#0f2744"/>
                </linearGradient>
                <linearGradient id="lg2" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#1e4fa0"/>
                    <stop offset="100%" stop-color="#163a76"/>
                </linearGradient>
            </defs>
        </svg>



        {{-- Tagline --}}
        <p style="color:rgba(255,255,255,.55);font-size:.87rem;line-height:1.75;margin-top:32px;max-width:300px;margin-left:auto;margin-right:auto;">
            Sewa mobil mudah, aman, dan terpercaya langsung dari mitra pilihan Roda Kita.
        </p>

    </div>
</div>

{{-- ══ PANEL KANAN (FORM) ══ --}}
<div class="right-panel">
    <div class="form-box">

        {{-- Header --}}
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width:38px;height:38px;background:var(--navy);">
                    <i class="bi bi-car-front-fill text-white" style="font-size:1.1rem;"></i>
                </div>
                <span style="font-family:'Outfit',sans-serif;font-weight:800;font-size:1.25rem;color:var(--navy);">
                    Roda<span style="color:var(--orange);">Kita</span>
                </span>
            </div>
            <h3 style="font-family:'Outfit',sans-serif;font-weight:800;font-size:1.65rem;color:var(--navy);margin-bottom:4px;">
                Selamat Datang Kembali
            </h3>
            <p class="text-muted mb-0" style="font-size:.9rem;">
                Masuk untuk melanjutkan perjalananmu bersama Roda Kita.
            </p>
        </div>

        {{-- ── Alerts (logika tidak berubah) ── --}}
        @if(session('success'))
        <div class="d-flex align-items-start gap-2 rounded-3 p-3 mb-3"
             style="background:#f0fdf4;border:1px solid #bbf7d0;">
            <i class="bi bi-check-circle-fill mt-1" style="color:#16a34a;flex-shrink:0;"></i>
            <span style="font-size:.87rem;color:#15803d;">{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="d-flex align-items-start gap-2 rounded-3 p-3 mb-3"
             style="background:#fef2f2;border:1px solid #fecaca;">
            <i class="bi bi-exclamation-circle-fill mt-1" style="color:#dc2626;flex-shrink:0;"></i>
            <span style="font-size:.87rem;color:#b91c1c;">{{ $errors->first() }}</span>
        </div>
        @endif

        {{-- ── Form (logika tidak berubah) ── --}}
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold mb-1" style="font-size:.84rem;color:var(--navy);">
                    Alamat Email
                </label>
                <div class="position-relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email"
                           class="rk-input"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           required>
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label fw-semibold mb-0" style="font-size:.84rem;color:var(--navy);">
                        Password
                    </label>
                    <a href="#" style="font-size:.78rem;color:var(--orange);text-decoration:none;font-weight:600;">
                        Lupa Password?
                    </a>
                </div>
                <div class="position-relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password"
                           class="rk-input"
                           id="password"
                           name="password"
                           placeholder="••••••••"
                           required
                           style="padding-right:40px;">
                    <i class="bi bi-eye toggle-pw" id="togglePw"></i>
                </div>
            </div>

            <button type="submit" class="btn-rk">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
            </button>
        </form>

        <div class="divider">atau</div>

        <div class="text-center" style="font-size:.88rem;">
            <span class="text-muted">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="fw-bold text-decoration-none ms-1"
               style="color:var(--orange);">
                Daftar Sekarang →
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    document.getElementById('togglePw')?.addEventListener('click', function () {
        const pw = document.getElementById('password');
        const isText = pw.type === 'text';
        pw.type = isText ? 'password' : 'text';
        this.className = isText ? 'bi bi-eye toggle-pw' : 'bi bi-eye-slash toggle-pw';
    });
</script>
</body>
</html>