<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    # Memicu transaksi simulasi tagihan pembayaran.
    public function charge(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payable_type' => 'required|in:booking,transaction',
                'payable_id' => 'required|integer',
                'amount' => 'required|numeric|min:0',
                'method' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $payment = $this->paymentService->charge(
                $request->payable_type,
                $request->payable_id,
                $request->amount,
                $request->method
            );

            # Hasilkan tanda tangan mock untuk pengujian lokal yang aman
            $signature = hash('sha256', $payment->gateway_reference . config('app.key'));

            return response()->json([
                'success' => true,
                'message' => 'Tagihan berhasil dibuat. Silakan selesaikan pembayaran.',
                'data' => array_merge($payment->toArray(), ['signature' => $signature])
            ]);
        } catch (\Exception $e) {
            Log::error("Error in PaymentController@charge: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    # Webhook callback dari payment gateway (simulasi webhook Midtrans / Xendit).
    public function webhook(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'gateway_reference' => 'required|string',
                'status' => 'required|in:success,failed,expired',
                'signature_key' => 'required|string', # Mekanisme verifikasi
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            # Verifikasi tanda tangan (Logika simulasi: cocokkan hash dari referensi dan secret key aplikasi)
            $expectedSignature = hash('sha256', $request->gateway_reference . config('app.key'));
            if ($request->signature_key !== $expectedSignature) {
                return response()->json(['success' => false, 'message' => 'Signature key tidak valid.'], 403);
            }

            $payment = $this->paymentService->processWebhook(
                $request->gateway_reference,
                $request->status
            );

            return response()->json([
                'success' => true,
                'message' => 'Callback webhook berhasil diproses.',
                'data' => $payment
            ]);
        } catch (\Exception $e) {
            Log::error("Error in PaymentController@webhook: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    # Mengunduh dokumen kuitansi / invoice pembayaran digital
    public function downloadInvoice($id)
    {
        try {
            $payment = Payment::with(['payable.property.user'])->findOrFail($id);
            $user = Auth::user();
            
            $isPayer = false;
            $isPayee = false;
            $isAdmin = in_array($user->role_id, [1, 2]);
            
            $payable = $payment->payable;
            if ($payment->payable_type === Booking::class) {
                $isPayer = $payable->tenant_id === $user->id;
                $isPayee = $payable->property->user_id === $user->id;
            } elseif ($payment->payable_type === Transaction::class) {
                $isPayer = $payable->buyer_id === $user->id;
                $isPayee = $payable->property->user_id === $user->id;
            }
            
            if (!$isPayer && !$isPayee && !$isAdmin) {
                abort(403, 'Anda tidak memiliki akses untuk dokumen ini.');
            }
            
            return view('pdf.invoice', compact('payment'));
        } catch (\Exception $e) {
            Log::error("Error in PaymentController@downloadInvoice: " . $e->getMessage());
            abort(404, 'Dokumen tidak ditemukan.');
        }
    }
}
