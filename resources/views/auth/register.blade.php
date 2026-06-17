<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Roda Kita</title>
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
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
        }

        /* ── Panel kiri (branding) ── */
        .left-panel {
            width: 40%;
            background: linear-gradient(145deg, var(--navy) 0%, var(--blue) 60%, #1e5fac 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 36px;
            position: relative;
            overflow: hidden;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249,115,22,.15) 0%, transparent 70%);
            top: -100px; right: -100px;
            pointer-events: none;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(30,95,172,.42) 0%, transparent 70%);
            bottom: -80px; left: -80px;
            pointer-events: none;
        }

        /* ── Panel kanan (form) ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 28px;
            background: #f8fafc;
            overflow-y: auto;
            min-height: 100vh;
        }
        .form-box {
            width: 100%;
            max-width: 440px;
            padding-bottom: 40px;
        }

        /* ── Input ── */
        .rk-input {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            font-size: .9rem;
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
        .rk-textarea {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            font-size: .9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fff;
            width: 100%;
            color: var(--navy);
            transition: border-color .2s, box-shadow .2s;
            resize: none;
        }
        .rk-textarea:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(249,115,22,.12);
        }
        .rk-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            font-size: .9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fff;
            width: 100%;
            color: var(--navy);
            transition: border-color .2s, box-shadow .2s;
            appearance: auto;
            cursor: pointer;
        }
        .rk-select:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(249,115,22,.12);
        }
        .input-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .92rem;
            pointer-events: none;
        }
        .textarea-icon {
            position: absolute;
            left: 13px;
            top: 13px;
            color: #94a3b8;
            font-size: .92rem;
            pointer-events: none;
        }

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

        /* ── Role card ── */
        .role-option { display: none; }
        .role-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            background: #fff;
        }
        .role-option:checked + .role-card {
            border-color: var(--orange);
            background: #fff7ed;
        }
        .role-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* ── Progress steps ── */
        .step-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #e2e8f0;
            display: inline-block;
            transition: background .2s, width .2s;
        }
        .step-dot.active { background: var(--orange); width: 22px; border-radius: 4px; }

        /* ── Left panel features ── */
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            margin-bottom: 10px;
        }

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
    <div class="position-relative text-center" style="z-index:2;width:100%;">

        {{-- Logo --}}
        <div class="mb-4">
            <span style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:900;color:#fff;">Roda</span><span style="font-family:'Outfit',sans-serif;font-size:2rem;font-weight:900;color:var(--orange);">Kita</span>
            <div style="font-size:.7rem;color:rgba(255,255,255,.42);font-weight:600;text-transform:uppercase;letter-spacing:.12em;margin-top:3px;">Platform Rental Terpercaya</div>
        </div>

        {{-- Heading --}}
        <h3 style="font-family:'Outfit',sans-serif;font-weight:800;color:#fff;font-size:1.45rem;line-height:1.3;margin-bottom:8px;">
            Bergabunglah dengan<br>10.000+ Pelanggan Puas
        </h3>
        <p style="color:rgba(255,255,255,.52);font-size:.85rem;line-height:1.75;margin-bottom:28px;">
            Daftar sekarang dan nikmati kemudahan sewa mobil online di seluruh Indonesia.
        </p>

        {{-- Feature list --}}
        @foreach([
            ['bi-shield-check-fill','#fff7ed','var(--orange)','Mitra Terverifikasi','Armada terawat dari mitra terpercaya'],
            ['bi-credit-card-2-front-fill','#e8f4fd','#60a5fa','Pembayaran Aman','Transaksi via Midtrans, 100% terjamin'],
            ['bi-star-fill','#faf5ff','#c084fc','Rating Transparan','Ulasan nyata dari penyewa sesungguhnya'],
        ] as [$icon,$bg,$color,$title,$desc])
        <div class="feature-item text-start">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:38px;height:38px;background:{{ $bg }};color:{{ $color }};font-size:1.05rem;">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div>
                <div style="font-size:.85rem;font-weight:700;color:#fff;line-height:1.2;">{{ $title }}</div>
                <div style="font-size:.76rem;color:rgba(255,255,255,.5);margin-top:2px;">{{ $desc }}</div>
            </div>
        </div>
        @endforeach

    </div>
