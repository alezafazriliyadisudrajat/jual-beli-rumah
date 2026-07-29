<!-- 10. Tab: Laporan Keuangan & Platform Commission (Admin) -->
@if(in_array(Auth::user()->role_id, [1, 2]))
    <div x-show="tab === 'admin-reports'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Laporan Keuangan & Komisi Platform</h2>
                <p class="text-sm text-slate-500 mt-1">Pantau total pembayaran sukses dan pendapatan komisi</p>
            </div>
            <a href="{{ route('admin.reports.export') }}" class="py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-xs flex items-center gap-1.5 shadow-md shadow-indigo-600/10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Ekspor Laporan (Excel/CSV)
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="p-6 border border-slate-200/60 dark:border-zinc-800 rounded-2xl bg-slate-50 dark:bg-zinc-950/40">
                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Pembayaran Sukses</span>
                <span class="font-extrabold text-slate-900 dark:text-white text-2xl">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
            </div>
            <div class="p-6 border border-slate-200/60 dark:border-zinc-800 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/20 border-indigo-100 dark:border-indigo-900/30">
                <span class="block text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-1">Estimasi Komisi Platform</span>
                <span class="font-extrabold text-indigo-600 dark:text-indigo-400 text-2xl">Rp {{ number_format($totalCommission, 0, ',', '.') }}</span>
            </div>
            <div class="p-6 border border-slate-200/60 dark:border-zinc-800 rounded-2xl bg-slate-50 dark:bg-zinc-950/40">
                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Transaksi Sukses</span>
                <span class="font-extrabold text-slate-900 dark:text-white text-2xl">{{ $allSuccessPayments->count() }} Kali</span>
            </div>
        </div>

        <!-- Payment Logs Table -->
        <div class="overflow-x-auto pt-4">
            <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                    <tr>
                        <th class="px-4 py-3">Tgl Pembayaran</th>
                        <th class="px-4 py-3">Pengirim</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Metode</th>
                        <th class="px-4 py-3">Total Bayar</th>
                        <th class="px-4 py-3">Komisi Platform</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-850">
                    @forelse($allSuccessPayments as $payment)
                        @php
                            $payerName = '-';
                            $typeLabel = '-';
                            $comm = 0;
                            if (class_basename($payment->payable_type) === 'Booking') {
                                $payerName = $payment->payable->tenant->name ?? '-';
                                $typeLabel = 'Sewa Properti';
                                $comm = $payment->amount * 0.05;
                            } else {
                                $payerName = $payment->payable->buyer->name ?? '-';
                                $typeLabel = 'Jual Beli';
                                $comm = $payment->amount * 0.01;
                            }
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-xs">{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-xs font-bold text-slate-800 dark:text-slate-200">{{ $payerName }}</td>
                            <td class="px-4 py-3 text-xs font-semibold text-indigo-600">{{ $typeLabel }}</td>
                            <td class="px-4 py-3 text-xs uppercase">{{ $payment->method }}</td>
                            <td class="px-4 py-3 text-xs font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs font-bold text-emerald-600">Rp {{ number_format($comm, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs">
                                <a href="{{ route('payments.invoice', $payment->id) }}" target="_blank" class="p-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-all inline-flex items-center justify-center shadow" title="Unduh Kuitansi">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.793 1.996 1.817M12 6.25c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center italic text-slate-400">Belum ada riwayat pembayaran sukses.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
