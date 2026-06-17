# Panduan Pengujian Manual (Step-by-Step Manual Testing Guide)

Dokumen ini berisi panduan langkah demi langkah untuk menguji fitur **Sistem Promo & Kode Voucher** dan **Integrasi Peta LeafletJS** secara manual di RodaKita.

---

## Persiapan Awal (Preparation)

Pastikan server lokal Anda dan database sudah berjalan dengan PHP 8.5 dan dummy data terbaru:

1. **Jalankan Migrasi & Seed Database**:
   ```powershell
   # Jalankan migrations fresh dan populate dummy data
   $packageDir = Get-ChildItem "$env:LOCALAPPDATA\Microsoft\WinGet\Packages\PHP.PHP.8.5_*" | Select-Object -First 1 -ExpandProperty FullName; $env:Path = "$packageDir;$env:Path"
   php artisan migrate:fresh --seed
   ```

2. **Jalankan Server Lokal**:
   ```powershell
   composer run dev
   # Server Laravel akan berjalan di http://localhost:8000
   ```

3. **Akun Pengujian (Test Accounts)**:
   Berikut adalah daftar lengkap 6 akun yang sudah disiapkan otomatis dalam seeder untuk pengujian berbagai alur:
   
   *   **Admin Utama (Role 1)**:
       *   Email: `admin@gmail.com`
       *   Password: `password123`
   *   **Pelanggan Terverifikasi (Role 2)**:
       *   Email: `pelanggan@gmail.com`
       *   Password: `password123`
       *   *Note: Sudah terverifikasi KTP/SIM oleh Admin, bisa langsung memesan layanan Lepas Kunci dan memakai promo.*
   *   **Pelanggan Belum Verifikasi (Role 2)**:
       *   Email: `unverified@gmail.com`
       *   Password: `password123`
       *   *Note: Belum mengupload data identitas, akan diblokir jika mencoba memesan layanan Lepas Kunci.*
   *   **Pelanggan Pending Verifikasi (Role 2)**:
       *   Email: `pending@gmail.com`
       *   Password: `password123`
       *   *Note: Sudah mengupload KTP/SIM/Selfie, siap disetujui/ditolak oleh Admin di menu Verifikasi.*
   *   **Mitra Utama / Pemilik Mobil (Role 3)**:
       *   Email: `mitra@gmail.com`
       *   Password: `password123`
       *   *Note: Bisa masuk ke dashboard mitra untuk memantau mobil, melihat komisi 70%, dan mengajukan klaim asuransi.*
   *   **Mitra Kedua (Role 3)**:
       *   Email: `mitra2@gmail.com`
       *   Password: `password123`

---

# BAGIAN 1: PENGUJIAN PERAN ADMIN (ROLE 1)

Gunakan akun **Admin** (`admin@gmail.com`) untuk menguji skenario operasional berikut:

### Skenario 1.1: CRUD Manajemen Promo & Voucher
1. Masuk ke menu **Lainnya** -> **Manajemen Promo**.
2. Klik **Tambah Promo**, buat promo dengan kode `RODAKITA10`, tipe `persen`, nominal `10` (10%), minimal transaksi `100000`, kuota `5`, tanggal kadaluarsa di minggu depan. Klik **Simpan**.
3. Klik **Edit** pada kode promo tersebut, ubah kuotanya menjadi `10`, klik **Simpan**. Pastikan data terupdate.
4. Buat kode promo sekali lagi bernama `DISKONKADALUARSA` dengan tanggal kadaluarsa kemarin (untuk pengujian error kelak).

### Skenario 1.2: Manajemen Verifikasi Pengguna (Trust & Safety)
1. Masuk ke menu **Operasional** -> **Verifikasi Pengguna**.
2. Anda akan melihat pengajuan dari pelanggan bernama **Citra Lestari** (`pending@gmail.com`). Klik tombol detail/review.
3. Tinjau foto dokumen (KTP, SIM, dan Selfie).
4. Klik tombol **Setujui Verifikasi (Approve)**. Status pelanggan akan berubah menjadi `verified`.

