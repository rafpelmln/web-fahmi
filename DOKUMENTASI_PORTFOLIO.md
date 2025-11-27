# Sistem Portfolio Siswa - Dokumentasi Lengkap

## A. Perintah Artisan Yang Harus Dijalankan

Berikut adalah perintah-perintah artisan yang perlu Anda jalankan untuk setup project:

### 1. Generate Files (Sudah Dilakukan)
```bash
php artisan make:model Student -m
php artisan make:model Portfolio -m
php artisan make:controller PortfolioController --resource
php artisan make:policy PortfolioPolicy --model=Portfolio
php artisan make:request StorePortfolioRequest
php artisan make:request UpdatePortfolioRequest
php artisan make:seeder PortfolioSeeder
```

### 2. Jalankan Migration
```bash
php artisan migrate
```

### 3. Jalankan Seeder (untuk data dummy)
```bash
php artisan db:seed --class=PortfolioSeeder
```

Atau jika ingin menjalankan semua seeder:
```bash
php artisan db:seed
```

### 4. Link Storage (untuk akses file upload)
```bash
php artisan storage:link
```

---

## B. Alur Kerja Sistem

### Flow Diagram:
```
INPUT USER
   ↓
FORM VALIDATION (StorePortfolioRequest/UpdatePortfolioRequest)
   ↓
FILE UPLOAD & VALIDATION
   ↓
SIMPAN KE DATABASE
   ↓
STATUS PENDING (default)
   ↓
GURU/ADMIN VERIFIKASI
   ↓
UPDATE STATUS (approved/rejected)
   ↓
NOTIFIKASI KE SISWA
```

### Detail Alur Kerja:

#### 1. **INPUT USER (Siswa membuat portfolio)**
   - Siswa login ke sistem
   - Klik "Buat Portfolio Baru"
   - Isi form: Judul, Deskripsi, Jenis, Upload File

#### 2. **VALIDASI (StorePortfolioRequest)**
   - **Validasi Authorization**: Pastikan user adalah siswa atau admin
   - **Validasi Input**:
     - Judul: 3-255 karakter (required)
     - Deskripsi: 10-5000 karakter (required)
     - Type: prestasi/karya/sertifikat (required)
     - File: PDF/JPG/PNG, maksimal 5MB (required)
   - **Sanitasi Input**: Hanya mengambil data yang diperlukan dari `validated()`

#### 3. **UPLOAD FILE (PortfolioController::store)**
   ```
   1. Validasi file sudah lolos di FormRequest
   2. Generate nama file yang aman:
      - Timestamp + nama file asli
      - Contoh: 1732638157_sertifikat_google.pdf
   3. Simpan ke: storage/app/public/portfolios/
   4. Store path ke database: portfolios/1732638157_sertifikat_google.pdf
   ```

#### 4. **SIMPAN KE DATABASE**
   - Buat record Portfolio baru
   - Set status: `verified_status = 'pending'`
   - Set student_id: ID siswa yang login
   - Redirect ke halaman detail portfolio

#### 5. **GURU MELAKUKAN VERIFIKASI**
   - Guru/Admin melihat portfolio di halaman index dengan status "pending"
   - Klik "Lihat" untuk melihat detail
   - Di halaman detail, ada form verifikasi:
     - Pilih: Approve atau Reject
     - Bisa tambah catatan (opsional)
   - Submit form verifikasi
   - Status diubah menjadi: `approved` atau `rejected`
   - Set `verified_by`: ID guru yang verifikasi
   - Set `verified_at`: Waktu verifikasi

#### 6. **AKSES KONTROL (PortfolioPolicy)**
   - **Siswa**:
     - Lihat: Hanya milik sendiri
     - Buat: Boleh
     - Edit: Hanya milik sendiri, status harus pending
     - Hapus: Hanya milik sendiri, status harus pending
   - **Guru**:
     - Lihat: Semua portfolio
     - Verifikasi: Boleh
   - **Admin**:
     - Akses penuh

---

## C. Struktur File yang Telah Dibuat

