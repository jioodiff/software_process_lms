# Lab Management System (LMS) - Universitas IPWIJA 🎓

Aplikasi LMS berbasis website buat mempermudah administrasi lab, manajemen stok barang, dan proses pinjam-meminjam alat buat mahasiswa. Aplikasi ini juga udah nyambung ke n8n buat kirim notif otomatis via Email/WhatsApp lho!

---

## 🛠 Syarat Sistem (Prerequisites)

Bisa jalanin pakai **Docker (Laravel Sail)** atau cara klasik pakai **XAMPP / Laragon**.
- **Opsi 1 (Paling gampang)**: Docker Desktop
- **Opsi 2**: PHP >= 8.2, Composer, Node.js, dan MySQL (via XAMPP/Laragon)

---

## 🚀 Cara Install & Jalanin via Docker / Laravel Sail (Recomended)

Cara ini paling enak karena gak perlu ribet install PHP/MySQL di laptop kamu.

1. **Clone Repo:**
   ```bash
   git clone https://github.com/jioodiff/software_process_lms.git
   cd software_process_lms
   ```

2. **Copy file `.env`:**
   ```bash
   cp .env.example .env
   ```
   *(Tinggal edit file `.env` kalau mau ngatur `N8N_WEBHOOK_URL` & `N8N_API_KEY` buat fitur notifnya).*

3. **Install Dependencies (Composer):**
   Kalau udah ada Composer di laptop:
   ```bash
   composer install
   ```
   Kalau belum ada (pakai Docker aja):
   - **Windows (PowerShell):**
     ```powershell
     docker run --rm -u "$((Get-Item .).CreationTime.ToString('u'))" -v "${PWD}:/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
     ```
   - **Mac/Linux:**
     ```bash
     docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
     ```

4. **Jalanin Docker Container (Sail):**
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Generate Key & Setup Database:**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```
   *(Perintah `--seed` ini bakal otomatis bikin akun Admin dan ngisi data alat dummy).*

6. **Build Frontend (Vite):**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build
   ```

7. **Selesai! 🎉**
   Buka browser dan ketik: **http://localhost**

---

## 🚀 Cara Install & Jalanin Tanpa Docker (Pakai XAMPP / Laragon)

Buat yang lebih suka pakai XAMPP atau Laragon:

1. **Clone Repo:**
   ```bash
   git clone https://github.com/jioodiff/software_process_lms.git
   cd software_process_lms
   ```

2. **Install Composer:**
   ```bash
   composer install
   ```

3. **Copy `.env`:**
   ```bash
   cp .env.example .env
   ```
   Lalu buka file `.env` dan setting koneksi databasenya (sesuaikan sama phpMyAdmin kamu):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_lab_management_system
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Bikin Database Baru:**
   Buka phpMyAdmin, trus bikin database baru dengan nama `db_lab_management_system`.

5. **Generate Key, Link Storage & Migrate:**
   Jalanin perintah ini berurutan ya:
   ```bash
   php artisan key:generate
   php artisan storage:link
   php artisan migrate:fresh --seed
   ```
   *(Note: `storage:link` wajib banget supaya gambar alatnya bisa muncul).*

6. **Build Frontend:**
   ```bash
   npm install
   npm run build
   ```

7. **Jalanin Local Server:**
   ```bash
   php artisan serve
   ```

8. **Selesai! 🎉**
   Akses di browser lewat URL yang muncul (biasanya **http://localhost:8000**).

---

## 🔑 Akun Login Bawaan

Begitu selesai di-install, kamu bisa langsung login pakai akun bawaan (dari seeder) ini:

**Akun Administrator:**
- **Email:** `admin@ipwija.ac.id`
- **Password:** `password`

*(Catatan: Akun mahasiswa sengaja nggak dibikin dari awal, jadi nanti mahasiswa bisa langsung daftar sendiri lewat halaman Register).*

---

## ✨ Fitur Utama
- **Autentikasi & Otorisasi** berbasis Role (Admin & Mahasiswa).
- **Katalog Alat Interaktif** dengan stok yang selalu update secara *real-time*.
- **Peminjaman & Pengembalian Alat** lengkap dengan pengecekan kondisi (Baik, Rusak, Hilang).
- **Audit Log (Append-Only)** buat nyatet semua aktivitas sistem biar aman.
- **Pusat Laporan** yang datanya bisa di-export ke CSV.
- **Notifikasi Terintegrasi** pakai n8n Webhook.