### Skenario 1.3: Manajemen Booking & Penyerahan Kunci (Serah-Terima)
1. Masuk ke menu **Operasional** -> **Jadwal & Booking**.
2. Di sini terdapat daftar pesanan dari pelanggan. Untuk pesanan dengan status `dibayar`:
   - Klik tombol **Serahkan Mobil** saat pelanggan datang menjemput armada. Status berubah menjadi `disewakan`.
   - Klik tombol **Terima Mobil** setelah masa sewa habis dan armada dikembalikan. Status berubah menjadi `selesai`.
   - Catatan: Anda juga bisa menolak (`reject`) booking yang berstatus `menunggu_approval`.

### Skenario 1.4: Manajemen Pengajuan Klaim Asuransi
1. Masuk ke menu **Lainnya** -> **Pengajuan Klaim**.
2. Tinjau klaim asuransi yang diajukan oleh Mitra (e.g. laporan kerusakan bumper belakang).
3. Klik **Proses Klaim**. Anda bisa memilih **Setujui** dengan memasukkan nominal *Biaya yang Disetujui* (e.g. Rp 500.000) atau memilih **Tolak** dengan menuliskan alasan penolakan.

### Skenario 1.5: Keuangan & Transfer Komisi Mitra
1. Masuk ke menu **Lainnya** -> **Transaksi**.
2. Lihat daftar dana sewa bersih yang masuk. Pastikan ada tombol **Transfer Dana** di baris transaksi yang statusnya sudah selesai (`selesai`).
3. Klik **Transfer Dana** untuk mengirimkan 70% bagian komisi ke rekening Mitra secara simulasi.

---

# BAGIAN 2: PENGUJIAN PERAN PELANGGAN (ROLE 2)

### Skenario 2.1: Mengunggah Verifikasi Identitas (Akun Baru)
1. Login menggunakan akun **Pelanggan Belum Verifikasi** (`unverified@gmail.com`).
2. Masuk ke menu **Verifikasi Akun** di navbar.
3. Unggah file gambar sembarang untuk foto KTP, SIM, dan foto Selfie. Klik **Kirim Verifikasi**.
4. Akun Anda sekarang berstatus `pending` dan tidak dapat diubah kembali sampai Admin memprosesnya.

### Skenario 2.2: Batasan Proteksi Layanan "Lepas Kunci"
1. Menggunakan akun yang belum terverifikasi (`unverified@gmail.com`):
   - Masuk ke katalog mobil, pilih mobil Avanza Veloz.
   - Pilih tanggal sewa, klik **Lanjut Proses Sewa**.
   - Pilih tipe layanan **Lepas Kunci** dan klik **Kirim Pengajuan Sewa**.
   - **Verifikasi**: Anda harus diblokir dengan pesan error: *"Layanan Lepas Kunci memerlukan verifikasi KTP/SIM terlebih dahulu."*

### Skenario 2.3: Pemesanan dengan Kode Promo Valid
1. Login menggunakan akun **Pelanggan Terverifikasi** (`pelanggan@gmail.com`).
2. Pilih mobil Avanza Veloz (biaya sewa Rp 350.000/hari), pilih durasi sewa 2 hari (total subtotal Rp 700.000), lalu lanjut ke Checkout.
3. Masukkan kode promo `RODAKITA10` lalu klik **Gunakan**.
4. **Verifikasi**:
   - Rincian promo berlabel `RODAKITA10` akan memotong total harga sebesar 10% (Rp 70.000).
   - Nilai bayar akhir menjadi Rp 630.000.
5. Klik **Kirim Pengajuan Sewa**. Pastikan Snap Midtrans muncul dengan nominal yang benar setelah terdiskon (Rp 630.000).

### Skenario 2.4: Pengujian Edge Cases Validasi Promo
Cobalah skenario error berikut saat checkout:
- **Kode Promo Tidak Ditemukan**: Masukkan `KODESALAH` -> Harus muncul *"Kode promo tidak ditemukan."*
- **Kode Promo Kadaluarsa**: Masukkan `DISKONKADALUARSA` -> Harus muncul *"Kode promo sudah kadaluarsa."*
- **Minimal Transaksi Belum Terpenuhi**: Gunakan kode promo yang memiliki batas minimal transaksi lebih tinggi dari subtotal sewa mobil -> Harus muncul pesan total sewa tidak memenuhi minimal transaksi.

