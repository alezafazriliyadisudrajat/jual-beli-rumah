<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard utama dengan data yang sudah di-eager load sesuai hak akses.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $allUsers = collect();
        $auditLogs = collect();
        $ownerBookings = collect();
        $ownerTransactions = collect();
        $allSuccessPayments = collect();
        $totalIncome = 0;
        $totalCommission = 0;

        if (in_array($user->role_id, [1, 2])) {
            // Data untuk Admin & Super Admin
            $allUsers = User::with('role')->get();
            $auditLogs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(10);
            
            // Laporan Keuangan
            $allSuccessPayments = Payment::with('payable')
                ->where('status', 'success')
                ->orderBy('paid_at', 'desc')
                ->get();
                
            // Eager load polymorphic relation secara aman
            $allSuccessPayments->loadMorph('payable', [
                Booking::class => ['tenant'],
                Transaction::class => ['buyer']
            ]);

            $totalIncome = $allSuccessPayments->sum('amount');
            foreach ($allSuccessPayments as $payment) {
                if ($payment->payable_type === Booking::class) {
                    $totalCommission += $payment->amount * 0.05;
                } else {
                    $totalCommission += $payment->amount * 0.01;
                }
            }
        }

        if (in_array($user->role_id, [3, 4])) {
            // Data transaksi masuk untuk Penjual / Agen
            $ownerPropertyIds = $user->properties()->pluck('id');
            $ownerBookings = Booking::whereIn('property_id', $ownerPropertyIds)->with(['property', 'tenant'])->get();
            $ownerTransactions = Transaction::whereIn('property_id', $ownerPropertyIds)->with(['property', 'buyer'])->get();
        }

        $buyerBookings = collect();
        $buyerTransactions = collect();
        if ($user->role_id === 5) {
            // Data transaksi untuk Pembeli / Penyewa
            $buyerBookings = Booking::where('tenant_id', $user->id)->with('property')->get();
            $buyerTransactions = Transaction::where('buyer_id', $user->id)->with(['property', 'contract'])->get();
        }

        $myProperties = collect();
        if (in_array($user->role_id, [3, 4])) {
            $myProperties = $user->properties()->with('category')->get();
        }

        $categories = Category::all();
        $locations = Location::all();

        $pendingProperties = collect();
        if (in_array($user->role_id, [1, 2])) {
            $pendingProperties = Property::where('status', 'pending')->with('user')->get();
        }

        // Hitung statistik untuk Dashboard Overview
        $stats = [];
        $chartLabels = [];
        $chartData = [];

        if (in_array($user->role_id, [1, 2])) {
            // Admin stats
            $stats = [
                'users_count' => User::count(),
                'properties_count' => Property::count(),
                'payments_count' => Payment::where('status', 'success')->count(),
                'total_income' => $totalIncome,
                'total_commission' => $totalCommission
            ];
        } elseif (in_array($user->role_id, [3, 4])) {
            // Owner stats
            $ownerPropertyIds = $user->properties()->pluck('id');
            $activeRentals = Booking::whereIn('property_id', $ownerPropertyIds)->where('status', 'aktif')->count();
            $completedSales = Transaction::whereIn('property_id', $ownerPropertyIds)->where('status', 'lunas')->count();
            $totalRev = Transaction::whereIn('property_id', $ownerPropertyIds)->where('status', 'lunas')->sum('agreed_price') 
                + Booking::whereIn('property_id', $ownerPropertyIds)->where('status', 'aktif')->sum('total_price');

            $stats = [
                'properties_count' => Property::where('user_id', $user->id)->count(),
                'active_rentals' => $activeRentals,
                'completed_sales' => $completedSales,
                'total_revenue' => $totalRev
            ];
        } else {
            // Buyer/Tenant stats
            $stats = [
                'active_rentals' => Booking::where('tenant_id', $user->id)->where('status', 'aktif')->count(),
                'completed_purchases' => Transaction::where('buyer_id', $user->id)->where('status', 'lunas')->count(),
                'favorites_count' => $user->favorites()->count(),
                'total_spent' => Payment::whereHasMorph('payable', [Booking::class, Transaction::class], function($query, $type) use($user) {
                    if ($type === Booking::class) {
                        $query->where('tenant_id', $user->id);
                    } else {
                        $query->where('buyer_id', $user->id);
                    }
                })->where('status', 'success')->sum('amount')
            ];
        }

        // Generate chart data for the last 6 months cleanly in a synchronized loop
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $chartLabels[] = $date->translatedFormat('M Y');

            if (in_array($user->role_id, [1, 2])) {
                $chartData[] = (float) Payment::where('status', 'success')
                    ->whereYear('paid_at', $date->year)
                    ->whereMonth('paid_at', $date->month)
                    ->sum('amount');
            } elseif (in_array($user->role_id, [3, 4])) {
                $chartData[] = (float) Payment::where('status', 'success')
                    ->whereHas('payable', function($q) use($ownerPropertyIds) {
                        $q->whereIn('property_id', $ownerPropertyIds);
                    })
                    ->whereYear('paid_at', $date->year)
                    ->whereMonth('paid_at', $date->month)
                    ->sum('amount');
            } else {
                $chartData[] = (float) Payment::where('status', 'success')
                    ->whereHasMorph('payable', [Booking::class, Transaction::class], function($query, $type) use($user) {
                        if ($type === Booking::class) {
                            $query->where('tenant_id', $user->id);
                        } else {
                            $query->where('buyer_id', $user->id);
                        }
                    })
                    ->whereYear('paid_at', $date->year)
                    ->whereMonth('paid_at', $date->month)
                    ->sum('amount');
            }
        }

        $activeChatId = null;
        if ($request->filled('active_chat')) {
            $chat = \App\Models\ChatConversation::find($request->active_chat);
            if ($chat && ($chat->participant_one === $user->id || $chat->participant_two === $user->id)) {
                $activeChatId = $chat->id;
            }
        }

        return view('dashboard', compact(
            'allUsers',
            'auditLogs',
            'ownerBookings',
            'ownerTransactions',
            'allSuccessPayments',
            'totalIncome',
            'totalCommission',
            'buyerBookings',
            'buyerTransactions',
            'myProperties',
            'categories',
            'locations',
            'pendingProperties',
            'stats',
            'chartLabels',
            'chartData',
            'activeChatId'
        ));
    }
}
