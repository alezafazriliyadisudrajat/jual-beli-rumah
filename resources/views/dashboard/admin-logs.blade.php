<!-- 9. Tab: Admin Audit Logs (Admin) -->
@if(in_array(Auth::user()->role_id, [1, 2]))
    <div x-show="tab === 'admin-logs'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Log Aktivitas Sistem (Audit Trail)</h2>
            <p class="text-sm text-slate-500 mt-1">Catatan audit log keamanan aplikasi</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                    <tr>
                        <th class="px-4 py-3">Tgl / Waktu</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">IP / Device</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($auditLogs as $lg)
                        <tr>
                            <td class="px-4 py-3 text-xs">{{ $lg->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-xs font-bold">{{ $lg->user->name ?? 'System' }}</td>
                            <td class="px-4 py-3 text-xs uppercase font-semibold text-indigo-600">{{ $lg->action }}</td>
                            <td class="px-4 py-3 text-xs text-slate-700 dark:text-slate-350">{{ $lg->description }}</td>
                            <td class="px-4 py-3 text-[10px] text-slate-400">{{ $lg->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center italic text-slate-400">Tidak ada log aktivitas tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($auditLogs instanceof \Illuminate\Pagination\LengthAwarePaginator && $auditLogs->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800">
                {{ $auditLogs->appends(['tab' => 'admin-logs'])->links() }}
            </div>
        @endif
    </div>
@endif
