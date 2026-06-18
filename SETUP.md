# SETUP.md - RodaKita Project Setup Guide (from scratch)

> How to get this project running from zero on Windows.
> Covers PHP 8.5 via winget, MySQL, the full schema, and the new Trust & Safety features (Verifikasi Akun + Klaim Asuransi + Auto-Cancel Booking + Pencairan Dana Mitra).

---

## Prerequisites

| Tool     | Version | Notes |
|----------|---------|-------|
| PHP      | ^8.3    | **Required** - Laravel 13 does not work on PHP 8.2 |
| Composer | latest  | |
| Node.js  | 20+     | |
| MySQL    | 8.0+    | Required (project uses MySQL, not SQLite). Can be run via XAMPP, Laragon, or standalone MySQL Server. |

---

## Step 1 - PHP 8.5

```powershell
winget install PHP.PHP.8.5
```

Locate the installation:

```powershell
Get-ChildItem "$env:LOCALAPPDATA\Microsoft\WinGet\Packages\PHP.PHP.8.5_*"
```

Enable required extensions (openssl, curl, mbstring, pdo_mysql, fileinfo, gd, zip):

```powershell
# Inside the PHP folder:
Copy-Item php.ini-development php.ini
$ini = Get-Content php.ini
$ini = $ini -replace ";extension=(curl|openssl|mbstring|pdo_mysql|pdo_sqlite|fileinfo|gd|zip)", "extension=`$1"
$ini | Set-Content php.ini
```

Add PHP to your PATH for the current session:

```powershell
$phpPath = "$env:LOCALAPPDATA\Microsoft\WinGet\Packages\PHP.PHP.8.5_Microsoft.Winget.Source_8wekyb3d8bbwe"
$env:Path = "$phpPath;$env:Path"
php -v   # Should show "PHP 8.5.x"
```

---

## Step 2 - Clone & Dependencies

```powershell
git clone <repo-url> Tubes_RodaKita
cd Tubes_RodaKita

composer install
npm install
npm run build
```

---

## Step 3 - Environment

```powershell
copy .env.example .env
php artisan key:generate
```

Edit `.env` to use MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tubes_rodakita
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_IS_PRODUCTION=false
```

---

## Step 4 - Database

> [!NOTE]
> Make sure your MySQL service is running first (e.g., click **Start** next to MySQL in the **XAMPP Control Panel** or start your local MySQL service).

### 4a. Create the MySQL database

Create a database named `tubes_rodakita` using your preferred client (phpMyAdmin, TablePlus, DBeaver, etc.) or run:

```sql
CREATE DATABASE IF NOT EXISTS tubes_rodakita CHARACTER SET utf8mb4;
```

### 4b. Run migrations

All database tables (including trust & safety tables) are now fully managed by Laravel migrations:

```powershell
php artisan migrate
```

### 4c. Run database seeders (populated with test data)

To populate roles, test users (admin, verified/unverified customers, mitras), dummy cars, bookings, and insurance claims:

```powershell
php artisan db:seed
```

### 4d. Create Storage link

```powershell
php artisan storage:link
```

---

## Step 5 - Run

```powershell
# Terminal 1 - Laravel dev server:
php artisan serve

# Terminal 2 - Vite hot reload (optional):
npm run dev
```

Visit **http://localhost:8000**

---

## Step 6 - Login

| Role       | Email              | Password    |
|------------|--------------------|-------------|
| Admin      | admin@gmail.com    | password123 |

Pelanggan (role 2) and Mitra (role 3) users can be created by Admin at `/admin/user/create`.

---

## New Features (Post-Setup)

### Verifikasi Akun (Pelanggan -> Trust & Safety)

1. Login as Pelanggan -> click **Verifikasi Akun** in navbar
2. Upload KTP, SIM, and Selfie
3. Status changes to **pending**
4. Admin reviews at `/admin/verifikasi` -> Approve or Reject
5. Once **verified**, the user can book "Lepas Kunci" service
6. Unverified users are blocked from "Lepas Kunci" with a flash message

