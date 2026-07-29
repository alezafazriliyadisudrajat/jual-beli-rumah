<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    /**
     * Membuat booking sewa properti.
     */
    public function createBooking(array $data, int $tenantId)
    {
        DB::beginTransaction();
        try {
            $property = Property::findOrFail($data['property_id']);

            # Validasi apakah properti aktif dan merupakan tipe sewa
            if ($property->status !== 'active' || !str_contains($property->listing_type, 'sewa')) {
                throw new \Exception("Properti tidak tersedia untuk disewa.");
            }

            $startDate = new \DateTime($data['start_date']);
            $endDate = new \DateTime($data['end_date']);
            $diff = $startDate->diff($endDate);

            # Kalkulasi durasi dan harga berdasarkan tipe durasi
            $durationType = $data['duration_type']; # bulanan, tahunan
            $months = ($diff->y * 12) + $diff->m;
            $years = $diff->y;

            if ($durationType === 'bulanan') {
                $duration = $months <= 0 ? 1 : $months;
                $totalPrice = $property->price * $duration;
                # Deposit biasanya 0.5 kali harga untuk sewa bulanan sebagai jaminan
                $deposit = $property->price * 0.5;
            } else {
                $duration = $years <= 0 ? 1 : $years;
                # Harga sewa tahunan
                $totalPrice = $property->price * 12 * $duration;
                $deposit = $property->price * 1;
            }

            $booking = Booking::create([
                'property_id' => $property->id,
                'tenant_id' => $tenantId,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'duration_type' => $durationType,
                'total_price' => $totalPrice,
                'deposit' => $deposit,
                'status' => 'menunggu', # menunggu konfirmasi pemilik
            ]);

            AuditLogService::log($tenantId, 'CREATE_BOOKING', "Mengajukan sewa properti ID {$property->id} dengan total Rp" . number_format($totalPrice, 2));
            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in BookingService@createBooking: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memperbarui status booking.
     */
    public function updateStatus(Booking $booking, string $status, int $userId)
    {
        DB::beginTransaction();
        try {
            # Validasi transisi status
            if (!in_array($status, ['disetujui', 'aktif', 'selesai', 'dibatalkan'])) {
                throw new \Exception("Status booking tidak valid.");
            }

            $booking->update(['status' => $status]);

            # Jika status disetujui (pemilik menyetujui, pembeli perlu bayar deposit)
            if ($status === 'disetujui') {
                if (!$booking->contract_id) {
                    $contract = Contract::create([
                        'contract_number' => 'SEWA/' . date('Ymd') . '/' . $booking->id,
                        'file_path' => null,
                        'signed_at' => now(),
                    ]);
                    $booking->update(['contract_id' => $contract->id]);
                }
            }

            # Jika aktif, perbarui status properti menjadi rented (disewakan)
            if ($status === 'aktif') {
                $booking->property->update(['status' => 'rented']);
            } elseif ($status === 'selesai' || $status === 'dibatalkan') {
                $booking->property->update(['status' => 'active']);
            }

            AuditLogService::log($userId, 'UPDATE_BOOKING_STATUS', "Mengubah status sewa ID {$booking->id} menjadi {$status}");
            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in BookingService@updateStatus: " . $e->getMessage());
            throw $e;
        }
    }
}
