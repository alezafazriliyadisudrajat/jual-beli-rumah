<!-- 3. Tab: Sewa Saya (Pembeli/Penyewa) -->
@if(Auth::user()->role_id === 5)
    <div x-show="tab === 'my-rentals'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Riwayat Sewa Saya</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar properti yang Anda sewa</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                    <tr>
                        <th class="px-4 py-3">Properti</th>
                        <th class="px-4 py-3">Periode</th>
                        <th class="px-4 py-3">Biaya</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-850">
                    @forelse($buyerBookings as $book)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('properties.show', $book->property_id) }}" class="font-bold text-slate-800 dark:text-slate-250 hover:underline">
                                    {{ $book->property->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $book->start_date->format('d M Y') }} s/d {{ $book->end_date->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <div class="font-bold text-indigo-650 dark:text-indigo-400">
                                    Rp {{ number_format($book->property->price, 0, ',', '.') }} / bulan
                                </div>
                                <div class="text-[10px] text-slate-500 mt-0.5">
                                    Total Durasi ({{ $book->duration_type === 'bulanan' ? ($book->total_price / $book->property->price) . ' bln' : ($book->total_price / ($book->property->price * 12)) . ' thn' }}): Rp {{ number_format($book->total_price, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    Deposit Jaminan: Rp {{ number_format($book->deposit, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded font-bold uppercase
                                    {{ $book->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                    {{ $book->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs flex flex-wrap gap-1.5">
                                @if($book->status === 'menunggu')
                                    @php
                                        $initialPaymentAmount = $book->duration_type === 'bulanan' 
                                            ? ($book->property->price + $book->deposit) 
                                            : ($book->property->price * 12 + $book->deposit);
                                    @endphp
                                    <button onclick="triggerMockPayment('booking', {{ $book->id }}, {{ $initialPaymentAmount }}, 'Virtual Account')" class="py-1.5 px-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded font-bold shadow active:scale-95 transition-all">
                                        Bayar Bulan Pertama + Deposit (Mock)
                                    </button>
                                @endif
                                
                                @if(in_array($book->status, ['disetujui', 'aktif', 'selesai']) && $book->contract_id)
                                    <a href="{{ route('bookings.contract', $book->id) }}" target="_blank" class="p-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition-all inline-flex items-center justify-center shadow" title="Unduh Perjanjian Sewa">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </a>
                                @endif

                                @php
                                    $bookingPayment = $book->payments()->where('status', 'success')->first();
                                @endphp
                                @if($bookingPayment)
                                    <a href="{{ route('payments.invoice', $bookingPayment->id) }}" target="_blank" class="p-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-all inline-flex items-center justify-center shadow" title="Unduh Kuitansi">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.793 1.996 1.817M12 6.25c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
                                        </svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center italic text-slate-400">Belum ada riwayat booking sewa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
