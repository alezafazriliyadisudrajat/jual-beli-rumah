<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Contract;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    /**
     * Mengajukan minat beli properti (dimulai dari fase negosiasi).
     */
    public function proposeTransaction(array $data, int $buyerId)
    {
        DB::beginTransaction();
        try {
            $property = Property::findOrFail($data['property_id']);

            if ($property->status !== 'active' || $property->listing_type !== 'jual') {
                throw new \Exception("Properti tidak tersedia untuk dibeli.");
            }

            # Booking fee biasanya sebesar 1% dari harga penawaran yang disepakati
            $agreedPrice = $data['agreed_price'] ?? $property->price;
            $bookingFee = $agreedPrice * 0.01;

            $transaction = Transaction::create([
                'property_id' => $property->id,
                'buyer_id' => $buyerId,
                'agreed_price' => $agreedPrice,
                'booking_fee' => $bookingFee,
                'status' => 'negosiasi', # Dimulai sebagai fase negosiasi
            ]);

            AuditLogService::log($buyerId, 'PROPOSE_TRANSACTION', "Mengajukan minat beli properti ID {$property->id} seharga Rp" . number_format($agreedPrice, 2));
            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in TransactionService@proposeTransaction: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memperbarui status transaksi (negosiasi, booking, menunggu pelunasan, lunas, batal).
     */
    public function updateStatus(Transaction $transaction, string $status, int $userId)
    {
        DB::beginTransaction();
        try {
            if (!in_array($status, ['negosiasi', 'booking', 'menunggu_pelunasan', 'lunas', 'batal'])) {
                throw new \Exception("Status transaksi tidak valid.");
            }

            $transaction->update(['status' => $status]);

            # Jika status adalah booking (artinya pemilik menyetujui penawaran dan pembeli membayar booking fee)
            if ($status === 'booking') {
                # Buat placeholder kontrak digital sederhana (PPJB)
                if (!$transaction->contract_id) {
                    $contract = Contract::create([
                        'contract_number' => 'PPJB/' . date('Ymd') . '/' . $transaction->id,
                        'file_path' => null, # File PDF kontrak dibuat nanti
                        'signed_at' => now(),
                    ]);
                    $transaction->update(['contract_id' => $contract->id]);
                }
            }

            # Jika status lunas (selesai)
            if ($status === 'lunas') {
                $transaction->property->update(['status' => 'sold']);
            } elseif ($status === 'batal') {
                $transaction->property->update(['status' => 'active']);
            }

            AuditLogService::log($userId, 'UPDATE_TRANSACTION_STATUS', "Mengubah status transaksi ID {$transaction->id} menjadi {$status}");
            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in TransactionService@updateStatus: " . $e->getMessage());
            throw $e;
        }
    }
}
