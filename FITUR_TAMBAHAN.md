# FITUR TAMBAHAN & ENHANCEMENT

Dokumentasi ini berisi fitur-fitur tambahan yang bisa diimplementasikan ke sistem portfolio.

## 1. Export PDF Daftar Portfolio

### Setup Package

```bash
composer require barryvdh/laravel-dompdf
```

### Create Export Controller Method

Edit `app/Http/Controllers/PortfolioController.php` dan tambahkan method:

```php
use Barryvdh\DomPDF\Facade\Pdf;

public function exportPdf(Request $request)
{
    // Check authorization
    if (auth()->user()->role !== 'teacher' && auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    // Get portfolios sesuai filters
    $query = Portfolio::query();

    if ($status = $request->get('status')) {
        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('verified_status', $status);
        }
    }

    if ($type = $request->get('type')) {
        if (in_array($type, ['prestasi', 'karya', 'sertifikat'])) {
            $query->where('type', $type);
        }
    }

    $portfolios = $query->with('student', 'verifiedByUser')
                        ->latest('created_at')
                        ->get();

    // Create PDF
    $pdf = Pdf::loadView('portfolios.export-pdf', [
        'portfolios' => $portfolios,
        'generatedAt' => now(),
    ]);

    return $pdf->download('portfolio-list-' . now()->format('Y-m-d-H-i-s') . '.pdf');
}
```

### Create Export View

Buat file: `resources/views/portfolios/export-pdf.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Portfolio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 10px;
        }
        .info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        .badge-approved {
            background-color: #28a745;
        }
        .badge-rejected {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <h1>📋 Daftar Portfolio Siswa</h1>
    <div class="info">
        <p>Laporan Daftar Portfolio | Dihasilkan: {{ $generatedAt->format('d/m/Y H:i') }}</p>
        <p>Total Portfolio: {{ $portfolios->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Nama Siswa</th>
                <th style="width: 20%">Judul Portfolio</th>
                <th style="width: 10%">Tipe</th>
                <th style="width: 15%">Status</th>
                <th style="width: 15%">Diverifikasi Oleh</th>
                <th style="width: 10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($portfolios as $key => $portfolio)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $portfolio->student->name }}
                    <br>
                    <small>({{ $portfolio->student->nis }})</small>
                </td>
                <td>{{ $portfolio->title }}</td>
                <td>
                    @if($portfolio->type === 'prestasi')
                        Prestasi
                    @elseif($portfolio->type === 'karya')
                        Karya
                    @else
                        Sertifikat
                    @endif
                </td>
                <td>
                    <span class="badge badge-{{ $portfolio->verified_status }}">
                        @if($portfolio->verified_status === 'pending')
                            Pending
                        @elseif($portfolio->verified_status === 'approved')
                            Disetujui
                        @else
                            Ditolak
                        @endif
                    </span>
                </td>
                <td>
                    {{ $portfolio->verifiedByUser->name ?? '-' }}
                </td>
                <td>{{ $portfolio->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #999;">
                    Tidak ada data portfolio
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center; font-size: 11px; color: #999;">
        <p>Sistem Portfolio Siswa - {{ now()->year }}</p>
    </div>
</body>
</html>
```

### Add Route

Di `routes/web.php`:

```php
Route::middleware('auth')->group(function () {
    Route::resource('portfolios', PortfolioController::class);
    Route::post('portfolios/{portfolio}/verify', [PortfolioController::class, 'verify'])
        ->middleware('can:verify,portfolio')
        ->name('portfolios.verify');
    
    // Add export route
    Route::get('portfolios/export/pdf', [PortfolioController::class, 'exportPdf'])
        ->middleware('can:viewAny,App\Models\Portfolio')
        ->name('portfolios.export.pdf');
});
```

### Add Button di View

Di `resources/views/portfolios/index.blade.php`, tambahkan di header:

```blade
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-folder-open"></i> Daftar Portfolio
    </h2>
    <div>
        @can('create', App\Models\Portfolio::class)
        <a href="{{ route('portfolios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Portfolio Baru
        </a>
        @endcan
        
        @can('viewAny', App\Models\Portfolio::class)
        <a href="{{ route('portfolios.export.pdf') }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        @endcan
    </div>
</div>
```

---

## 2. Notifikasi Email Verifikasi

### Create Event

```bash
php artisan make:event PortfolioVerified
```

Edit `app/Events/PortfolioVerified.php`:

```php
<?php

namespace App\Events;

use App\Models\Portfolio;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PortfolioVerified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Portfolio $portfolio,
        public string $status
    ) {}
}
```

### Create Mailable

```bash
php artisan make:mail PortfolioVerificationMail
```

Edit `app/Mail/PortfolioVerificationMail.php`:

```php
<?php

namespace App\Mail;

use App\Models\Portfolio;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PortfolioVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Portfolio $portfolio,
        public string $status
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved' 
            ? 'Portfolio Anda Disetujui ✓' 
            : 'Portfolio Anda Ditolak ✗';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.portfolio-verification',
            with: [
                'portfolio' => $this->portfolio,
                'status' => $this->status,
                'portalUrl' => url('/portfolios/' . $this->portfolio->id),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
```