### Klaim Asuransi (Mitra -> Insurance Claim)

1. Login as Mitra -> **Monitoring Mobil**
2. Click **Ajukan Klaim** on any car
3. Fill in damage description, estimated cost, upload photos
4. Admin reviews at `/admin/klaim` -> Approve (with biaya_disetujui) or Reject (with note)
5. Mitra can track claim status at `/mitra/klaim`

### Promo & Voucher Code System (Discount Management)

1. **Admin CRUD**: Admin can manage vouchers at `/admin/promo` (add code, discount type [percentage/nominal], min transaction, quota, expiration date).
2. **Customer Checkout**: Customer can enter promo codes during checkout.
3. **Dynamic Calculations**: The total payment is calculated and discounted in real-time via AJAX `/pelanggan/promo/check`.
4. **Midtrans Integration**: The discounted amount is passed to Midtrans Snap. Commission splits (70% for Mitra) are calculated based on the final discounted amount.

### LeafletJS Maps Integration (Pickup Location)

1. **Admin Car Creation/Edit**: Admin can place a marker on an interactive Leaflet map to set `latitude` and `longitude` coordinates for a car, along with an `alamat_jemput` address text field.
2. **Customer Car Detail**: Displays the pickup location map pin on the car details page `/pelanggan/mobil/{id}`.

### Auto-Cancel Booking (Batas Waktu Pembayaran 30 Menit)

Booking yang tidak dibayar dalam **30 menit** akan otomatis dibatalkan oleh sistem, membebaskan slot tanggal agar tidak menghalangi mitra.

1. Saat pelanggan membuat booking, kolom `bayar_sebelum` diisi dengan `now() + 30 menit`.
2. Countdown timer muncul di halaman checkout (Fase Pembayaran), menampilkan sisa waktu secara real-time.
3. Timer berubah merah saat sisa waktu < 5 menit dan otomatis redirect ke Riwayat Booking saat habis.
4. Scheduler Laravel menjalankan `booking:cancel-expired` setiap menit untuk membatalkan booking yang melewati `bayar_sebelum`.
5. Kuota promo yang sudah dipakai akan dikembalikan otomatis jika booking dibatalkan.

> [!IMPORTANT]
> Fitur ini membutuhkan **Laravel Scheduler aktif**. Lihat bagian **Menjalankan Scheduler** di bawah.

### Pencairan Dana / Penarikan Komisi Mitra (Withdrawal)

Mitra dapat mengajukan pencairan dana komisi secara mandiri tanpa harus menghubungi admin.

1. Mitra login → buka menu **Pencairan Dana** di sidebar.
2. Isi formulir penarikan: jumlah, nama bank, nomor rekening, nama pemilik rekening.
3. Pengajuan masuk ke Admin dengan status `pending`.
4. Admin meninjau di `/admin/pencairan` → Setujui (opsional: unggah bukti transfer) atau Tolak dengan catatan.
5. Mitra melihat riwayat dan status pengajuan secara real-time.

---

## Menjalankan Scheduler (Auto-Cancel Booking)

Scheduler Laravel diperlukan agar fitur **auto-cancel booking kedaluwarsa** berjalan otomatis.

### Development (Lokal)

Jalankan scheduler di terminal terpisah (berjalan terus selama development):

```powershell
php artisan schedule:work
```

Atau jalankan command secara manual untuk menguji:

```powershell
php artisan booking:cancel-expired
```

### Production (Linux Server)

Tambahkan satu baris ke crontab server (`crontab -e`):

```bash
* * * * * cd /path/to/Tubes_RodaKita && php artisan schedule:run >> /dev/null 2>&1
```

Ini membuat scheduler berjalan setiap menit. Laravel kemudian menentukan sendiri command mana yang perlu dieksekusi berdasarkan jadwal yang didefinisikan di `routes/console.php`.

---

## New Routes Added

