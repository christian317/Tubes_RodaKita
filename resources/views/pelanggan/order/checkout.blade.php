@extends('layouts.pelanggan')

@section('title', 'Checkout Sewa - ' . $mobil->model)

@push('scripts')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endpush

@section('content')


<div class="container py-4 py-lg-5">
    
    <div class="mb-4">
        <a href="{{ route('pelanggan.mobil.detail_mobil', $mobil->id) }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm fw-medium">
            <i class="bi bi-arrow-left me-2"></i>Batal & Kembali
        </a>
    </div>
    
    <div class="row justify-content-center g-4">
        
        {{-- KIRI --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">Ringkasan Kendaraan</h5>

                    <div class="bg-light rounded-3 overflow-hidden mb-3" style="height: 150px;">
                        @if($mobil->gambar)
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
                        <span class="text-muted small fw-semibold">Harga per Hari</span>
                        <span class="fw-bold text-dark" id="hargaPerHari" data-harga="{{ $mobil->harga_sewa }}">
                            Rp {{ number_format($mobil->harga_sewa, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    
                    {{-- FORM --}}
                    @if(!isset($snapToken))
                        <h4 class="fw-bold mb-1">Informasi Penyewaan</h4>
                        <p class="text-muted small mb-4">Tentukan tanggal penyewaan kendaraan.</p>

                        <form action="{{ route('pelanggan.order.checkout.proses') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_mobil" value="{{ $mobil->id }}">
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="waktuMulai" class="form-control form-control-lg" required onchange="hitungTotal()">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="waktuSelesai" class="form-control form-control-lg" required onchange="hitungTotal()">
                                </div>
                            </div>

                            <div class="bg-primary bg-opacity-10 p-4 rounded-4 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Durasi</span>
                                    <span><b id="durasiTeks">0</b> Hari</span>
                                </div>

                                <div class="d-flex justify-content-between border-top pt-3">
                                    <span class="fw-bold">Total</span>
                                    <span class="fw-bold text-primary" id="totalHargaTeks">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="btnLanjut" disabled>
                                Lanjut Pembayaran
                            </button>
                        </form>

                    {{-- PEMBAYARAN --}}
                    @else
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Selesaikan Pembayaran</h4>
                            <p class="text-muted small">
                                Status: <b>{{ ucfirst($booking->status) }}</b>
                            </p>
                        </div>

                        <div class="bg-light p-4 rounded-4 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total</span>
                                <span class="fw-bold">
                                    Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <button id="pay-button" class="btn btn-success w-100">
                            Bayar Sekarang
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function hitungTotal() {
    const start = document.getElementById('waktuMulai').value;
    const end = document.getElementById('waktuSelesai').value;
    const btn = document.getElementById('btnLanjut');

    if (start && end) {
        const d1 = new Date(start);
        const d2 = new Date(end);

        let diff = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
        if (diff === 0) diff = 1;

        if (d2 >= d1) {
            const harga = document.getElementById('hargaPerHari').dataset.harga;
            const total = diff * harga;

            document.getElementById('durasiTeks').innerText = diff;
            document.getElementById('totalHargaTeks').innerText = 'Rp ' + total.toLocaleString('id-ID');

            btn.removeAttribute('disabled');
        } else {
            btn.setAttribute('disabled', true);
        }
    }
}

@if(isset($snapToken))
document.getElementById('pay-button').onclick = function () {
    window.snap.pay('{{ $snapToken }}', {
        onSuccess: function(){
            alert("Pembayaran berhasil!");
            window.location.href = "{{ route('pelanggan.dashboard') }}";
        },
        onPending: function(){
            alert("Menunggu pembayaran...");
        },
        onError: function(){
            alert("Pembayaran gagal!");
        }
    });
}
@endif
</script>
@endsection