### Skenario 2.5: Menambahkan Rating & Ulasan (Shopee-Style)
1. Pergi ke menu **Riwayat Booking**.
2. Cari pesanan yang status penyewaannya sudah selesai (`selesai`).
3. Klik tombol **Beri Ulasan**.
4. Isi rating bintang (1-5) dan berikan catatan ulasan, lalu klik **Kirim**.
5. Buka kembali halaman katalog mobil tersebut dan verifikasi bahwa rating rata-rata bintang serta ulasan sensor nama (misal: `Pel***`) telah diperbarui.

---

# BAGIAN 3: PENGUJIAN PERAN MITRA (ROLE 3)

Gunakan akun **Mitra Utama** (`mitra@gmail.com`) untuk memverifikasi fitur berikut:

### Skenario 3.1: Pemantauan Armada (Monitoring Mobil)
1. Masuk ke menu **Monitoring Mobil** di sidebar.
2. Lihat daftar unit mobil Anda. Pastikan plat nomor dan status unit (Tersedia / Sedang Disewakan) tampil secara real-time.

### Skenario 3.2: Pengajuan Klaim Asuransi
1. Pada menu **Monitoring Mobil**, klik tombol **Ajukan Klaim** pada mobil yang mengalami insiden.
2. Isi formulir laporan kerusakan (misal: "Bumper belakang lecet ditabrak motor"), estimasi biaya perbaikan (misal: Rp 600.000), dan unggah foto kerusakan. Klik **Kirim Pengajuan**.
3. Pantau status pengajuan klaim Anda di menu **Daftar Klaim**. Status awalnya adalah `pending`.

### Skenario 3.3: Verifikasi Komisi 70% Bersih Setelah Diskon Promo
1. Masuk ke menu **Pendapatan & Komisi**.
2. Temukan transaksi sewa mobil Avanza Veloz milik Anda yang menggunakan promo `RODAKITA10` pada **Skenario 2.3**.
3. **Verifikasi Perhitungan Komisi**:
   - Total bayar setelah promo adalah Rp 630.000.
   - Komisi Anda (Mitra) harus tertulis tepat sebesar **Rp 441.000** (70% dari Rp 630.000).
   - Pastikan nominal ini bersih dan tidak dihitung dari total kotor Rp 700.000 sebelum diskon.

---

## Skenario 4: Pengujian Peta Lokasi Penjemputan (LeafletJS Maps)

### A. Sisi Admin (Mengatur Koordinat Mobil)
1. Login kembali sebagai **Admin** (`admin@gmail.com`).
2. Masuk ke menu **Manajemen Mobil**.
3. Cari salah satu mobil di daftar tabel (misal: Avanza Veloz) dan klik tombol **Edit** (ikon pensil).
4. Gulir ke bawah hingga menemukan bagian **Lokasi Penjemputan Mobil**:
   - Isi kolom **Alamat Penjemputan Detail** (contoh: `Jalan Soekarno Hatta No. 102, dekat Terminal Arjosari`).
   - Perhatikan peta LeafletJS interaktif di bawahnya.
   - **Geser penanda (drag pin)** atau klik di sembarang titik pada peta.
   - Pastikan kolom **Latitude** dan **Longitude** terisi secara otomatis dengan koordinat titik yang Anda pilih secara real-time.
5. Klik **Simpan Perubahan**.

### B. Sisi Pelanggan (Melihat Titik Peta Penjemputan)
1. Login kembali sebagai **Pelanggan** (`pelanggan@gmail.com`).
2. Pilih mobil Avanza Veloz yang baru saja Anda edit koordinat lokasinya.
3. Di halaman **Detail Mobil**, perhatikan kolom sebelah kiri di bawah gambar mobil.
4. **Verifikasi**:
   - Di sana harus tampil kartu **Lokasi Penjemputan** yang berisi teks detail alamat penjemputan.
   - Di bawah alamat, tampil peta interaktif Leaflet dengan pin berwarna merah yang menunjuk tepat pada koordinat yang Anda atur di panel admin sebelumnya.
