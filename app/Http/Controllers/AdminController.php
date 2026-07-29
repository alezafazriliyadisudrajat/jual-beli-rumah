<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Property;
use App\Models\User;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Transaction;
use App\Services\AuditLogService;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    # Memoderasi listing properti (setuju/tolak).
    public function moderateProperty(Request $request, $id)
    {
        try {
            $admin = Auth::user();
            if (!$admin || !in_array($admin->role_id, [1, 2])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }

            $request->validate([
                'status' => 'required|in:active,rejected',
            ]);

            $property = Property::findOrFail($id);
            $updated = $this->propertyService->moderate($property, $request->status, $admin->id);

            return response()->json([
                'success' => true,
                'message' => "Properti berhasil diupdate ke status: {$request->status}.",
                'data' => $updated
            ]);
        } catch (\Exception $e) {
            Log::error("Error in AdminController@moderateProperty: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    # Verifikasi dokumen pemilik/agen.
    public function verifyUser(Request $request, $id)
    {
        try {
            $admin = Auth::user();
            if (!$admin || !in_array($admin->role_id, [1, 2])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }

            $request->validate([
                'is_verified' => 'required|boolean',
            ]);

            $user = User::findOrFail($id);
            if ($user->id === $admin->id) {
                return response()->json(['success' => false, 'message' => 'Anda tidak bisa memoderasi akun Anda sendiri.'], 400);
            }
            $user->update(['is_verified' => $request->is_verified]);

            AuditLogService::log(
                $admin->id,
                'VERIFY_USER',
                "Mengubah status verifikasi user ID {$user->id} ({$user->name}) menjadi: " . ($request->is_verified ? 'VERIFIED' : 'UNVERIFIED')
            );

            return response()->json([
                'success' => true,
                'message' => "User verifikasi status berhasil diupdate.",
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error("Error in AdminController@verifyUser: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    # Memblokir atau menangguhkan pengguna.
    public function toggleBan(Request $request, $id)
    {
        try {
            $admin = Auth::user();
            if (!$admin || $admin->role_id !== 1) { # Hanya Super Admin
                return response()->json(['success' => false, 'message' => 'Hanya Super Admin yang dapat membekukan akun.'], 403);
            }

            $user = User::findOrFail($id);
            if ($user->id === $admin->id) {
                return response()->json(['success' => false, 'message' => 'Anda tidak bisa membekukan akun Anda sendiri.'], 400);
            }
            if ($user->role_id === 1) {
                return response()->json(['success' => false, 'message' => 'Super Admin tidak bisa dibekukan.'], 403);
            }

            # Kita dapat memblokir mereka dengan mengubah verifikasi atau menghapus token, lalu mencatatnya di log audit
            $user->update(['is_verified' => false]);
            $user->tokens()->delete(); # Paksa logout

            AuditLogService::log($admin->id, 'BAN_USER', "Membekukan user ID {$user->id} ({$user->email})");

            return response()->json([
                'success' => true,
                'message' => "User berhasil dinonaktifkan."
            ]);
        } catch (\Exception $e) {
            Log::error("Error in AdminController@toggleBan: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    # Mengekspor laporan keuangan (CSV) untuk admin
    public function exportReport(Request $request)
    {
        try {
            $admin = Auth::user();
            if (!$admin || !in_array($admin->role_id, [1, 2])) {
                abort(403, 'Akses ditolak.');
            }

            $payments = Payment::with('payable')
                ->where('status', 'success')
                ->orderBy('paid_at', 'desc')
                ->get();

            $payments->loadMorph('payable', [
                Booking::class => ['tenant'],
                Transaction::class => ['buyer']
            ]);

            $xlsFileName = 'laporan_keuangan_' . date('Ymd_His') . '.xls';
            $headers = [
                "Content-type"        => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=$xlsFileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = ['Tanggal Pembayaran', 'Pemberi Pembayaran', 'Tipe Transaksi', 'Metode', 'Total Bayar (Rp)', 'Komisi Platform (Rp)', 'Referensi Gateway'];

            $callback = function() use($payments, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns, "\t");

                foreach ($payments as $payment) {
                    $payable = $payment->payable;
                    if (!$payable) continue;
                    
                    $payerName = '-';
                    $type = '-';
                    $commission = 0;

                    if ($payment->payable_type === Booking::class) {
                        $payerName = $payable->tenant->name ?? '-';
                        $type = 'Sewa Properti';
                        $commission = $payment->amount * 0.05; // 5% komisi
                    } elseif ($payment->payable_type === Transaction::class) {
                        $payerName = $payable->buyer->name ?? '-';
                        $type = 'Jual Beli';
                        $commission = $payment->amount * 0.01; // 1% komisi
                    }

                    fputcsv($file, [
                        $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : '-',
                        $payerName,
                        $type,
                        $payment->method,
                        $payment->amount,
                        $commission,
                        $payment->gateway_reference
                    ], "\t");
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error("Error in AdminController@exportReport: " . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor laporan keuangan.');
        }
    }

    # Menyimpan kategori baru.
    public function storeCategory(Request $request)
    {
        try {
            $admin = Auth::user();
            if (!$admin || !in_array($admin->role_id, [1, 2])) {
                return back()->with('error', 'Akses ditolak.');
            }

            $request->validate([
                'name' => 'required|string|max:100|unique:categories,name',
            ], [
                'name.unique' => 'Kategori ini sudah terdaftar.',
            ]);

            \App\Models\Category::create(['name' => $request->name]);

            AuditLogService::log($admin->id, 'CREATE_CATEGORY', "Menambahkan kategori master baru: {$request->name}");

            return back()->with('success', 'Kategori baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Error in AdminController@storeCategory: " . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    # Menghapus kategori.
    public function destroyCategory(Request $request, $id)
    {
        try {
            $admin = Auth::user();
            if (!$admin || !in_array($admin->role_id, [1, 2])) {
                return back()->with('error', 'Akses ditolak.');
            }

            $category = \App\Models\Category::findOrFail($id);

            // Cek properti terkait
            if ($category->properties()->exists()) {
                return back()->with('error', 'Tidak dapat menghapus kategori ini karena masih digunakan oleh beberapa properti.');
            }

            $categoryName = $category->name;
            $category->delete();

            AuditLogService::log($admin->id, 'DELETE_CATEGORY', "Menghapus kategori master: {$categoryName}");

            return back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error in AdminController@destroyCategory: " . $e->getMessage());
            return back()->with('error', 'Gagal menghapus kategori.');
        }
    }

    # Menyimpan wilayah/lokasi baru.
    public function storeLocation(Request $request)
    {
        try {
            $admin = Auth::user();
            if (!$admin || !in_array($admin->role_id, [1, 2])) {
                return back()->with('error', 'Akses ditolak.');
            }

            $request->validate([
                'name' => 'required|string|max:100',
                'type' => 'required|in:provinsi,kota,kecamatan',
                'parent_id' => 'nullable|exists:locations,id',
            ]);

            // Cek duplikasi nama di tipe yang sama
            $exists = \App\Models\Location::where('name', $request->name)
                ->where('type', $request->type)
                ->where('parent_id', $request->parent_id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Wilayah dengan nama dan tipe ini sudah terdaftar.');
            }

            \App\Models\Location::create([
                'name' => $request->name,
                'type' => $request->type,
                'parent_id' => $request->parent_id,
            ]);

            AuditLogService::log($admin->id, 'CREATE_LOCATION', "Menambahkan wilayah master baru: {$request->name} ({$request->type})");

            return back()->with('success', 'Wilayah baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Error in AdminController@storeLocation: " . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan wilayah.');
        }
    }

    # Menghapus wilayah/lokasi.
    public function destroyLocation(Request $request, $id)
    {
        try {
            $admin = Auth::user();
            if (!$admin || !in_array($admin->role_id, [1, 2])) {
                return back()->with('error', 'Akses ditolak.');
            }

            $location = \App\Models\Location::findOrFail($id);

            // Cek properti terkait
            if ($location->properties()->exists()) {
                return back()->with('error', 'Tidak dapat menghapus wilayah ini karena masih digunakan oleh beberapa properti.');
            }

            // Cek sub-wilayah terkait (jika kota/kecamatan lain bergantung padanya)
            if (\App\Models\Location::where('parent_id', $id)->exists()) {
                return back()->with('error', 'Tidak dapat menghapus wilayah ini karena masih memiliki wilayah bawahan (sub-wilayah).');
            }

            $locationName = $location->name;
            $location->delete();

            AuditLogService::log($admin->id, 'DELETE_LOCATION', "Menghapus wilayah master: {$locationName}");

            return back()->with('success', 'Wilayah berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error in AdminController@destroyLocation: " . $e->getMessage());
            return back()->with('error', 'Gagal menghapus wilayah.');
        }
    }
}
