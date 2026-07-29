<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;

# Rute Publik
Route::get('/', [PropertyController::class, 'index'])->name('home');
Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('properties.show');

# Rute Otentikasi (Khusus Tamu/Guest)
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

# Rute Terotentikasi (Harus Login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    # Tindakan Properti
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::put('/properties/{id}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::post('/properties/{id}/favorite', [PropertyController::class, 'toggleFavorite'])->name('properties.favorite');

    # Transaksi Sewa & Jual Beli
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::patch('/transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');

    # Ulasan & Komentar
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    # Polling & Chat Real-time
    Route::post('/chat/start', [ChatController::class, 'startConversation'])->name('chat.start');
    Route::get('/chat/conversations', [ChatController::class, 'getUserConversations']);
    Route::get('/chat/conversations/{id}/messages', [ChatController::class, 'getMessages']);
    Route::post('/chat/conversations/{id}/messages', [ChatController::class, 'sendMessage']);
    Route::post('/chat/ping', [ChatController::class, 'ping']);

    # Simulasi Pembayaran Mock untuk AJAX
    Route::post('/payments/charge', [PaymentController::class, 'charge']);
    Route::post('/payments/webhook', [PaymentController::class, 'webhook']);

    # Kontrol Admin & Moderasi
    Route::post('/admin/properties/{id}/moderate', [AdminController::class, 'moderateProperty']);
    Route::post('/admin/users/{id}/verify', [AdminController::class, 'verifyUser']);
    Route::post('/admin/users/{id}/ban', [AdminController::class, 'toggleBan']);
    Route::get('/admin/reports/export', [AdminController::class, 'exportReport'])->name('admin.reports.export');

    # Kontrol Admin - Manajemen Master Data
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/admin/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{id}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    Route::post('/admin/locations', [AdminController::class, 'storeLocation'])->name('admin.locations.store');
    Route::put('/admin/locations/{id}', [AdminController::class, 'updateLocation'])->name('admin.locations.update');
    Route::delete('/admin/locations/{id}', [AdminController::class, 'destroyLocation'])->name('admin.locations.destroy');

    # Unduh Kontrak & Invoice
    Route::get('/contracts/{id}/download', [TransactionController::class, 'downloadContract'])->name('contracts.download');
    Route::get('/bookings/{id}/contract', [BookingController::class, 'downloadContract'])->name('bookings.contract');
    Route::get('/payments/{id}/invoice', [PaymentController::class, 'downloadInvoice'])->name('payments.invoice');
});
