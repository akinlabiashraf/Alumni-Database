# Summit University Alumni Database Platform

> Official Alumni Verification Engine, Directory & Administrative Control Center for **Summit University, Offa, Kwara State, Nigeria**.

[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL 8.0](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Tests Passing](https://img.shields.io/badge/Tests-53%20Passed-10B981?style=flat&logo=checkmarx&logoColor=white)](https://phpunit.de/)
[![NDPA 2023](https://img.shields.io/badge/NDPA-2023%20Compliant-006837?style=flat)](https://ndpc.gov.ng/)

---

## 🌟 Overview & Key Features

The Summit University Alumni Database is a centralized web platform designed to verify graduates, power networking, manage member profiles, and provide university administrators with tools to import records and moderate verifications.

- **Automated Verification Engine**: 2-step registration wizard matching matriculation numbers against the university's master academic archives with duplicate claim prevention.
- **Manual Verification Fallback**: Moderation queue for graduates whose records require physical review and approval.
- **Granular NDPA 2023 Privacy**: Per-field privacy visibility controls (Public, Alumni Only, Connections Only, Private), complete Data Portability export (`.json`), and Right to Erasure account deletion.
- **Alumni Directory & Search**: Real-time faceted filtering by Graduating Set, Faculty, Department, Industry, Location, and Skills.
- **Member Portal**: Interactive profile editor, timeline management, career history, avatar uploads, and set analytics.
- **Admin Directorate Portal**: Real-time analytics, bulk CSV graduate uploader with duplicate detection, downloadable CSV template, verification moderation queue, user role control, and system security audit logs.
- **Progressive Web App (PWA)**: Standalone mobile display support and offline asset caching via Service Worker.
- **Automated Backups**: Scheduled daily database backup snapshots (`php artisan summit:backup-database`).

---

## 🛠️ Technology Stack

- **Backend**: Laravel 12 on PHP 8.4, Laravel Sanctum (Token Auth)
- **Database**: MySQL 8.0 (`summit_alumni_db`)
- **Frontend**: Laravel Blade (`.blade.php`) reusable layouts + Vanilla JavaScript API client (`fetch()`)
- **Styling**: Summit University Brand Design System (Forest Green `#006837`, Emerald `#26ae61`, Gold `#D4AF37`) + Bootstrap 3 grid
- **Testing**: PHPUnit (53 Feature & Unit tests, 326 assertions)

---

## 🚀 Quick Start for Developers

### 1. Prerequisites
- **PHP** >= 8.3 (PHP 8.4 recommended) with `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `curl` extensions
- **Composer** >= 2.x
- **MySQL** >= 8.0 (or Laragon / XAMPP)
- **Git**

### 2. Clone Repository
```bash
git clone https://github.com/akinlabiashraf/Alumni-Database.git
cd Alumni-Database
```

### 3. Install Dependencies
```bash
composer install
```

### 4. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and configure your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=summit_alumni_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Create Database, Run Migrations & Seeders
Create database in MySQL:
```sql
CREATE DATABASE IF NOT EXISTS summit_alumni_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Run migrations and seed the authentic university faculties, departments, and graduate sets (2019–2025):
```bash
php artisan migrate:fresh --seed
```

### 6. Start the Development Server
```bash
php artisan serve
```
Open **[http://localhost:8000](http://localhost:8000)** in your browser.

---

## 🔑 Pre-Seeded Test Credentials

| Role | Email / Matriculation No. | Password | Description |
| :--- | :--- | :--- | :--- |
| **Pioneer Alumna** | `amina.bello@summituniversity.edu.ng`<br>`SU/19/NAS/001` | `password` | Verified alumna with complete profile, skills, and NDPA settings. |
| **Super Admin** | `admin@summituniversity.edu.ng` | `password` | Full administrative control center access (`/admin/dashboard`). |
| **Moderator** | `moderator@summituniversity.edu.ng` | `password` | Verification queue moderator (`/admin/verifications`). |

### Sample Unclaimed Matric Numbers for Registration Testing:
- `SU/19/MSS/005` — Olawale Kareem Balogun (B.Sc. Accounting, Class of 2019)
- `SU/20/NAS/014` — Ibrahim Babatunde Alabi (B.Sc. Computer Science, Class of 2020)
- `SU/21/MSS/032` — Zainab Ololade Adeleke (B.Sc. Accounting, Class of 2021)
- `SU/22/HUM/019` — Rashidat Abiola Ibrahim (B.A. English and Literary Studies, Class of 2022)

---

## 🧪 Running the Test Suite

Run the full automated test suite (53 tests):
```bash
php artisan test --testdox
```

To run individual test classes:
```bash
php artisan test --filter VerificationAndAuthTest
php artisan test --filter AdminPortalAndRecordsTest
php artisan test --filter SecurityAndNdpaComplianceTest
```

---

## 📦 Useful Commands

- **Database Backup Snapshot**:
  ```bash
  php artisan summit:backup-database
  ```
  *(Snapshots are archived in `storage/app/backups/`)*

- **Download Graduate CSV Template**:
  Visit `GET /api/admin/template-csv` or download from the Admin Records interface.

---

## 📄 License & Compliance

Developed for **Summit University, Offa, Kwara State, Nigeria**. Compliant with the **Nigeria Data Protection Act (NDPA) 2023**.