```
database/
├── migrations/
│   ├── 2025_11_27_011457_create_students_table.php    (✓ Dibuat)
│   └── 2025_11_27_011457_create_portfolios_table.php   (✓ Dibuat)
└── seeders/
    └── PortfolioSeeder.php                              (✓ Dibuat)

app/
├── Models/
│   ├── Student.php                                     (✓ Dibuat)
│   └── Portfolio.php                                   (✓ Dibuat)
├── Http/
│   ├── Controllers/
│   │   └── PortfolioController.php                     (✓ Dibuat)
│   └── Requests/
│       ├── StorePortfolioRequest.php                   (✓ Dibuat)
│       └── UpdatePortfolioRequest.php                  (✓ Dibuat)
└── Policies/
    └── PortfolioPolicy.php                             (✓ Dibuat)

resources/
└── views/
    ├── layouts/
    │   └── app.blade.php                               (✓ Dibuat)
    └── portfolios/
        ├── index.blade.php                             (✓ Dibuat)
        ├── create.blade.php                            (✓ Dibuat)
        ├── edit.blade.php                              (✓ Dibuat)
        └── show.blade.php                              (✓ Dibuat)

routes/
└── web.php                                             (✓ Diupdate)

storage/
└── app/public/portfolios/                              (Auto-created)
```

---

## D. Database Schema

### Tabel: students
```
id              (Primary Key)
nis             (VARCHAR, UNIQUE) - Nomor Induk Siswa
name            (VARCHAR) - Nama Siswa
class           (VARCHAR) - Kelas
created_at      (TIMESTAMP)
updated_at      (TIMESTAMP)

Index: nis, class
```

### Tabel: portfolios
```
id              (Primary Key)
student_id      (Foreign Key → students.id, onDelete=cascade)
title           (VARCHAR) - Judul Portfolio
description     (TEXT) - Deskripsi
type            (ENUM: prestasi, karya, sertifikat)
file_path       (VARCHAR) - Path file di storage
verified_status (ENUM: pending, approved, rejected)
verified_by     (Foreign Key → users.id, nullable, onDelete=set null)
verified_at     (TIMESTAMP, nullable)
created_at      (TIMESTAMP)
updated_at      (TIMESTAMP)

Index: student_id, verified_status, created_at
```

---

## E. API Routes

### Resource Routes (Auto-generated)
```
GET    /portfolios              → index()      - Daftar portfolio
GET    /portfolios/create       → create()     - Form buat portfolio
POST   /portfolios              → store()      - Simpan portfolio baru
GET    /portfolios/{id}         → show()       - Detail portfolio
GET    /portfolios/{id}/edit    → edit()       - Form edit portfolio
PUT    /portfolios/{id}         → update()     - Update portfolio
DELETE /portfolios/{id}         → destroy()    - Hapus portfolio
```

### Custom Routes
```
POST   /portfolios/{id}/verify  → verify()     - Verifikasi portfolio (guru/admin)
```

---

## F. Features Lengkap

### 1. ✅ CRUD Portfolio
- Create: Siswa bisa membuat portfolio baru
- Read: Lihat daftar dan detail portfolio
- Update: Edit portfolio yang statusnya pending
- Delete: Hapus portfolio yang statusnya pending

### 2. ✅ File Upload
- Simpan ke: `storage/app/public/portfolios/`
- Format: PDF, JPG, PNG
- Ukuran max: 5MB
- Nama file otomatis (aman dari XSS)
- Link download aman melalui route handler

### 3. ✅ Validasi
- Input sanitasi (prevent injection)
- File validation (mime type, size)
- Authorization checks (Policy)
- Error messages custom

### 4. ✅ Access Control (Policy)
- Siswa: Lihat/edit/hapus milik sendiri
- Guru: Lihat semua, verifikasi
- Admin: Akses penuh

### 5. ✅ Verifikasi Portfolio
- Guru verifikasi portfolio pending
- Set status: approved/rejected
- Track siapa yang verifikasi + kapan
- Akses terbatas dengan Gate

### 6. ✅ Pencarian & Filter
- Search: Judul atau nama siswa
- Filter: Status (pending/approved/rejected)
- Filter: Tipe (prestasi/karya/sertifikat)
- Query protection: Prevent wildcard injection

### 7. ✅ UI/UX
- Bootstrap 5 responsive
- Form validation client & server
- Status badges dengan warna
- File preview sebelum upload
- Timeline history verifikasi
- Modal confirm delete

