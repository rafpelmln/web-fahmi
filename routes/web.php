<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Jika user sudah login arahkan ke dashboard, jika belum tampilkan form login di root
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Admin User Management (routes prefixed with /admin, names prefixed with admin.)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', App\Http\Controllers\UserController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Portfolio Management Routes
    Route::resource('portfolios', PortfolioController::class);
    
    // Route untuk verifikasi portfolio (hanya guru/admin)
    Route::post('portfolios/{portfolio}/verify', [PortfolioController::class, 'verify'])
        ->middleware('can:verify,portfolio')
        ->name('portfolios.verify');
});

require __DIR__.'/auth.php';
