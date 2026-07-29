<!-- 7. Tab: Admin Moderation (Admin) -->
@if(in_array(Auth::user()->role_id, [1, 2]))
    <div x-show="tab === 'admin-moderation'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Moderasi Properti Tertunda</h2>
            <p class="text-sm text-slate-500 mt-1">Review listing properti baru sebelum ditampilkan ke publik</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                    <tr>
                        <th class="px-4 py-3">Properti</th>
                        <th class="px-4 py-3">Pemilik</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-850">
                    @forelse($pendingProperties as $pp)
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">
                                <a href="{{ route('properties.show', $pp->id) }}" class="hover:underline" target="_blank">{{ $pp->title }}</a>
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $pp->user->name }}</td>
                            <td class="px-4 py-3 text-xs font-bold">Rp {{ number_format($pp->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs uppercase font-semibold text-amber-500">{{ $pp->status }}</td>
                            <td class="px-4 py-3 text-xs flex gap-1.5">
                                <button onclick="adminModerateProperty({{ $pp->id }}, 'active')" class="p-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Setujui Properti">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </button>
                                <button onclick="adminModerateProperty({{ $pp->id }}, 'rejected')" class="p-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Tolak Properti">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center italic text-slate-400">Tidak ada properti menunggu moderasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
