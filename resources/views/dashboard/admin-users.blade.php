<!-- 8. Tab: Admin Users (Admin) -->
@if(in_array(Auth::user()->role_id, [1, 2]))
    <div x-show="tab === 'admin-users'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Manajemen Pengguna</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola hak akses, verifikasi KTP, dan blokir akun</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Peran</th>
                        <th class="px-4 py-3">KTP Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @foreach($allUsers as $usr)
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">{{ $usr->name }}</td>
                            <td class="px-4 py-3 text-xs">{{ $usr->email }}</td>
                            <td class="px-4 py-3 text-xs font-semibold">{{ $usr->role->name ?? 'User' }}</td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded font-bold uppercase
                                    {{ $usr->is_verified ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $usr->is_verified ? 'Verified' : 'Unverified' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs flex gap-1.5">
                                @if($usr->id === Auth::id())
                                    <span class="text-slate-400 italic">Sesi Aktif</span>
                                @else
                                    @if(!$usr->is_verified)
                                        <button onclick="adminVerifyUser({{ $usr->id }}, true)" class="p-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Verifikasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </button>
                                    @else
                                        <button onclick="adminVerifyUser({{ $usr->id }}, false)" class="p-1.5 bg-slate-500 hover:bg-slate-400 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Batal Verifikasi">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                    @if($usr->role_id !== 1 && $usr->is_verified)
                                        <button onclick="adminBanUser({{ $usr->id }})" class="p-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Blokir">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
