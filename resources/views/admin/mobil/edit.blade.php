@extends('layouts.admin')

@section('title', 'Edit Mobil - Admin')
@section('page_title', 'Edit Mobil')
@section('breadcrumb', 'Edit data mobil')

@section('content')
    <div class="py-3 mb-4" style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.mobil.index') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Formulir Edit Mobil
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Perbarui data unit mobil milik mitra</p>
                </div>
            </div>

            <form action="{{ route('admin.mobil.update', $mobil->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body px-4 py-4 bg-light">
                    
                    {{-- Section: Info Mobil --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border">
                        <p class="small fw-bold text-uppercase text-muted mb-3 letter-spacing-1">
                            <i class="bi bi-info-circle me-1"></i> Informasi Mobil
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Brand (Merk) <span class="text-danger">*</span></label>
                                <select name="id_brand" class="form-select rounded-3" required>
                                    <option value="" disabled>Pilih Merk...</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}" {{ $mobil->id_brand == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama_brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Model / Nama Mobil <span class="text-danger">*</span></label>
                                <input type="text" name="model" class="form-control rounded-3" value="{{ $mobil->model }}" placeholder="cth. Avanza Veloz" required/>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-dark">Kategori <span class="text-danger">*</span></label>
                                <select name="id_kategori" class="form-select rounded-3" required>
                                    <option value="" disabled>Pilih Kategori...</option>
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}" {{ $mobil->id_kategori == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-dark">Transmisi <span class="text-danger">*</span></label>
                                <select name="transmisi" class="form-select rounded-3" required>
                                    <option value="Otomatis" {{ $mobil->transmisi == 'Otomatis' ? 'selected' : '' }}>Otomatis</option>
                                    <option value="Manual" {{ $mobil->transmisi == 'Manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-dark">Penumpang <span class="text-danger">*</span></label>
                                <input type="number" name="kapasitas_penumpang" class="form-control rounded-3 text-center" value="{{ $mobil->kapasitas_penumpang }}" min="2" max="15" required/>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold text-dark">Tahun <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" class="form-control rounded-3 text-center" value="{{ $mobil->tahun }}" required/>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Kepemilikan & Harga --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border">
                        <p class="small fw-bold text-uppercase text-muted mb-3 letter-spacing-1">
                            <i class="bi bi-person-badge me-1"></i> Kepemilikan & Harga
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Plat Nomor <span class="text-danger">*</span></label>
                                <input type="text" name="plat_nomer" class="form-control rounded-3 font-monospace text-uppercase" value="{{ $mobil->plat_nomer }}" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Pemilik (Mitra) <span class="text-danger">*</span></label>
                                <select name="id_pemilik_mobil" class="form-select rounded-3" required>
                                    <option value="" disabled>Pilih Mitra...</option>
                                    @foreach($pemiliks as $p)
                                        <option value="{{ $p->id }}" {{ $mobil->id_pemilik_mobil == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label small fw-semibold text-dark">Status Ketersediaan <span class="text-danger">*</span></label>
                                <select name="status_mobil" class="form-select rounded-3" required>
                                    <option value="tersedia" {{ $mobil->status_mobil == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="booked" {{ $mobil->status_mobil == 'booked' ? 'selected' : '' }}>Booked</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Harga Sewa per Hari <span class="text-danger">*</span></label>
                                <div class="input-group rounded-3">
                                    <span class="input-group-text bg-light border-end-0 text-muted small">Rp</span>
                                    <input type="number" name="harga_sewa" class="form-control border-start-0 rounded-end-3" value="{{ intval($mobil->harga_sewa) }}" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Lokasi Penjemputan --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border">
                        <p class="small fw-bold text-uppercase text-muted mb-3 letter-spacing-1">
                            <i class="bi bi-geo-alt-fill me-1"></i> Lokasi Penjemputan Mobil
                        </p>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">Alamat Penjemputan Detail <span
                                        class="text-danger">*</span></label>
                                <textarea name="alamat_jemput" class="form-control rounded-3" rows="2"
                                    placeholder="cth. Jalan Raya Surabaya No. 45, dekat Stasiun" required>{{ $mobil->alamat_jemput }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Latitude <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="latitude" id="latitude" class="form-control rounded-3 bg-light"
                                    value="{{ $mobil->latitude }}" placeholder="Pilih lokasi di peta" readonly required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Longitude <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="longitude" id="longitude" class="form-control rounded-3 bg-light"
                                    value="{{ $mobil->longitude }}" placeholder="Pilih lokasi di peta" readonly required />
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark mb-2">Pilih Titik Lokasi di Peta (Geser Penanda)</label>
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Media --}}
                    <div class="bg-white p-4 rounded-4 shadow-sm border">
                        <p class="small fw-bold text-uppercase text-muted mb-3 letter-spacing-1">
                            <i class="bi bi-images me-1"></i> Media & Tampilan
                        </p>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">Foto Utama Mobil</label>
                                
                                {{-- Preview Foto Lama --}}
                                @if($mobil->gambar)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $mobil->gambar) }}" alt="Foto Mobil" class="rounded-3 border object-fit-cover shadow-sm" style="height: 120px; width: 200px;">
                                        <div class="text-muted small mt-2"><i class="bi bi-info-circle me-1"></i>Biarkan kosong jika tidak ingin mengubah foto saat ini.</div>
                                    </div>
                                @endif
                                
                                {{-- Input File (Tidak required karena opsional saat edit) --}}
                                <input type="file" name="gambar" class="form-control rounded-3" accept="image/*">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control rounded-3" rows="3">{{ $mobil->deskripsi }}</textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="form-check form-switch fs-6 d-flex align-items-center gap-3">
                                    <input class="form-check-input mt-0" style="width:2.5em;height:1.3em;" type="checkbox" name="status_katalog" id="checkTampil" {{ $mobil->status_katalog == 1 ? 'checked' : '' }}/>
                                    <div>
                                        <label class="form-check-label fw-semibold small text-dark mb-0" for="checkTampil" style="cursor:pointer;">Tampilkan di katalog pelanggan</label>
                                        <div class="text-muted small" style="font-size:12px;">Mobil akan langsung muncul di halaman sewa</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-white border-top px-4 py-3 text-end rounded-bottom-4">
                    <a href="{{ route('admin.mobil.index') }}" class="btn btn-light fw-semibold rounded-3 px-4 me-2 border">Batal</a>
                    <button type="submit" class="btn btn-primary fw-semibold rounded-3 px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            height: 350px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var defaultLat = {{ $mobil->latitude ?? -6.200000 }};
            var defaultLng = {{ $mobil->longitude ?? 106.816666 }};
            var hasCoordinates = {{ $mobil->latitude ? 'true' : 'false' }};

            var map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            function updateCoordinates(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(8);
                document.getElementById('longitude').value = lng.toFixed(8);
            }

            marker.on('dragend', function (e) {
                var position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });

            // If we don't have coordinates saved yet, try to geolocate
            if (!hasCoordinates && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    map.setView([lat, lng], 13);
                    marker.setLatLng([lat, lng]);
                    updateCoordinates(lat, lng);
                }, function(err) {
                    console.log("Geolocation error: ", err.message);
                });
            }
        });
    </script>
@endpush