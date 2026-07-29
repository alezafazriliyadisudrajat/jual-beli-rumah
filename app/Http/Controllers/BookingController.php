<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    # Mengajukan permintaan booking sewa properti.
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $request->wantsJson()
                    ? response()->json(['success' => false, 'message' => 'Anda harus login terlebih dahulu.'], 401)
                    : redirect()->route('login')->with('error', 'Silakan login untuk menyewa properti ini.');
            }

            $validator = Validator::make($request->all(), [
                'property_id' => 'required|exists:properties,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'duration_type' => 'required|in:bulanan,tahunan',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            $booking = $this->bookingService->createBooking($request->all(), $user->id);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan sewa berhasil dibuat, menunggu persetujuan pemilik.',
                    'data' => $booking
                ], 201);
            }

            return redirect()->route('dashboard', ['tab' => 'my-rentals'])->with('success', 'Pengajuan sewa berhasil diajukan! Menunggu persetujuan dari pemilik properti.');
        } catch (\Exception $e) {
            Log::error("Error in BookingController@store: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    # Memperbarui status sewa (pemilik menyetujui/menolak permintaan booking).
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $booking = Booking::findOrFail($id);

            # Verifikasi pengguna yang sah (pemilik properti, agen, atau admin)
            if ($booking->property->user_id !== $user->id && !in_array($user->role_id, [1, 2])) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak akses.'], 403);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:disetujui,dibatalkan,aktif,selesai',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $updatedBooking = $this->bookingService->updateStatus($booking, $request->status, $user->id);

            return response()->json([
                'success' => true,
                'message' => "Sewa berhasil diperbarui menjadi status: {$request->status}.",
                'data' => $updatedBooking
            ]);
        } catch (\Exception $e) {
            Log::error("Error in BookingController@updateStatus: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    # Mengunduh dokumen kontrak sewa digital
    public function downloadContract($id)
    {
        try {
            $booking = Booking::with(['property.user', 'tenant', 'contract'])->findOrFail($id);
            
            $user = Auth::user();
            $isOwner = $booking->property->user_id === $user->id;
            $isTenant = $booking->tenant_id === $user->id;
            $isAdmin = in_array($user->role_id, [1, 2]);
            
            if (!$isOwner && !$isTenant && !$isAdmin) {
                abort(403, 'Anda tidak memiliki akses untuk dokumen ini.');
            }
            
            if (!$booking->contract_id) {
                return back()->with('error', 'Kontrak belum diterbitkan.');
            }
            
            return view('pdf.contract_rent', compact('booking'));
        } catch (\Exception $e) {
            Log::error("Error in BookingController@downloadContract: " . $e->getMessage());
            abort(404, 'Dokumen tidak ditemukan.');
        }
    }
}
