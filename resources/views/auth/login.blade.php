    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Roda Kita</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <style>
            body {
                background: linear-gradient(135deg, #e0f2fe 0%, #e0e7ff 100%);
            }
            .icon-circle {
                width: 50px;
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    </head>
    <body class="min-vh-100 d-flex align-items-center justify-content-center p-3">

        <div class="card shadow-lg border-0 rounded-4 w-100" style="max-width: 450px;">
            <div class="card-body p-4 p-md-5">
                
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <div class="bg-primary rounded-circle icon-circle shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 14v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                    </div>
                    <h2 class="ms-3 mb-0 fw-bold text-dark">Roda Kita</h2>
                </div>

                <h4 class="text-center fw-semibold mb-4 text-secondary">Masuk ke Akun</h4>

                @if(session('success'))
                    <div class="alert alert-success py-2 text-center" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger py-2 text-center" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Email</label>
                        <input type="email" class="form-control form-control-lg fs-6" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium">Password</label>
                        <input type="password" class="form-control form-control-lg fs-6" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-medium">
                        Masuk
                    </button>
                </form>

                <div class="text-center mt-4">
                    <span class="text-muted">Belum punya akun?</span> 
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar Sekarang</a>
                </div>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>