</div>


{{-- ══ PANEL KANAN (FORM) ══ --}}
<div class="right-panel">
    <div class="form-box">

        {{-- Header --}}
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width:36px;height:36px;background:var(--navy);">
                    <i class="bi bi-car-front-fill text-white" style="font-size:1rem;"></i>
                </div>
                <span style="font-family:'Outfit',sans-serif;font-weight:800;font-size:1.15rem;color:var(--navy);">
                    Roda<span style="color:var(--orange);">Kita</span>
                </span>
            </div>
            <h3 style="font-family:'Outfit',sans-serif;font-weight:800;font-size:1.55rem;color:var(--navy);margin-bottom:4px;">
                Buat Akun Baru
            </h3>
            <p class="text-muted mb-0" style="font-size:.88rem;">
                Isi data di bawah untuk mulai menyewa mobil favoritmu.
            </p>
        </div>

        {{-- ── Errors (logika tidak berubah) ── --}}
        @if($errors->any())
        <div class="rounded-3 p-3 mb-4" style="background:#fef2f2;border:1.5px solid #fecaca;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-exclamation-circle-fill" style="color:#dc2626;"></i>
                <span style="font-size:.85rem;font-weight:700;color:#b91c1c;">Terdapat kesalahan:</span>
            </div>
            <ul class="mb-0 ps-4" style="font-size:.83rem;color:#b91c1c;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ── Form (logika tidak berubah) ── --}}
        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <label for="nama" class="form-label fw-semibold mb-1" style="font-size:.83rem;color:var(--navy);">
                    Nama Lengkap
                </label>
                <div class="position-relative">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text"
                           class="rk-input"
                           id="nama"
                           name="nama"
                           value="{{ old('nama') }}"
                           placeholder="Nama lengkap sesuai KTP"
                           required>
                </div>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold mb-1" style="font-size:.83rem;color:var(--navy);">
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

            {{-- No. Telepon --}}
            <div class="mb-3">
                <label for="no_telepon" class="form-label fw-semibold mb-1" style="font-size:.83rem;color:var(--navy);">
                    Nomor Telepon
                </label>
                <div class="position-relative">
                    <i class="bi bi-telephone input-icon"></i>
                    <input type="text"
                           class="rk-input"
                           id="no_telepon"
                           name="no_telepon"
                           value="{{ old('no_telepon') }}"
                           placeholder="08123456789"
                           required>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="mb-3">
                <label for="alamat" class="form-label fw-semibold mb-1" style="font-size:.83rem;color:var(--navy);">
                    Alamat
                </label>
                <div class="position-relative">
                    <i class="bi bi-geo-alt textarea-icon"></i>
                    <textarea class="rk-textarea"
                              id="alamat"
                              name="alamat"
                              rows="2"
                              placeholder="Jl. Contoh No. 123, Kota..."
                              required>{{ old('alamat') }}</textarea>
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold mb-1" style="font-size:.83rem;color:var(--navy);">
                    Password
                </label>
                <div class="position-relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password"
                           class="rk-input"
                           id="password"
                           name="password"
                           placeholder="Minimal 6 karakter"
                           minlength="6"
                           required
                           style="padding-right:40px;">
                    <i class="bi bi-eye toggle-pw" id="togglePw"></i>
                </div>
            </div>
            <button type="submit" class="btn-rk">
                <i class="bi bi-person-plus-fill me-2"></i>Buat Akun Sekarang
            </button>

        </form>

        <div class="text-center mt-4" style="font-size:.87rem;">
            <span class="text-muted">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="fw-bold text-decoration-none ms-1"
               style="color:var(--orange);">
                Masuk di sini →
            </a>
        </div>

        <p class="text-center text-muted mt-4 mb-0" style="font-size:.75rem;line-height:1.6;">
            Dengan mendaftar, Anda menyetujui
            <a href="#" style="color:var(--orange);text-decoration:none;">Syarat & Ketentuan</a>
            serta
            <a href="#" style="color:var(--orange);text-decoration:none;">Kebijakan Privasi</a>
            Roda Kita.
        </p>

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