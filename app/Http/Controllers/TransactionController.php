<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    # Mengajukan permintaan minat beli properti (memulai tahap negosiasi).
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengajukan minat beli.');
            }

            $validator = Validator::make($request->all(), [
                'property_id' => 'required|exists:properties,id',
                'agreed_price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $transaction = $this->transactionService->proposeTransaction($request->all(), $user->id);

            return redirect()->route('dashboard', ['tab' => 'my-purchases'])->with('success', 'Minat beli berhasil diajukan! Anda dapat memulai negosiasi harga di chat dengan pemilik.');
        } catch (\Exception $e) {
            Log::error("Error in TransactionController@store: " . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    # Memperbarui status transaksi (pemilik menyetujui negosiasi atau mengonfirmasi pembayaran).
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $transaction = Transaction::findOrFail($id);

            # Otorisasi: pembeli dapat membatalkan, pemilik/agen dapat menyetujui penawaran, admin dapat mengubah
            $isOwner = $transaction->property->user_id === $user->id;
            $isBuyer = $transaction->buyer_id === $user->id;
            $isAdmin = in_array($user->role_id, [1, 2]);

            if (!$isOwner && !$isBuyer && !$isAdmin) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak akses.'], 403);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:negosiasi,booking,menunggu_pelunasan,lunas,batal',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $updatedTransaction = $this->transactionService->updateStatus($transaction, $request->status, $user->id);

            return response()->json([
                'success' => true,
                'message' => "Status transaksi berhasil diubah menjadi: {$request->status}.",
                'data' => $updatedTransaction
            ]);
        } catch (\Exception $e) {
            Log::error("Error in TransactionController@updateStatus: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    # Mengunduh dokumen kontrak jual beli (PPJB)
    public function downloadContract($id)
    {
        try {
            $transaction = Transaction::with(['property.user', 'buyer', 'contract'])->findOrFail($id);
            
            $user = Auth::user();
            $isOwner = $transaction->property->user_id === $user->id;
            $isBuyer = $transaction->buyer_id === $user->id;
            $isAdmin = in_array($user->role_id, [1, 2]);
            
            if (!$isOwner && !$isBuyer && !$isAdmin) {
                abort(403, 'Anda tidak memiliki akses untuk dokumen ini.');
            }
            
            if (!$transaction->contract_id) {
                return back()->with('error', 'Kontrak belum diterbitkan.');
            }
            
            return view('pdf.contract_sale', compact('transaction'));
        } catch (\Exception $e) {
            Log::error("Error in TransactionController@downloadContract: " . $e->getMessage());
            abort(404, 'Dokumen tidak ditemukan.');
        }
    }
}
