<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Property;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    # Menyimpan ulasan & rating baru untuk suatu properti.
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return back()->with('error', 'Anda harus login untuk menulis ulasan.');
            }

            $validator = Validator::make($request->all(), [
                'property_id' => 'required|exists:properties,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            # Verifikasi apakah pengguna memiliki transaksi/sewa pada properti ini (opsional)
            $property = Property::findOrFail($request->property_id);
            
            # Periksa apakah pengguna sudah memberikan ulasan
            $existing = Review::where('property_id', $property->id)->where('user_id', $user->id)->exists();
            if ($existing) {
                return back()->with('error', 'Anda sudah memberikan ulasan untuk properti ini.');
            }

            $review = Review::create([
                'property_id' => $property->id,
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            AuditLogService::log($user->id, 'SUBMIT_REVIEW', "Memberikan ulasan bintang {$request->rating} pada properti ID {$property->id}");

            return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
        } catch (\Exception $e) {
            Log::error("Error in ReviewController@store: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan ulasan.');
        }
    }
}