| Method | Path | Description |
|--------|------|-------------|
| GET    | `/pelanggan/verifikasi` | Verification upload form |
| POST   | `/pelanggan/verifikasi/upload` | Upload KTP/SIM/selfie |
| POST   | `/pelanggan/verifikasi/proses` | Run face matching |
| GET    | `/admin/verifikasi` | Review pending verifications |
| POST   | `/admin/verifikasi/{id}/approve` | Approve verification |
| POST   | `/admin/verifikasi/{id}/reject` | Reject verification |
| GET    | `/mitra/klaim` | Mitra claim list |
| POST   | `/mitra/klaim/store` | Submit new claim |
| GET    | `/mitra/klaim/{id}` | Claim detail |
| GET    | `/admin/klaim` | Admin claim management |
| POST   | `/admin/klaim/{id}/proses` | Approve/reject claim |
| GET    | `/admin/promo` | Admin promo code management dashboard |
| GET    | `/admin/promo/create` | Form to create a new promo code |
| POST   | `/admin/promo/store` | Save a new promo code |
| GET    | `/admin/promo/{id}/edit` | Form to edit an existing promo code |
| PUT    | `/admin/promo/{id}` | Update an existing promo code |
| DELETE | `/admin/promo/{id}` | Delete a promo code |
| POST   | `/pelanggan/promo/check` | Asynchronously check promo code validity and calculate discount |
| GET    | `/mitra/pencairan` | Mitra withdrawal request form & history |
| POST   | `/mitra/pencairan/store` | Submit withdrawal request |
| GET    | `/admin/pencairan` | Admin withdrawal management |
| POST   | `/admin/pencairan/{id}/approve` | Approve withdrawal (with transfer proof upload) |
| POST   | `/admin/pencairan/{id}/reject` | Reject withdrawal with reason |

---

## New Models

| Model | Table | Timestamps | Key Relations |
|-------|-------|------------|---------------|
| `VerifikasiAkun` | `verifikasi_akun` | Yes (default) | `belongsTo(User)` via `id_user` |
| `KlaimAsuransi` | `klaim_asuransi` | Yes (default) | `belongsTo(Booking)`, `belongsTo(User)` |
| `Promo` | `promo` | Yes (default) | `hasMany(Pembayaran)` |
| `PencairanDana` | `pencairan_dana` | Yes (default) | `belongsTo(User)` via `id_mitra` |

`User` model also gained: `hasOne(VerifikasiAkun::class)`.
`Pembayaran` model also gained: `belongsTo(Promo::class)`.
`Mobil` model also gained: `latitude`, `longitude`, `alamat_jemput` columns.
`Booking` model also gained: `bayar_sebelum` (datetime) column — payment deadline for auto-cancel.

## Running Automated Tests

To ensure that the application features and validations are functioning properly, you can run the automated test suite. These tests execute against an in-memory SQLite database, meaning they do not affect your local MySQL database.

To run all tests (including Unit, Feature, and Integration tests):
```powershell
composer run test
```

### Available Test Suites:
1. **Safety & Booking Tests (`SafetyAndBookingTest`)**:
   Tests the trust & safety restrictions, booking date validation, promo code system, and coordinates:
   - `booking no overlap succeeds`: Validates that non-overlapping bookings succeed.
   - `booking overlap fails`: Verifies that overlapping bookings are blocked.
   - `verification flow`: Tests customer document upload (KTP/SIM/selfie) and Admin approval.
   - `pelanggan can apply valid promo code`: Tests checking coupon constraints, dynamic pricing reduction, and commission calculations.
   - `pelanggan cannot apply expired or invalid promo code`: Verifies expired/depleted promo codes are ignored.
   - `car coordinates stored correctly on creation`: Tests admin creating car with Leaflet latitude, longitude, and pickup address.
   - `car coordinates stored correctly on update`: Tests admin modifying car pickup coordinates and address.
   
   To run only this suite:
   ```powershell
   php artisan test --filter=SafetyAndBookingTest
   ```