### Create Listener

```bash
php artisan make:listener SendPortfolioVerificationNotification --event=PortfolioVerified
```

Edit `app/Listeners/SendPortfolioVerificationNotification.php`:

```php
<?php

namespace App\Listeners;

use App\Events\PortfolioVerified;
use App\Mail\PortfolioVerificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPortfolioVerificationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PortfolioVerified $event): void
    {
        Mail::to($event->portfolio->student->user->email)
            ->send(new PortfolioVerificationMail($event->portfolio, $event->status));
    }
}
```

### Register Listener

Di `app/Providers/EventServiceProvider.php`:

```php
protected $listen = [
    PortfolioVerified::class => [
        SendPortfolioVerificationNotification::class,
    ],
];
```

### Update Controller

Di `PortfolioController@verify()`, tambahkan sebelum redirect:

```php
// Fire event untuk mengirim email
PortfolioVerified::dispatch($portfolio, $validated['verified_status']);
```

### Create Email Template

Buat file: `resources/views/emails/portfolio-verification.blade.php`

```blade
<x-mail::message>
# Portfolio Anda Telah Diverifikasi

Halo {{ $portfolio->student->name }},

Portfolio Anda dengan judul **"{{ $portfolio->title }}"** telah diverifikasi oleh guru.

**Status**: 
@if($status === 'approved')
✓ **DISETUJUI** - Portfolio Anda sudah valid dan dapat dipampang di portofolio publik.
@else
✗ **DITOLAK** - Silakan perbaiki dan submit ulang portfolio Anda.
@endif

<x-mail::button :url="$portalUrl">
Lihat Portfolio
</x-mail::button>

Terima kasih,  
Tim Portfolio Siswa
</x-mail::message>
```

---

## 3. Soft Delete Portfolio

### Update Migration

Di `database/migrations/2025_11_27_011457_create_portfolios_table.php`, tambahkan:

```php
$table->softDeletes();
```

### Update Model

Di `app/Models/Portfolio.php`:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
}
```

### Create Migration untuk Add Soft Delete

```bash
php artisan make:migration add_soft_deletes_to_portfolios_table
```

Edit migration:

```php
Schema::table('portfolios', function (Blueprint $table) {
    $table->softDeletes();
});
```

### Jalankan

```bash
php artisan migrate
```

### Restore di Policy

Di `app/Policies/PortfolioPolicy.php`, update:

```php
public function restore(User $user, Portfolio $portfolio): bool
{
    return $user->role === 'admin';
}

public function forceDelete(User $user, Portfolio $portfolio): bool
{
    return $user->role === 'admin';
}
```

---

## 4. Comments/Review pada Portfolio

### Create Models & Migrations

```bash
php artisan make:model PortfolioComment -m
```

Edit migration `database/migrations/xxxx_create_portfolio_comments_table.php`:

```php
Schema::create('portfolio_comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('portfolio_id')->constrained('portfolios')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->text('comment');
    $table->integer('rating')->nullable()->min(1)->max(5);
    $table->timestamps();
    
    $table->index('portfolio_id');
    $table->index('user_id');
});
```

### Create Model

Edit `app/Models/PortfolioComment.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioComment extends Model
{
    protected $fillable = [
        'portfolio_id',
        'user_id',
        'comment',
        'rating',
    ];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

### Add to Portfolio Model

Di `app/Models/Portfolio.php`:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function comments(): HasMany
{
    return $this->hasMany(PortfolioComment::class);
}
```

---

## 5. Share Portfolio (Public Link)

### Add UUID ke Portfolio

Edit migration portfolios:

```php
$table->uuid('share_token')->unique()->nullable();
```

### Add to Model

Di `app/Models/Portfolio.php`:

```php
use Illuminate\Support\Str;

protected static function booted()
{
    static::creating(function ($portfolio) {
        if (!$portfolio->share_token) {
            $portfolio->share_token = Str::uuid();
        }
    });
}

public function getShareUrlAttribute(): string
{
    return url("/portfolio-share/{$this->share_token}");
}
```

### Create Public View

Buat route di `routes/web.php`:

```php
Route::get('/portfolio-share/{token}', function ($token) {
    $portfolio = Portfolio::where('share_token', $token)->firstOrFail();
    return view('portfolios.public-show', compact('portfolio'));
});
```

---

## 6. Advanced Search dengan Elasticsearch (Optional)

Untuk aplikasi yang lebih besar dengan ribuan portfolio:

```bash
composer require elasticsearch/elasticsearch
```

Tapi untuk project kecil/menengah, query biasa sudah cukup.

---

## Implementation Priority

1. **High Priority** (Langsung implementasikan):
   - ✅ Soft Delete Portfolio
   - ✅ Export PDF

2. **Medium Priority** (Segera setelah testing):
   - Email Verification Notifications
   - Comments & Review

3. **Low Priority** (Untuk masa depan):
   - Public Share Link
   - Advanced Analytics
   - Mobile App Integration

---

**Documentation Created: 27 November 2025**
