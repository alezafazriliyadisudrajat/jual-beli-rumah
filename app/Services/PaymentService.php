<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Membuat tagihan pembayaran (simulasi pembuatan invoice Midtrans/Xendit).
     */
    public function charge(string $payableType, int $payableId, float $amount, string $method)
    {
        try {
            # Validasi keberadaan data yang ditagihkan
            $payable = $payableType === 'booking'
                ? Booking::findOrFail($payableId)
                : Transaction::findOrFail($payableId);

            $categoryName = strtolower($payable->property->category->name ?? '');
            $typeCode = 'PROP';
            if (str_contains($categoryName, 'rumah')) {
                $typeCode = 'RMH';
            } elseif (str_contains($categoryName, 'ruko')) {
                $typeCode = 'RKO';
            }

            $gatewayRef = 'GW-' . $typeCode . '-' . date('Ymd') . '-' . rand(1000, 9999);

            $payment = Payment::create([
                'payable_type' => $payableType === 'booking' ? Booking::class : Transaction::class,
                'payable_id' => $payableId,
                'amount' => $amount,
                'method' => $method,
                'gateway_reference' => $gatewayRef,
                'status' => 'pending',
            ]);

            AuditLogService::log($payableType === 'booking' ? $payable->tenant_id : $payable->buyer_id, 'CHARGE_PAYMENT', "Membuat tagihan pembayaran ID {$payment->id} sejumlah Rp" . number_format($amount, 2));
            return $payment;
        } catch (\Exception $e) {
            Log::error("Error in PaymentService@charge: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memproses webhook callback (simulasi penyelesaian pembayaran).
     */
    public function processWebhook(string $gatewayReference, string $status)
    {
        DB::beginTransaction();
        try {
            $payment = Payment::where('gateway_reference', $gatewayReference)->firstOrFail();

            if (!in_array($status, ['success', 'failed', 'expired'])) {
                throw new \Exception("Status pembayaran webhook tidak valid.");
            }

            $payment->update([
                'status' => $status,
                'paid_at' => $status === 'success' ? now() : null,
            ]);

            # Jika sukses, perbarui status transaksi/booking induk
            if ($status === 'success') {
                $payable = $payment->payable;

                if ($payable instanceof Booking) {
                    # Perbarui booking menjadi aktif
                    $payable->update(['status' => 'aktif']);
                    $payable->property->update(['status' => 'rented']);
                    AuditLogService::log($payable->tenant_id, 'PAYMENT_SUCCESS', "Pembayaran sewa ID {$payable->id} berhasil diverifikasi.");
                } elseif ($payable instanceof Transaction) {
                    if ($payable->status === 'negosiasi') {
                        # terbayar booking fee
                        $payable->update(['status' => 'booking']);
                        AuditLogService::log($payable->buyer_id, 'PAYMENT_SUCCESS', "Pembayaran booking fee transaksi ID {$payable->id} berhasil diverifikasi.");
                    } elseif ($payable->status === 'menunggu_pelunasan') {
                        # jumlahkan semua pembayaran sukses termasuk yang sekarang
                        $totalPaid = $payable->payments()->where('status', 'success')->sum('amount');
                        
                        if ($totalPaid >= ($payable->agreed_price - 0.01)) {
                            # terbayar lunas harga penuh
                            $payable->update(['status' => 'lunas']);
                            $payable->property->update(['status' => 'sold']);
                            AuditLogService::log($payable->buyer_id, 'PAYMENT_SUCCESS', "Pembayaran pelunasan transaksi ID {$payable->id} berhasil diverifikasi. Transaksi lunas.");
                        } else {
                            # terbayar sebagian / cicilan
                            $remaining = $payable->agreed_price - $totalPaid;
                            AuditLogService::log($payable->buyer_id, 'PAYMENT_SUCCESS', "Pembayaran cicilan Rp" . number_format($payment->amount, 0, ',', '.') . " untuk transaksi ID {$payable->id} berhasil diverifikasi. Sisa tagihan: Rp" . number_format($remaining, 0, ',', '.'));
                        }
                    }
                }
            }

            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in PaymentService@processWebhook: " . $e->getMessage());
            throw $e;
        }
    }
}