2. **Comprehensive App Tests (`ComprehensiveAppTest`)**:
   Tests all core features for Admin, Mitra, and Pelanggan roles:
   - `admin can manage users`: Tests admin creation, update, and deletion of users.
   - `admin can manage cars`: Tests admin creation, update, and deletion of cars.
   - `mitra can monitor cars and commissions`: Tests mitra monitoring of their own cars and checking commission summaries.
   - `pelanggan can submit reviews`: Tests pelanggan posting review and rating after a rental is complete.
   - `pelanggan can manage travel schedule`: Tests pelanggan travel scheduling during booking periods.

   To run only this suite:
   ```powershell
   php artisan test --filter=ComprehensiveAppTest
   ```

## Midtrans Payment Testing & Verification (Simulasi Lunas)

Since Midtrans is integrated as the payment gateway, you need to verify how the system handles status changes from `belum_dibayar` to `dibayar` (lunas). Because Midtrans webhook calls are sent over the internet, they cannot reach your localhost directly by default.

Here are the 3 ways to test and simulate successful payments:

### Method A: Webhook Tunneling via ngrok (Recommended for Full Testing)
1. Download and install ngrok from [ngrok.com](https://ngrok.com/).
2. Run ngrok to tunnel your local port 8000:
   ```bash
   ngrok http 8000
   ```
3. Copy the forwarding HTTPS URL generated by ngrok (e.g., `https://a1b2-c3d4.ngrok-free.app`).
4. Update your `.env` file with this URL:
   ```env
   APP_URL=https://a1b2-c3d4.ngrok-free.app
   ```
5. Go to your **Midtrans Sandbox Dashboard** -> **Settings** -> **Configuration** and set the **Payment Notification URL** to:
   `https://a1b2-c3d4.ngrok-free.app/midtrans/callback`
6. Now, completing a sandbox payment via the checkout screen will automatically change the booking & payment status to paid (`dibayar`) in your local system.

### Method B: Manual Webhook Simulation (No ngrok required)
You can mock Midtrans webhook notifications by making a direct POST request from your local machine to your local endpoint.
- **URL**: `http://localhost:8000/midtrans/callback`
- **Method**: `POST`
- **Headers**: `Content-Type: application/json`
- **JSON Body Payload**:
  ```json
  {
    "transaction_status": "settlement",
    "order_id": "ORDER-3-1718645000",
    "fraud_status": "accept"
  }
  ```
  *(Note: Replace the number `3` in `ORDER-3-...` with the actual **ID** of the payment record in your `pembayaran` database table that you wish to mark as paid).*

### Method C: Manual Database Edit (Fastest)
If you just want to test dashboard flows, you can bypass the checkout step and manually edit the database:
1. Open your database (e.g., via phpMyAdmin, TablePlus, DBeaver).
2. Go to the `pembayaran` table and change `status_pembayaran` to `dibayar`.
3. Go to the `booking` table and change `status` to `dibayar` or `disewa`.

---

## Commands Reference

```powershell
composer run dev                     # php artisan serve + queue:listen + pail + npm run dev
composer run test                    # php artisan test (SQLite in-memory)
./vendor/bin/pint                    # Laravel Pint code style fixer
php artisan booking:cancel-expired  # Manually cancel expired bookings (no payment within 30 min)
php artisan schedule:work           # Run scheduler locally (keeps running in foreground)
```

---

## Troubleshooting

| Problem | Fix |
|---------|-----|
| `require(vendor/autoload.php)` | Run `composer install` |
| OpenSSL extension required | Enable `extension=openssl` in php.ini |
| Foreign key error on new tables | Ensure `user(id)` is `INT(11)` and ENGINE=InnoDB |
| `storage` link missing | Run `php artisan storage:link` |
| Midtrans payment fails | Set `MIDTRANS_SERVER_KEY` in `.env` (sandbox key from Midtrans dashboard) |
| `'vite'` not recognized | Run `npm install` again |
| Auto-cancel tidak berjalan | Pastikan `php artisan schedule:work` aktif di terminal terpisah (lokal) atau crontab sudah dikonfigurasi (production) |
| `bayar_sebelum` column not found | Run `php artisan migrate` untuk menambahkan kolom baru ke tabel `booking` |

