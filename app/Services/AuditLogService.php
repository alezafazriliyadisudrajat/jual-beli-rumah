<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

class AuditLogService
{
    /**
     * Mencatat tindakan ke log audit.
     */
    public static function log(?int $userId, string $action, string $description): void
    {
        try {
            AuditLog::create([
                'user_id' => $userId,
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Catat ke file log jika penulisan database gagal untuk mencegah kegagalan logika utama
            Log::error("Failed to write audit log: " . $e->getMessage(), [
                'user_id' => $userId,
                'action' => $action,
                'description' => $description,
            ]);
        }
    }
}
