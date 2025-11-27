# SETUP AWAL - QUICK START GUIDE

## 1. Konfigurasi User Model

Edit file `app/Models/User.php` dan tambahkan kolom `role`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',  // ADD THIS
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
```

## 2. Update Migration Users Table

Edit file `database/migrations/0001_01_01_000000_create_users_table.php`:

Tambahkan kolom `role` di dalam `Schema::create('users', ...)`:

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('role', ['student', 'teacher', 'admin'])->default('student'); // ADD THIS
    $table->rememberToken();
    $table->timestamps();
});
```

## 3. Daftar Policy di AuthServiceProvider

Edit file `app/Providers/AuthServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Portfolio;
use App\Policies\PortfolioPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Portfolio::class => PortfolioPolicy::class,  // ADD THIS LINE
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
```

## 4. Jalankan Semua Setup Commands

```bash
# Navigate ke project directory
cd c:\xampp\htdocs\web-fahmi

# 1. Update database users table dengan role
php artisan migrate

# 2. Link storage untuk file upload
php artisan storage:link

# 3. Jalankan seeder (opsional, untuk testing)
php artisan db:seed --class=PortfolioSeeder

# 4. Cache clear
php artisan cache:clear
php artisan view:clear

# 5. Restart server (jika sudah berjalan)
```

## 5. Membuat User untuk Testing

```bash
# Buka Laravel Tinker
php artisan tinker

# Di dalam Tinker command line, jalankan:

# Buat admin user
User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin']);

# Buat teacher user
User::create(['name' => 'Guru Budi', 'email' => 'guru@example.com', 'password' => bcrypt('password'), 'role' => 'teacher']);

# Buat student user (link ke siswa ID 1)
$student = Student::find(1);
User::create(['name' => $student->name, 'email' => 'siswa@example.com', 'password' => bcrypt('password'), 'role' => 'student']);

# Exit Tinker
exit
```

Atau lebih mudah, jalankan di terminal:

```bash
php artisan tinker
```

Kemudian copy-paste commands di atas satu per satu.

## 6. Jika Sudah Ada Auth System

Jika Anda sudah memiliki authentication system (sudah ada user yang registered), 
cukup tambahkan `role` column ke database users:

```bash
# Buat migration baru
php artisan make:migration add_role_to_users_table

# Edit file migration yang baru dibuat, tambahkan:
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['student', 'teacher', 'admin'])->default('student')->after('password');
});

# Jalankan migration
php artisan migrate
```

## 7. Struktur File yang Sudah Dibuat

Berikut file-file yang sudah disiapkan:

### Database
- ✅ `database/migrations/2025_11_27_011457_create_students_table.php`
- ✅ `database/migrations/2025_11_27_011457_create_portfolios_table.php`
- ✅ `database/seeders/PortfolioSeeder.php`

### Models
- ✅ `app/Models/Student.php`
- ✅ `app/Models/Portfolio.php`

### Controllers
- ✅ `app/Http/Controllers/PortfolioController.php`

### Requests
- ✅ `app/Http/Requests/StorePortfolioRequest.php`
- ✅ `app/Http/Requests/UpdatePortfolioRequest.php`

### Policies
- ✅ `app/Policies/PortfolioPolicy.php`

### Views
- ✅ `resources/views/layouts/app.blade.php`
- ✅ `resources/views/portfolios/index.blade.php`
- ✅ `resources/views/portfolios/create.blade.php`
- ✅ `resources/views/portfolios/edit.blade.php`
- ✅ `resources/views/portfolios/show.blade.php`

### Routes
- ✅ `routes/web.php` (sudah diupdate)

### Documentation
- ✅ `DOKUMENTASI_PORTFOLIO.md`
- ✅ `SETUP_AWAL.md` (file ini)

## 8. File Folder Structure

Pastikan folder ini ada (akan auto-created saat pertama kali upload):

```
storage/
└── app/
    └── public/
        └── portfolios/  ← File upload akan disimpan di sini
```

Setelah `php artisan storage:link`, file bisa diakses di:
```
/storage/portfolios/filename.pdf
```

## 9. Environment Setup

Di `.env` file, pastikan:

```env
APP_NAME="Portfolio Siswa"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_fahmi
DB_USERNAME=root
DB_PASSWORD=

# File Storage
FILESYSTEM_DISK=public
```

## 10. Testing Endpoints

Setelah setup selesai, test endpoints:

```
1. Buka: http://localhost:8000/portfolios
   - Jika redirect ke login, authentication sudah bekerja ✓

2. Login dengan user yang sudah dibuat

3. Test CRUD:
   - GET /portfolios → List portfolio
   - GET /portfolios/create → Form create
   - POST /portfolios → Submit create (dengan file upload)
   - GET /portfolios/1 → Detail portfolio
   - GET /portfolios/1/edit → Form edit
   - PUT /portfolios/1 → Submit edit
   - DELETE /portfolios/1 → Delete (auth check)

4. Test authorization:
   - Sebagai siswa, coba akses portfolio siswa lain (harus forbidden)
   - Sebagai guru, coba verify portfolio (harus berhasil)
```

## 11. Troubleshooting Umum

### Error 1: "Class 'App\Models\Portfolio' not found"
```bash
# Clear autoload cache
composer dump-autoload
```

### Error 2: "Migration table not found"
```bash
# Jalankan migration
php artisan migrate

# Atau reset semua dan jalankan ulang
php artisan migrate:refresh
```

### Error 3: "File upload tidak bekerja"
```bash
# Pastikan storage link sudah dibuat
php artisan storage:link

# Pastikan folder writable
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Error 4: "Policy class not found"
```php
// Di AuthServiceProvider.php, pastikan sudah ditambahkan:
protected $policies = [
    Portfolio::class => PortfolioPolicy::class,
];

// Lalu jalankan:
php artisan cache:clear
```

### Error 5: "Unauthorized" saat verifikasi
```
Pastikan user memiliki role: 'teacher' atau 'admin'
Check di database: SELECT * FROM users WHERE email='guru@example.com'
```

## 12. Command Reference

```bash
# Development
php artisan serve                           # Start dev server
php artisan tinker                         # Interactive shell

# Database
php artisan migrate                        # Run migrations
php artisan migrate:refresh                # Reset & migrate
php artisan migrate:reset                  # Rollback all
php artisan db:seed                        # Run seeders
php artisan db:seed --class=PortfolioSeeder  # Run specific seeder

# Cache
php artisan cache:clear                    # Clear application cache
php artisan view:clear                     # Clear view cache
php artisan config:clear                   # Clear config cache

# Storage
php artisan storage:link                   # Create storage link

# Code
composer dump-autoload                     # Reload autoloader
```

## 13. Next Steps

Setelah semua setup selesai:

1. ✅ Test semua CRUD operations
2. ✅ Test file upload (size, format validation)
3. ✅ Test authorization (siswa, guru, admin)
4. ✅ Test search & filter
5. ✅ Test verification flow
6. ✅ Test error handling

Silakan hubungi jika ada pertanyaan atau error yang tidak terdaftar di atas!

---

**Last Updated: 27 November 2025**
