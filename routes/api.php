<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;

# 5.1 Endpoint Autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

# Simulasi Lupa Kata Sandi
Route::post('/password/forgot', function () {
    return response()->json([
        'success' => true,
        'message' => 'Tautan atur ulang kata sandi berhasil dikirim ke email Anda.'
    ]);
});

# Rute API Terotentikasi (Memerlukan Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    # Otentikasi
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    # 5.2 Endpoint Properti
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{id}', [PropertyController::class, 'show']);
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::post('/properties/{id}/favorite', [PropertyController::class, 'toggleFavorite']);
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);

    # 5.3 Endpoint Transaksi & Sewa
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::patch('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus']);
    
    # Pembayaran
    Route::post('/payments/charge', [PaymentController::class, 'charge']);

    # 5.4 Endpoint Chat & Ulasan
    Route::get('/conversations', [ChatController::class, 'getUserConversations']);
    Route::get('/conversations/{id}/messages', [ChatController::class, 'getMessages']);
    Route::post('/conversations/{id}/messages', [ChatController::class, 'sendMessage']);
    Route::post('/reviews', [ReviewController::class, 'store']);
});

# Webhook Payment Gateway (Tanpa Autentikasi Sanctum)
Route::post('/payments/webhook', [PaymentController::class, 'webhook']);
