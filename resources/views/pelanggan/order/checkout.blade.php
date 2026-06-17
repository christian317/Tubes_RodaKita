@extends('layouts.pelanggan')

@section('title', 'Checkout Sewa - ' . $mobil->model)

@push('styles')
    <style>
        .custom-radio-label {
            border: 2px solid #e2e8f0;
            background-color: #ffffff;
            color: #475569;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
    </style>
@endpush

@section('content')

    <div class="container py-4 py-lg-5">

        <div class="mb-4">
            <a href="{{ route('pelanggan.mobil.detail_mobil', $mobil->id) }}"
                class="btn btn-white border rounded-3 px-3 py-2 text-secondary shadow-sm fw-medium">
                <i class="bi bi-arrow-left me-2"></i>Batal & Kembali
            </a>
        </div>

        <div class="row justify-content-center g-4">

            {{-- KIRI: RINGKASAN KENDARAAN --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-4">Ringkasan Kendaraan</h5>

                        <div class="bg-light rounded-3 overflow-hidden mb-3" style="height: 150px;">
                            @if ($mobil->gambar)
                                <img src="{{ asset('storage/' . $mobil->gambar) }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 d-flex justify-content-center align-items-center text-muted">
                                    <i class="bi bi-car-front fs-1"></i>
                                </div>
                            @endif
                        </div>

                        <div class="fw-bold text-dark fs-5">{{ $mobil->model }}</div>
                        <div class="text-muted small mb-3">
                            {{ $mobil->brand->nama_brand ?? '-' }} • {{ $mobil->tahun }}
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="text-muted small fw-semibold">Harga Sewa Dasar</span>
                            <span class="fw-bold text-dark" id="hargaPerHari" data-harga="{{ $mobil->harga_sewa }}">
                                Rp {{ number_format($mobil->harga_sewa, 0, ',', '.') }} <span
                                    class="fw-normal text-muted small">/ hari</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN: FORM & PEMBAYARAN --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">

                        {{-- FASE 1: FORM CHECKOUT --}}
                        @if (!isset($snapToken))
                            <h4 class="fw-bold mb-1 text-dark">Informasi Penyewaan</h4>
                            <p class="text-muted small mb-4">Silakan tinjau detail waktu dan layanan Anda.</p>

                            @if (session('error'))
                                <div class="alert alert-danger rounded-3 py-2 px-3 small mb-4 shadow-sm border-0">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger rounded-3 py-2 px-3 small mb-4 shadow-sm border-0">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- WAJIB TAMBAHKAN ENCTYPE UNTUK UPLOAD FILE --}}
                            <form action="{{ route('pelanggan.order.checkout.proses') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_mobil" value="{{ $mobil->id }}">

                                <input type="hidden" name="tanggal_mulai" id="hiddenTglMulai" value="{{ $tgl_mulai }}">
                                <input type="hidden" name="tanggal_selesai" id="hiddenTglSelesai"
                                    value="{{ $tgl_selesai }}">
                                <input type="hidden" name="waktu_mulai" id="jamMulai" value="08:00">
                                <input type="hidden" name="waktu_selesai" id="jamSelesai" value="20:00">

                                <div class="mb-4 summary-box p-3 rounded-3">
                                    <div class="small text-muted mb-2 fw-semibold"><i class="bi bi-calendar-check me-1"></i>
                                        Rincian Jadwal Sewa & Jam Operasional</div>
                                    <div class="row g-2 text-dark">
                                        <div class="col-6 border-end pe-2">
                                            <span class="text-muted small d-block">Pengambilan:</span>
                                            <strong
                                                class="d-block text-dark">{{ \Carbon\Carbon::parse($tgl_mulai)->format('d M Y') }}</strong>
                                            <small class="text-secondary fw-medium"><i class="bi bi-clock me-1"></i>08:00
                                                WIB</small>
                                        </div>
                                        <div class="col-6 ps-3">
                                            <span class="text-muted small d-block">Pengembalian:</span>
                                            <strong
                                                class="d-block text-dark">{{ \Carbon\Carbon::parse($tgl_selesai)->format('d M Y') }}</strong>
                                            <small class="text-secondary fw-medium"><i class="bi bi-clock me-1"></i>20:00
                                                WIB</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- OPSI LAYANAN --}}
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-dark mb-2">Pilih Tipe Layanan <span
                                            class="text-danger">*</span></label>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="tipe_layanan" id="lepasKunci"
                                                value="lepas_kunci" autocomplete="off" checked onchange="hitungTotal()">
                                            <label
                                                class="btn btn-outline-primary w-100 text-start p-3 h-100 rounded-3 d-flex flex-column"
                                                for="lepasKunci">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="bi bi-key-fill fs-5 me-2"></i>
                                                    <span class="fw-bold">Lepas Kunci</span>
                                                </div>
                                                <small class="d-block mt-2 opacity-75"
                                                    style="font-size: 0.8rem; line-height: 1.4;">Bawa mobil sendiri tanpa
                                                    biaya tambahan.</small>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="tipe_layanan" id="denganSupir"
                                                value="dengan_supir" autocomplete="off" onchange="hitungTotal()">
                                            <label
                                                class="btn btn-outline-primary w-100 text-start p-3 h-100 rounded-3 d-flex flex-column"
                                                for="denganSupir">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="bi bi-person-badge-fill fs-5 me-2"></i>
                                                    <span class="fw-bold">Dengan Supir</span>
                                                </div>
                                                <small class="d-block mt-2 opacity-75"
                                                    style="font-size: 0.8rem; line-height: 1.4;">+ Rp 150.000 / hari.
                                                    Santai di perjalanan.</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- UPLOAD KTP (Akan disembunyikan via JS jika pilih Dengan Supir) --}}
                                <div class="mb-4" id="formUploadKtp">
                                    <label class="form-label small fw-bold text-dark mb-2">Upload Foto KTP <span
                                            class="text-danger">*</span></label>
                                    <div class="p-3 border rounded-3 bg-light">
                                        <input class="form-control form-control-sm border-0 shadow-none bg-white mb-2"
                                            type="file" name="foto_ktp" id="inputKtp"
                                            accept="image/png, image/jpeg, image/jpg" required>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;"><i
                                                class="bi bi-info-circle me-1"></i>Dokumen ini diperlukan untuk verifikasi
                                            identitas layanan Lepas Kunci. Maks 2MB.</small>
                                    </div>
                                </div>

                                {{-- KODE PROMO SYSTEM --}}
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-dark mb-2">Kode Promo / Voucher</label>
                                    <div class="input-group">
                                        <input type="text" name="kode_promo" id="inputKodePromo" class="form-control text-uppercase" placeholder="Masukkan kode promo (e.g. RODAKITA10)">
                                        <button type="button" class="btn btn-dark fw-bold px-3" id="btnApplyPromo">Gunakan</button>
                                    </div>
                                    <div id="promoFeedback" class="small mt-2" style="display: none;"></div>
                                    <input type="hidden" name="applied_id_promo" id="appliedIdPromo" value="">
                                </div>

                                {{-- KOTAK TOTAL --}}
                                <div class="summary-box p-4 rounded-4 mb-4">
                                    <div class="d-flex justify-content-between mb-2 small text-dark">
                                        <span>Durasi Sewa</span>
                                        <span><b id="durasiTeks">0</b> Hari</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3 small text-dark" id="rincianSupir"
                                        style="display: none !important;">
                                        <span>Biaya Supir (<span id="durasiSupirTeks">0</span>x Hari)</span>
                                        <span class="fw-bold" id="biayaSupirTeks">Rp 0</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2 small text-success" id="rincianDiskon"
                                        style="display: none !important;">
                                        <span>Potongan Promo (<span id="promoCodeLabel"></span>)</span>
                                        <span class="fw-bold" id="potonganHargaTeks">- Rp 0</span>
                                    </div>

                                    <div class="d-flex justify-content-between border-top pt-3 mt-2">
                                        <span class="fw-bold text-dark fs-5">Total Bayar</span>
                                        <span class="fw-bold text-primary fs-4" id="totalHargaTeks">Rp 0</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm"
                                    id="btnLanjut" disabled>
                                    <span id="teksTombol">Kirim Pengajuan Sewa</span> <i
                                        class="bi bi-chevron-right ms-1"></i>
                                </button>
                            </form>

                        {{-- FASE 2: PEMBAYARAN MIDTRANS --}}
                        @else
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-dark">Selesaikan Pembayaran</h4>
                                <p class="text-muted small">
                                    Status Pesanan: <span class="badge bg-warning text-dark px-2 py-1">Menunggu Approval Admin</span>
                                </p>
                            </div>

                            <div class="summary-box p-4 rounded-4 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Layanan</span>
                                    <span class="fw-bold text-dark text-uppercase"
                                        style="font-size: 0.85rem;">{{ str_replace('_', ' ', $booking->tipe_layanan) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Durasi</span>
                                    <span
                                        class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($booking->tanggal_selesai)) ?: 1 }}
                                        Hari</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-3 mt-3">
                                    <span class="text-dark fw-bold">Total Tagihan</span>
                                    <span class="fw-bold text-success fs-5">
                                        Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            {{-- PERBAIKAN DI SINI: Tombol muncul jika statusnya 'menunggu_approval' --}}
                            @if($booking->status == 'menunggu_approval')
                                <button id="pay-button" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm">
                                    <i class="bi bi-credit-card-fill me-2"></i> Bayar Sekarang via Midtrans
                                </button>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let appliedPromo = null;
        let potonganHarga = 0;

        function hitungTotalSebelumPromo() {
            const tglMulai = document.getElementById('hiddenTglMulai').value;
            const tglSelesai = document.getElementById('hiddenTglSelesai').value;
            const jamMulai = document.getElementById('jamMulai').value;
            const jamSelesai = document.getElementById('jamSelesai').value;

            const pakaiSupir = document.getElementById('denganSupir').checked;

            if (tglMulai && jamMulai && tglSelesai && jamSelesai) {
                const startString = tglMulai + "T" + jamMulai + ":00";
                const endString = tglSelesai + "T" + jamSelesai + ":00";
                const d1 = new Date(startString);
                const d2 = new Date(endString);

                if (d2 > d1) {
                    const diffTime = Math.abs(d2 - d1);
                    const diffHours = diffTime / (1000 * 60 * 60);

                    let diffDays = Math.ceil(diffHours / 24);
                    if (diffDays === 0) diffDays = 1;

                    const hargaSewaMobil = parseInt(document.getElementById('hargaPerHari').dataset.harga);

                    let totalBiayaSupir = 0;
                    if (pakaiSupir) {
                        totalBiayaSupir = 150000 * diffDays;
                    }
                    return (diffDays * hargaSewaMobil) + totalBiayaSupir;
                }
            }
            return 0;
        }

        function hitungTotal() {
            const tglMulai = document.getElementById('hiddenTglMulai').value;
            const tglSelesai = document.getElementById('hiddenTglSelesai').value;
            const jamMulai = document.getElementById('jamMulai').value;
            const jamSelesai = document.getElementById('jamSelesai').value;
            const btn = document.getElementById('btnLanjut');

            const isLepasKunci = document.getElementById('lepasKunci').checked;
            const pakaiSupir = document.getElementById('denganSupir').checked;

            // Elemen Dinamis
            const rincianSupirDiv = document.getElementById('rincianSupir');
            const formUploadKtp = document.getElementById('formUploadKtp');
            const inputKtp = document.getElementById('inputKtp');
            const teksTombol = document.getElementById('teksTombol');

            if (isLepasKunci) {
                // Munculkan kotak upload dan wajibkan isiannya
                formUploadKtp.style.display = 'block';
                inputKtp.setAttribute('required', 'required');
                inputKtp.removeAttribute('disabled');

                // PERBAIKAN: Ubah kembali teksnya karena tetap harus bayar
                teksTombol.innerText = 'Lanjut Pembayaran (Midtrans)'; 
            } else if (pakaiSupir) {
                // Sembunyikan kotak upload dan matikan inputnya
                formUploadKtp.style.display = 'none';
                inputKtp.removeAttribute('required');
                inputKtp.setAttribute('disabled', 'true');

                teksTombol.innerText = 'Lanjut Pembayaran (Midtrans)';
            }

            if (tglMulai && jamMulai && tglSelesai && jamSelesai) {
                const startString = tglMulai + "T" + jamMulai + ":00";
                const endString = tglSelesai + "T" + jamSelesai + ":00";
                const d1 = new Date(startString);
                const d2 = new Date(endString);

                if (d2 > d1) {
                    const diffTime = Math.abs(d2 - d1);
                    const diffHours = diffTime / (1000 * 60 * 60);

                    let diffDays = Math.ceil(diffHours / 24);
                    if (diffDays === 0) diffDays = 1;

                    const hargaSewaMobil = parseInt(document.getElementById('hargaPerHari').dataset.harga);

                    let totalBiayaSupir = 0;
                    if (pakaiSupir) {
                        totalBiayaSupir = 150000 * diffDays;
                        rincianSupirDiv.style.setProperty('display', 'flex', 'important');
                        document.getElementById('durasiSupirTeks').innerText = diffDays;
                        document.getElementById('biayaSupirTeks').innerText = '+ Rp ' + totalBiayaSupir.toLocaleString(
                            'id-ID');
                    } else {
                        rincianSupirDiv.style.setProperty('display', 'none', 'important');
                    }

                    const subtotal = (diffDays * hargaSewaMobil) + totalBiayaSupir;
                    const rincianDiskonDiv = document.getElementById('rincianDiskon');
                    let totalKeseluruhan = subtotal;

                    if (appliedPromo) {
                        rincianDiskonDiv.style.setProperty('display', 'flex', 'important');
                        document.getElementById('promoCodeLabel').innerText = appliedPromo.code;
                        
                        let currentPotongan = potonganHarga;
                        if (currentPotongan > subtotal) {
                            currentPotongan = subtotal;
                        }
                        document.getElementById('potonganHargaTeks').innerText = '- Rp ' + currentPotongan.toLocaleString('id-ID');
                        totalKeseluruhan = subtotal - currentPotongan;
                    } else {
                        rincianDiskonDiv.style.setProperty('display', 'none', 'important');
                    }

                    document.getElementById('durasiTeks').innerText = diffDays;
                    document.getElementById('totalHargaTeks').innerText = 'Rp ' + totalKeseluruhan.toLocaleString('id-ID');

                    btn.removeAttribute('disabled');
                } else {
                    btn.setAttribute('disabled', true);
                }
            }
        }

        function resetPromo() {
            if (appliedPromo) {
                appliedPromo = null;
                potonganHarga = 0;
                document.getElementById('appliedIdPromo').value = '';
                document.getElementById('inputKodePromo').value = '';
                const feedback = document.getElementById('promoFeedback');
                feedback.style.display = 'block';
                feedback.className = 'small mt-2 text-warning';
                feedback.innerHTML = '<i class="bi bi-info-circle me-1"></i>Layanan diubah. Silakan masukkan kembali kode promo jika ada.';
                hitungTotal();
            }
        }

        window.onload = function() {
            hitungTotal();
            
            document.getElementById('lepasKunci').addEventListener('change', resetPromo);
            document.getElementById('denganSupir').addEventListener('change', resetPromo);

            document.getElementById('btnApplyPromo').addEventListener('click', function() {
                const code = document.getElementById('inputKodePromo').value.trim();
                const feedback = document.getElementById('promoFeedback');
                const totalSebelumPromo = hitungTotalSebelumPromo();

                if (!code) {
                    feedback.style.display = 'block';
                    feedback.className = 'small mt-2 text-danger';
                    feedback.innerHTML = '<i class="bi bi-x-circle me-1"></i>Masukkan kode promo terlebih dahulu.';
                    return;
                }

                fetch("{{ route('pelanggan.promo.check') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        kode_promo: code,
                        total_bayar: totalSebelumPromo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        appliedPromo = {
                            id: data.id_promo,
                            code: code,
                            potongan: data.potongan
                        };
                        potonganHarga = data.potongan;
                        feedback.style.display = 'block';
                        feedback.className = 'small mt-2 text-success fw-semibold';
                        feedback.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + data.message;
                        
                        document.getElementById('appliedIdPromo').value = data.id_promo;
                        hitungTotal();
                    } else {
                        appliedPromo = null;
                        potonganHarga = 0;
                        feedback.style.display = 'block';
                        feedback.className = 'small mt-2 text-danger';
                        feedback.innerHTML = '<i class="bi bi-x-circle me-1"></i>' + data.message;
                        
                        document.getElementById('appliedIdPromo').value = '';
                        hitungTotal();
                    }
                })
                .catch(err => {
                    console.error(err);
                    feedback.style.display = 'block';
                    feedback.className = 'small mt-2 text-danger';
                    feedback.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Terjadi kesalahan sistem.';
                });
            });
        };
    </script>

    @if (isset($snapToken))
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var payButton = document.getElementById('pay-button');
                if (payButton) {
                    payButton.onclick = function() {
                        if (typeof window.snap === 'undefined') {
                            alert(
                                'Error: Script Midtrans gagal dimuat. Pastikan koneksi internet lancar dan konfigurasi benar.');
                            return;
                        }

                        window.snap.pay('{{ $snapToken }}', {
                            onSuccess: function(result) {
                                alert("Pembayaran berhasil!");
                                window.location.href = "{{ route('pelanggan.dashboard') }}";
                            },
                            onPending: function(result) {
                                alert("Menunggu pembayaran Anda...");
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal!");
                            },
                            onClose: function() {
                                alert("Anda menutup popup sebelum pembayaran selesai.");
                            }
                        });
                    };
                }
            });
        </script>
    @endif
@endsection
