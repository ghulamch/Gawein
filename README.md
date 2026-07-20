<div align="center">

# 🔧 GAWEIN
### Gabungkan Warga, Kerja Inklusif

**Platform digital yang menjembatani pekerja informal dan pemberi kerja di seluruh Indonesia**

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

</div>

---

## 📌 Tentang GAWEIN

**GAWEIN** adalah platform kerja informal berbasis web yang dirancang khusus untuk menghubungkan pekerja dan pemberi kerja di Indonesia, termasuk daerah dengan akses internet terbatas. Tidak menggunakan GPS atau radius — pencocokan dilakukan berbasis **domisili kota** agar lebih inklusif dan hemat data.

> *"Bukan sekadar job portal — GAWEIN membangun ekosistem kepercayaan antara pekerja dan pemberi kerja informal."*

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🔍 **Pencarian by Domisili** | Cari lowongan atau kandidat berdasarkan nama kota, tanpa GPS |
| 💰 **Estimasi Upah Wajar** | Rekomendasi gaji otomatis dari rata-rata historis per kategori |
| ✅ **Verifikasi KTP** | Verifikasi identitas via upload KTP, disetujui oleh Admin |
| ⭐ **Rating Dua Arah** | Pekerja dan pemberi kerja saling memberi ulasan setelah pekerjaan selesai |
| 💬 **Chat Langsung** | Komunikasi langsung antar pengguna dalam platform |
| 🔑 **OTP Reset Password** | Reset sandi via kode OTP 6 digit tanpa ketergantungan email |
| 🏢 **Profil Perusahaan** | Halaman profil publik dengan foto sampul, logo, dan deskripsi |
| 📋 **Manajemen Lamaran** | Pemberi kerja bisa ubah status lamaran (Pending → Interview → Diterima) |
| 📊 **Panel Admin** | Dashboard admin untuk verifikasi pengguna dan memantau aktivitas |

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────┐
│               LAPISAN KLIEN                 │
│   Pencari Kerja │ Pemberi Kerja │ Admin      │
└──────────────────────┬──────────────────────┘
                       │ HTTP Request
┌──────────────────────▼──────────────────────┐
│          ROUTING & MIDDLEWARE               │
│   web.php │ Auth Guard │ Role Check │ CSRF  │
└──────────────────────┬──────────────────────┘
                       │
┌──────────────────────▼──────────────────────┐
│            LAPISAN APLIKASI                 │
│  AuthController │ JobController │ Review... │
└──────────────────────┬──────────────────────┘
                       │ Eloquent ORM
┌──────────────────────▼──────────────────────┐
│              LAPISAN DATA                   │
│   MySQL Database │ Local Storage │ Session  │
└─────────────────────────────────────────────┘
```

![Arsitektur Sistem](public/docs/architecture_diagram.png)
![Use Case Diagram](public/docs/use_case_diagram.png)

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| **Framework** | Laravel 10 (PHP 8.4) |
| **Templating** | Blade Template Engine |
| **ORM** | Eloquent ORM |
| **Database** | MySQL 8 |
| **Styling** | Vanilla CSS (Glassmorphism) |
| **Icons** | Font Awesome 6 |
| **Alert UI** | SweetAlert2 |
| **Image Processing** | GD Library (PHP built-in) |
| **Auth** | Laravel Session + OTP |

---

## 🚀 Cara Instalasi

### Prasyarat

- PHP >= 8.1
- Composer
- MySQL
- Node.js (opsional)

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/username/gawein.git
cd gawein

# 2. Install dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gawein
DB_USERNAME=root
DB_PASSWORD=

# 6. Jalankan migrasi dan seeder
php artisan migrate --seed

# 7. Jalankan server lokal
php artisan serve
```

Akses aplikasi di: **http://127.0.0.1:8000**

---

## 👤 Akun Demo (Seeder)

| Role | Email | Password |
|---|---|---|
| 🛡️ Admin | `admin@gawein.com` | `password` |
| 🏢 Pemberi Kerja | `employer@gawein.com` | `password` |
| 👷 Pencari Kerja | `jobseeker@gawein.com` | `password` |

---

## 📁 Struktur Direktori

```
gawein/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── ReviewController.php
│   │   ├── Employer/
│   │   │   ├── JobController.php
│   │   │   ├── CandidateController.php
│   │   │   └── ProfileController.php
│   │   ├── Jobseeker/
│   │   │   └── JobController.php
│   │   └── Admin/
│   └── Models/
│       ├── User.php, Job.php, Application.php
│       ├── Transaction.php, Review.php, Message.php
│       └── CompanyProfile.php, SeekerProfile.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── css/style.css
│   ├── uploads/
│   └── docs/
│       ├── use_case_diagram.png
│       └── architecture_diagram.png
└── resources/views/
    ├── layouts/dashboard.blade.php
    ├── auth/  (login, register, otp)
    ├── dashboard/employer/
    ├── dashboard/jobseeker/
    └── welcome.blade.php
```

---

## 🗃️ Skema Database (Ringkas)

| Tabel | Fungsi |
|---|---|
| `users` | Akun pengguna (role: jobseeker / employer / admin) |
| `seeker_profiles` | Profil & resume pencari kerja |
| `company_profiles` | Profil perusahaan pemberi kerja |
| `ktp_verifications` | Verifikasi identitas KTP |
| `jobs` | Lowongan (kategori, gaji, domisili, kuota) |
| `applications` | Lamaran pekerjaan |
| `transactions` | Bukti perjanjian kerja otomatis |
| `reviews` | Ulasan & rating dua arah |
| `messages` | Chat langsung antar pengguna |
| `saved_jobs` | Lowongan yang disimpan |

---

<div align="center">

Dibuat dengan ❤️ untuk pekerja informal Indonesia

</div>