---

## G. Keamanan

### 1. **Authorization**
```php
// Di controller
$this->authorize('view', $portfolio);     // Check policy

// Di routes
Route::post('/.../verify', [...])
    ->middleware('can:verify,portfolio')  // Middleware check
```

### 2. **File Upload Security**
```php
// Validasi di FormRequest
'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120']

// Simpan dengan nama aman
$fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
$filePath = $file->storeAs('portfolios', $fileName, 'public');

// Akses melalui symbolic link
php artisan storage:link
// Buka via: /storage/portfolios/filename
```

### 3. **Input Sanitasi**
```php
// Prevent XSS
{{ $portfolio->title }}  // Auto-escaped

// Prevent SQL Injection di search
$search = str_replace(['%', '_'], ['\\%', '\\_'], $search);
```

### 4. **CSRF Protection**
```blade
@csrf  <!-- Di setiap form -->
@method('DELETE')  <!-- Di form delete -->
```

---

## H. Testing Data (Seeder)

Seeder membuat:
- **5 Siswa** dengan NIS dan kelas berbeda
- **8 Portfolio** dengan berbagai status:
  - Pending: 2 portfolio
  - Approved: 4 portfolio
  - Rejected: 1 portfolio
- **Dummy files** untuk setiap portfolio

Jalankan:
```bash
php artisan db:seed --class=PortfolioSeeder
```

---

## I. Fitur Tambahan Yang Bisa Dikembangkan

1. **Export PDF Daftar Portfolio**
   ```php
   - Install: composer require barryvdh/laravel-dompdf
   - Route: GET /portfolios/export/pdf
   ```

2. **Notifikasi Email**
   ```php
   - Event: PortfolioVerified
   - Listener: SendVerificationNotification
   - Queue: untuk email async
   ```

3. **Soft Delete Portfolio**
   ```php
   - Model: use SoftDeletes
   - Migration: add soft_deletes()
   - Admin bisa restore
   ```

4. **Comment/Review pada Portfolio**
   ```php
   - Model: Comment
   - Guru bisa add review
   - Siswa melihat feedback
   ```

5. **Share Portfolio (Social)**
   ```php
   - Share link public
   - QR code generator
   - Social media integration
   ```

---

## J. Troubleshooting

### 1. File Upload Tidak Bekerja
```bash
# Pastikan folder writable
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Buat symbolic link
php artisan storage:link

# Pastikan .env sudah: APP_URL=http://localhost
```

### 2. Authorization Gagal
```bash
# Daftar policy di AuthServiceProvider
protected $policies = [
    \App\Models\Portfolio::class => \App\Policies\PortfolioPolicy::class,
];

# Di app/Providers/AuthServiceProvider.php
```

### 3. Database Migration Error
```bash
# Rollback dan migrate ulang
php artisan migrate:refresh
php artisan migrate

# Atau specific
php artisan migrate --path=/database/migrations/2025_11_27_011457_create_students_table.php
```

### 4. View Not Found
```bash
# Clear cache
php artisan view:clear

# Pastikan file ada di resources/views/portfolios/
```

---

## K. Quick Start

```bash
# 1. Jalankan migration
php artisan migrate

# 2. Link storage
php artisan storage:link

# 3. Jalankan seeder (optional, untuk testing)
php artisan db:seed --class=PortfolioSeeder

# 4. Start server
php artisan serve

# 5. Akses: http://localhost:8000/portfolios
```

---

## L. Checklist Implementasi

- [x] Migrations (Students & Portfolios)
- [x] Models (Student & Portfolio dengan relationships)
- [x] FormRequest (Store & Update dengan validasi)
- [x] Controller (CRUD + Verify method)
- [x] Policy (Authorization rules)
- [x] Routes (Resource + Custom)
- [x] Views (Index, Create, Edit, Show)
- [x] Layout (Bootstrap responsive)
- [x] Seeder (Dummy data)
- [x] File Upload (Secure storage)
- [x] Search & Filter
- [x] Access Control
- [x] Error Handling
- [x] Blade Security (Escape, CSRF)

---

**Dokumentasi dibuat pada: 27 November 2025**
**Siap untuk production dengan beberapa enhancement**
