<!-- 6. Tab: Persetujuan & Transaksi Sewa/Jual (Owner/Agent) -->
@if(in_array(Auth::user()->role_id, [3, 4]))
    <div x-show="tab === 'owner-deals'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Persetujuan Transaksi Masuk</h2>
            <p class="text-sm text-slate-500 mt-1">Konfirmasi minat sewa dan beli dari calon pelanggan</p>
        </div>

        <!-- Deals Section -->
        <div class="space-y-8">
            <!-- Rental Bookings Requested -->
            <div>
                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-zinc-850 pb-2 mb-4">Pengajuan Sewa Properti</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                        <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                            <tr>
                                <th class="px-4 py-3">Properti</th>
                                <th class="px-4 py-3">Penyewa</th>
                                <th class="px-4 py-3">Biaya Sewa + Deposit</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-850">
                            @forelse($ownerBookings as $bk)
                                <tr>
                                    <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">{{ $bk->property->title }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $bk->tenant->name }} ({{ $bk->tenant->phone }})</td>
                                    <td class="px-4 py-3 text-xs font-bold text-indigo-600">
                                        Rp {{ number_format($bk->total_price + $bk->deposit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-0.5 rounded font-bold uppercase
                                            {{ $bk->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                            {{ $bk->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs flex flex-wrap gap-1.5">
                                        @if($bk->status === 'menunggu')
                                            <button onclick="updateDealStatus('booking', {{ $bk->id }}, 'disetujui')" class="p-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Setujui Sewa">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </button>
                                            <button onclick="updateDealStatus('booking', {{ $bk->id }}, 'dibatalkan')" class="p-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Tolak Sewa">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                        
                                        @if(in_array($bk->status, ['disetujui', 'aktif', 'selesai']) && $bk->contract_id)
                                            <a href="{{ route('bookings.contract', $bk->id) }}" target="_blank" class="p-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Unduh Kontrak">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </a>
                                        @endif
 
                                        @php
                                            $bkPay = $bk->payments()->where('status', 'success')->first();
                                        @endphp
                                        @if($bkPay)
                                            <a href="{{ route('payments.invoice', $bkPay->id) }}" target="_blank" class="p-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Unduh Kuitansi">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 00-2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.793 1.996 1.817M12 6.25c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center italic text-slate-400">Belum ada pengajuan sewa masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Purchase Transactions Requested -->
            <div>
                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-zinc-850 pb-2 mb-4">Pengajuan Beli Properti</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                        <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                            <tr>
                                <th class="px-4 py-3">Properti</th>
                                <th class="px-4 py-3">Peminat</th>
                                <th class="px-4 py-3">Penawaran</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-850">
                            @forelse($ownerTransactions as $tx)
                                <tr>
                                    <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">{{ $tx->property->title }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $tx->buyer->name }}</td>
                                    <td class="px-4 py-3 text-xs font-bold">
                                        Rp {{ number_format($tx->agreed_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-0.5 rounded font-bold uppercase
                                            {{ $tx->status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                            {{ $tx->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs flex flex-wrap gap-1.5">
                                        @if($tx->status === 'negosiasi')
                                            <button onclick="updateDealStatus('transaction', {{ $tx->id }}, 'booking')" class="p-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Setujui & Buat PPJB">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </button>
                                            <button onclick="updateDealStatus('transaction', {{ $tx->id }}, 'batal')" class="p-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Tolak">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @elseif($tx->status === 'booking')
                                            <span class="text-indigo-650 text-xs">Menunggu Pembayaran</span>
                                        @endif
                                        
                                        @if(in_array($tx->status, ['booking', 'menunggu_pelunasan', 'lunas']) && $tx->contract_id)
                                            <a href="{{ route('contracts.download', $tx->id) }}" target="_blank" class="p-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Unduh PPJB">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </a>
                                        @endif

                                        @php
                                            $txPay = $tx->payments()->where('status', 'success')->get();
                                        @endphp
                                        @foreach($txPay as $pay)
                                            <a href="{{ route('payments.invoice', $pay->id) }}" target="_blank" class="p-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Unduh Kuitansi">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 00-2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.793 1.996 1.817M12 6.25c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
                                                </svg>
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center italic text-slate-400">Belum ada pengajuan beli masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
