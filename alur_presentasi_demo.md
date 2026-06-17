# Naskah Alur Demo Presentasi (RodaKita Demo Storyline)

Dokumen ini disusun sebagai **alur cerita (storyline)** untuk demonstrasi produk saat presentasi. Alur ini menggabungkan semua fitur baru ke dalam satu cerita pengguna yang mengalir secara logis.

---

## 🎬 Prolog Cerita
> *"Kami memiliki platform P2P Car Rental bernama **Roda Kita** yang mempertemukan Pemilik Mobil (Mitra), Penyewa (Pelanggan), dan Admin sebagai verifikator sistem. Hari ini, kami akan mendemonstrasikan bagaimana platform ini menjaga keamanan transaksi, kemudahan penjemputan, hingga fleksibilitas promo."*

---

## 🎭 BABAK 1: Pendaftaran Armada & Penentuan Lokasi (Mitra & Admin)
**Tujuan Demo**: Menampilkan Integrasi Peta LeafletJS di sisi Admin.

1. **Narasi**: 
   > *"Cerita dimulai dari Hendra, salah satu Mitra kami. Admin mendaftarkan mobil Toyota Avanza milik Hendra ke dalam sistem. Yang spesial di sini adalah penentuan titik lokasi penjemputan kunci secara akurat menggunakan peta interaktif."*
2. **Aksi Demo**:
   - Login sebagai **Admin** (`admin@gmail.com`).
   - Masuk ke menu **Manajemen Mobil** -> pilih salah satu mobil (Avanza Veloz) -> klik **Edit**.
   - Tunjukkan bagian **Lokasi Penjemputan**. 
   - *Demonstrasikan*: Klik/geser pin merah pada peta LeafletJS. Tunjukkan bahwa koordinat **Latitude** dan **Longitude** berubah secara otomatis saat peta diklik.
   - Isi alamat jalan secara detail, klik **Simpan**.

---

## 🎭 BABAK 2: Keamanan Akun & Proses Verifikasi (Pelanggan & Admin)
**Tujuan Demo**: Menampilkan fitur Trust & Safety (Verifikasi Akun Pelanggan).

1. **Narasi**:
   > *"Sekarang, masuklah Budi, seorang pelanggan baru yang ingin menyewa mobil lepas kunci. Karena alasan keamanan (Trust & Safety), Budi tidak bisa langsung membawa kabur mobil sebelum identitasnya diverifikasi secara resmi oleh Admin."*
2. **Aksi Demo**:
   - Login sebagai **Pelanggan Belum Verifikasi** (`unverified@gmail.com`).
   - Coba lakukan checkout mobil Avanza Veloz dengan memilih tipe layanan **Lepas Kunci**. Tunjukkan bahwa sistem **memblokir** pemesanan dengan pesan error: *"Layanan Lepas Kunci memerlukan verifikasi KTP/SIM terlebih dahulu."*
   - Pergi ke menu **Verifikasi Akun** di navbar. Upload KTP, SIM, dan Selfie tiruan. Klik **Kirim**.
   - Logout, lalu login kembali sebagai **Admin** (`admin@gmail.com`).
   - Pergi ke menu **Verifikasi Pengguna**. Tunjukkan pengajuan akun baru yang statusnya `pending`.
   - Klik **Approve (Setujui)**. Jelaskan bahwa sekarang akun tersebut sudah aman dan dipercaya untuk menyewa unit mobil.

---

## 🎭 BABAK 3: Proses Penyewaan & Penerapan Diskon (Pelanggan)
**Tujuan Demo**: Menampilkan Peta Pelanggan, Kolom Kode Voucher, dan Integrasi Midtrans.

1. **Narasi**:
   > *"Setelah akun Budi terverifikasi, ia kembali ke katalog untuk memesan mobil Avanza Veloz. Di sini Budi bisa melihat titik koordinat penjemputan mobil, memasukkan voucher diskon, dan langsung membayar lewat gateway pembayaran."*
2. **Aksi Demo**:
   - Login sebagai **Pelanggan Terverifikasi** (`pelanggan@gmail.com`).
   - Masuk ke Detail Mobil Avanza Veloz. Tunjukkan kepada audiens peta LeafletJS di sebelah kiri bawah yang menampilkan titik pin merah presisi lokasi kunci mobil.
   - Klik **Lanjut Proses Sewa** (pilih durasi 2 hari, total subtotal Rp 700.000).
   - Pada halaman Checkout, tunjukkan kolom **Kode Promo**. Masukkan kode `RODAKITA10` lalu klik **Gunakan**.
   - Tunjukkan potongan harga sebesar **Rp 70.000 (10%)** muncul secara real-time dan memotong tagihan akhir menjadi **Rp 630.000**.
   - Klik **Kirim Pengajuan**. Tunjukkan integrasi pop-up Midtrans Snap yang mendeteksi nominal tagihan bersih sebesar Rp 630.000.

---

## 🎭 BABAK 4: Penyelesaian Sewa, Komisi Adil, dan Rating (Mitra & Pelanggan)
**Tujuan Demo**: Menampilkan perhitungan komisi 70% setelah diskon di sisi Mitra, dan Ulasan Shopee-style.

1. **Narasi**:
   > *"Sewa selesai dan Budi mengembalikan mobil tersebut. Admin memproses penyelesaian sewa di dashboard. Sekarang, kita akan melihat bagaimana sistem membagi komisi 70% untuk Mitra secara adil berdasarkan nilai transaksi bersih."*
2. **Aksi Demo**:
   - Login sebagai **Admin** (`admin@gmail.com`). Pergi ke **Jadwal & Booking**. Ubah status sewa Avanza Budi dari `dibayar` -> `disewakan` -> `selesai` (mensimulasikan mobil sudah dikembalikan).
   - Pergi ke menu **Transaksi** -> klik **Transfer Dana** pada booking tersebut untuk meneruskan komisi ke rekening Mitra.
   - Logout, lalu login sebagai **Mitra** (`mitra@gmail.com`).
   - Masuk ke menu **Pendapatan & Komisi**. Tunjukkan ke audiens bahwa pendapatan yang masuk ke Mitra untuk transaksi tadi adalah **Rp 441.000**.
   - *Jelaskan*: *"Sistem menghitung komisi 70% dari nilai bersih Rp 630.000 (setelah diskon promo), bukan dari Rp 700.000. Ini menjamin pembagian komisi yang adil bagi perusahaan dan mitra."*
   - Logout, login kembali sebagai **Pelanggan** (`pelanggan@gmail.com`).
   - Masuk ke **Riwayat Booking**, klik **Beri Ulasan** pada pesanan Avanza tadi. Berikan rating bintang 5 dan catatan.
   - Buka kembali halaman katalog Avanza Veloz dan tunjukkan ulasan Budi telah masuk dengan nama pelanggan yang tersensor otomatis (`Bud***`).

---

## 🏁 Epilog
> *"Demikian alur transaksi lengkap RodaKita. Sistem kami tidak hanya menjamin fungsionalitas penyewaan, namun juga menjamin keamanan pengguna lewat peta penjemputan, validasi verifikasi identitas, serta skema promo diskon yang transparan."*
