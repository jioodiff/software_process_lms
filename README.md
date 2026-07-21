# Lab Management System (LMS) - Universitas IPWIJA 🎓

Aplikasi **Lab Management System (LMS)** berbasis website untuk mempermudah proses administrasi laboratorium, manajemen inventaris, dan alur peminjaman alat oleh mahasiswa. Aplikasi ini terintegrasi dengan n8n untuk notifikasi otomatis (Email/WhatsApp).

---

## 🛠 Persyaratan Sistem (Prerequisites)

Anda bisa menjalankan aplikasi ini menggunakan **Docker (Laravel Sail)** atau secara manual menggunakan **XAMPP / Laragon / Local Server**.

- **Opsi 1 (Direkomendasikan)**: Docker Desktop
- **Opsi 2**: PHP >= 8.2, Composer, Node.js, dan MySQL (via XAMPP/Laragon)

---

## 🚀 Cara Instalasi & Menjalankan (Menggunakan Docker / Laravel Sail)

Ini adalah cara paling direkomendasikan karena Anda tidak perlu menginstall PHP/MySQL secara manual di komputer Anda.

1. **Clone Repository:**
   ```bash
   git clone https://github.com/jioodiff/software_process_lms.git
   cd software_process_lms
   ```

2. **Copy file `.env`:**
   ```bash
   cp .env.example .env
   ```
   *(Silakan edit file `.env` untuk mengatur `N8N_WEBHOOK_URL` & `N8N_API_KEY` jika Anda menggunakan fitur notifikasi).*

3. **Install Dependencies (Composer):**
   Jika Anda **sudah memiliki** Composer di komputer lokal:
   ```bash
   composer install
   ```
   Jika **tidak memiliki** Composer (menggunakan Docker sepenuhnya):
   - **Windows (PowerShell):**
     ```powershell
     docker run --rm -u "$((Get-Item .).CreationTime.ToString('u'))" -v "${PWD}:/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
     ```
   - **Mac/Linux:**
     ```bash
     docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
     ```

4. **Jalankan Docker Container (Sail):**
   ```bash
   ./vendor/bin/sail up -d
   ```
   *(Untuk pengguna Windows, Anda bisa menjalankannya via WSL atau pastikan Docker Desktop berjalan).*

5. **Generate Application Key & Database:**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```
   *(Perintah `--seed` di atas otomatis membuatkan akun Admin & Mahasiswa beserta data alat dummy).*

6. **Install NPM & Build Frontend (Vite):**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build
   ```
   *(Atau gunakan `./vendor/bin/sail npm run dev` jika ingin mode development).*

7. **Selesai! 🎉**
   Buka browser Anda dan akses: **http://localhost**

---

## 🚀 Cara Instalasi & Menjalankan (Tanpa Docker / Menggunakan XAMPP / Laragon)

Jika Anda terbiasa menggunakan XAMPP atau Laragon, ikuti langkah berikut:

1. **Clone Repository:**
   ```bash
   git clone https://github.com/jioodiff/software_process_lms.git
   cd software_process_lms
   ```

2. **Install Composer:**
   ```bash
   composer install
   ```

3. **Copy dan Atur `.env`:**
   ```bash
   cp .env.example .env
   ```
   Buka file `.env`, lalu atur konfigurasi database Anda (sesuaikan dengan pengaturan phpMyAdmin/MySQL Anda):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_lab_management_system
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Buat Database Baru:**
   Buka phpMyAdmin atau software database Anda, dan **buat database baru** dengan nama `db_lab_management_system`.

5. **Generate Key, Link Storage & Migrate:**
   Jalankan perintah ini berurutan:
   ```bash
   php artisan key:generate
   php artisan storage:link
   php artisan migrate:fresh --seed
   ```
   *(Pastikan perintah `storage:link` berhasil dijalankan agar gambar alat bisa tampil di aplikasi).*

6. **Install NPM & Build Frontend:**
   ```bash
   npm install
   npm run build
   ```

7. **Jalankan Local Server:**
   ```bash
   php artisan serve
   ```

8. **Selesai! 🎉**
   Buka browser dan akses alamat yang diberikan (biasanya **http://localhost:8000**).

---

## 🔑 Akun Login (Otomatis Dibuat oleh Seeder)

Anda bisa menggunakan kredensial berikut untuk *login* ke dalam sistem setelah menjalankan instalasi di atas.

**Akun Administrator:**
- **Email:** `admin@lms.com`
- **Password:** `password`

**Akun Mahasiswa:**
- **Email:** `mahasiswa@lms.com`
- **Password:** `password`

---

## ✨ Fitur Utama
- **Autentikasi & Otorisasi** berbasis Role (Admin & Mahasiswa).
- **Katalog Alat Interaktif** lengkap dengan ketersediaan stok *real-time*.
- **Peminjaman & Pengembalian Alat** dengan validasi kondisi alat (Baik, Rusak, Hilang).
- **Audit Log Terpusat (Append-Only)** untuk melacak aktivitas sistem.
- **Pusat Laporan & Ekspor Data** ke CSV.
- **Notifikasi Terintegrasi** menggunakan n8n Webhook.
