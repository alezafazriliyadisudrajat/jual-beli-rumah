<!-- 4. Tab: Transaksi Saya (Pembeli/Penyewa) -->
@if(Auth::user()->role_id === 5)
    <div x-show="tab === 'my-purchases'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Transaksi Jual Beli Saya</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar minat beli properti Anda</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                    <tr>
                        <th class="px-4 py-3">Properti</th>
                        <th class="px-4 py-3">Harga Penawaran</th>
                        <th class="px-4 py-3">Terbayar / Sisa</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($buyerTransactions as $tx)
                        @php
                            $totalPaid = $tx->payments()->where('status', 'success')->sum('amount');
                            $remaining = $tx->agreed_price - $totalPaid;
                            
                            // Ambil nilai cicilan yang disepakati dari database
                            $monthlyInstallment = $tx->monthly_installment;
                            
                            // Sisa pembayaran yang bisa dicicil
                            $installmentPayment = $monthlyInstallment ? min($remaining, $monthlyInstallment) : null;
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('properties.show', $tx->property_id) }}" class="font-bold text-slate-800 dark:text-slate-250 hover:underline">
                                    {{ $tx->property->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-xs font-bold">
                                Rp {{ number_format($tx->agreed_price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="font-semibold text-emerald-600">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                                <div class="text-[10px] text-slate-400 dark:text-slate-450 mt-0.5">Sisa: Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded font-bold uppercase
                                    {{ $tx->status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : ($tx->status === 'batal' ? 'bg-rose-100 text-rose-800' : ($tx->status === 'booking' ? 'bg-blue-100 text-blue-800' : ($tx->status === 'menunggu_pelunasan' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-800'))) }}">
                                    {{ str_replace('_', ' ', $tx->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs flex items-center gap-3">
                                <!-- Main Actions -->
                                <div class="flex flex-col gap-1.5">
                                    @if($tx->status === 'negosiasi')
                                        <button onclick="triggerMockPayment('transaction', {{ $tx->id }}, {{ $tx->booking_fee }}, 'Virtual Account')" class="py-1.5 px-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold transition-all active:scale-95 shadow text-[10px]">
                                            Bayar Booking Fee (Mock)
                                        </button>
                                    @elseif($tx->status === 'booking')
                                        <span class="py-1.5 px-3 bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-slate-400 rounded-lg font-bold text-[10px]">Menunggu Tagihan Pelunasan</span>
                                    @elseif($tx->status === 'menunggu_pelunasan')
                                        @if($installmentPayment && $installmentPayment > 0)
                                            <button onclick="triggerMockPayment('transaction', {{ $tx->id }}, {{ $installmentPayment }}, 'Virtual Account')" class="py-1.5 px-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold transition-all active:scale-95 shadow text-[10px] text-center" title="Bayar Cicilan Bulanan">
                                                Cicil Bulanan (Rp {{ number_format($installmentPayment, 0, ',', '.') }})
                                            </button>
                                            <button onclick="triggerMockPayment('transaction', {{ $tx->id }}, {{ $remaining }}, 'Virtual Account')" class="py-1.5 px-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold transition-all active:scale-95 shadow text-[10px] text-center" title="Lunasi Sisa Pembayaran">
                                                Lunasi Sisa (Rp {{ number_format($remaining, 0, ',', '.') }})
                                            </button>
                                        @else
                                            <button onclick="triggerMockPayment('transaction', {{ $tx->id }}, {{ $remaining }}, 'Virtual Account')" class="py-1.5 px-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold transition-all active:scale-95 shadow text-[10px] text-center" title="Lunasi Pembayaran">
                                                Lunasi Pembayaran (Rp {{ number_format($remaining, 0, ',', '.') }})
                                            </button>
                                        @endif
                                    @endif
                                </div>

                                <!-- Separator line if there are documents -->
                                @if((in_array($tx->status, ['booking', 'menunggu_pelunasan', 'lunas']) && $tx->contract_id) || $tx->payments()->where('status', 'success')->exists())
                                    <div class="h-8 w-px bg-slate-200 dark:bg-zinc-850"></div>
                                @endif

                                <!-- Document & Receipts -->
                                <div class="flex items-center gap-1.5">
                                    @if(in_array($tx->status, ['booking', 'menunggu_pelunasan', 'lunas']) && $tx->contract_id)
                                        <a href="{{ route('contracts.download', $tx->id) }}" target="_blank" class="w-8 h-8 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 dark:text-indigo-400 rounded-lg transition-all flex items-center justify-center shadow-sm border border-indigo-100 dark:border-indigo-900/30" title="Unduh PPJB">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                        </a>
                                    @endif

                                    @php
                                        $txPayments = $tx->payments()->where('status', 'success')->get();
                                    @endphp
                                    @foreach($txPayments as $pay)
                                        <a href="{{ route('payments.invoice', $pay->id) }}" target="_blank" class="px-2.5 h-8 bg-slate-50 hover:bg-slate-100 text-slate-700 dark:bg-zinc-800/40 dark:hover:bg-zinc-800/80 dark:text-zinc-300 border border-slate-200/60 dark:border-zinc-750 rounded-lg transition-all flex items-center justify-center gap-1 shadow-sm text-[10px]" title="Unduh Kuitansi (Rp {{ number_format($pay->amount, 0, ',', '.') }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 00-2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.793 1.996 1.817M12 6.25c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
                                            </svg>
                                            <span class="text-[9px] font-bold">
                                                @if($pay->amount == $tx->booking_fee)
                                                    BF
                                                @elseif($tx->status === 'lunas' && $loop->last)
                                                    Lunas
                                                @else
                                                    Ccl
                                                @endif
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center italic text-slate-400">Belum ada riwayat transaksi jual beli.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
