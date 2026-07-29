<!-- 0. Tab: Overview Ringkasan (Default) -->
<div x-show="tab === 'overview'" class="space-y-6">
    <!-- Page Title -->
    <div>
        <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Ringkasan Aktivitas</h2>
        <p class="text-sm text-slate-500 mt-1">Status dan grafik data kinerja akun Anda saat ini</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @if(in_array(Auth::user()->role_id, [1, 2]))
            <!-- Admin Cards -->
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Pengguna</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['users_count'] }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Properti</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['properties_count'] }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Transaksi Berhasil</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['payments_count'] }} Kali</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-1">Estimasi Komisi Platform</span>
                    <span class="font-extrabold text-indigo-650 dark:text-indigo-400 text-2xl">Rp {{ number_format($stats['total_commission'], 0, ',', '.') }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-700 dark:text-indigo-300 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        @elseif(in_array(Auth::user()->role_id, [3, 4]))
            <!-- Owner Cards -->
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Properti Saya</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['properties_count'] }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21h8.25M17.25 3.75h-10.5a2.25 2.25 0 00-2.25 2.25v15h15v-15a2.25 2.25 0 00-2.25-2.25z" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Sewa Aktif</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['active_rentals'] }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Properti Terjual</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['completed_sales'] }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-1">Total Pendapatan</span>
                    <span class="font-extrabold text-indigo-650 dark:text-indigo-400 text-2xl">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-700 dark:text-indigo-300 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        @else
            <!-- Buyer Cards -->
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Sewa Aktif Saya</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['active_rentals'] }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pembelian Selesai</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['completed_purchases'] }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Favorit Saya</span>
                    <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $stats['favorites_count'] }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
            </div>
            <div class="p-6 bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="block text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-1">Total Pengeluaran</span>
                    <span class="font-extrabold text-indigo-650 dark:text-indigo-400 text-2xl">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-700 dark:text-indigo-305 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        @endif
    </div>

    <!-- Chart Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
        <h3 class="heading-font font-bold text-base text-slate-800 dark:text-slate-200 mb-4">
            @if(in_array(Auth::user()->role_id, [1, 2]))
                Tren Keuangan Platform (6 Bulan Terakhir)
            @elseif(in_array(Auth::user()->role_id, [3, 4]))
                Tren Pendapatan Deal Saya (6 Bulan Terakhir)
            @else
                Tren Pengeluaran Transaksi Saya (6 Bulan Terakhir)
            @endif
        </h3>
        <div class="relative w-full h-80" style="min-height: 320px;">
            <!-- Loading Spinner/Skeleton Overlay -->
            <div x-show="chartLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 dark:bg-zinc-900/80 z-10 rounded-xl transition-opacity duration-350">
                <div class="w-10 h-10 border-4 border-slate-200 border-t-indigo-650 rounded-full animate-spin"></div>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-3 animate-pulse">Menghitung dan memuat data grafik...</span>
            </div>

            <canvas id="revenueChart" style="display: block; width: 100%; height: 320px;"></canvas>
        </div>
    </div>
</div>
