<!-- 1. Tab: Profile Saya -->
<div x-show="tab === 'profile'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Profil Saya</h2>
            <p class="text-sm text-slate-500 mt-1">Informasi pribadi dan verifikasi akun Anda</p>
        </div>
        <button @click="showProfileModal = true" class="py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-md hover:shadow-lg active:scale-95 transition-all text-xs flex items-center gap-1.5 animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 21.75a.75.75 0 01-.393.213l-4.053.879a.375.375 0 01-.46-.46l.879-4.053a.75.75 0 01.213-.393L16.862 4.487zm0 0L19.5 7.125"/></svg>
            Update Profil
        </button>
    </div>

    <!-- Profile Photo & Metadata Section -->
    <div class="flex flex-col sm:flex-row items-center gap-6 p-6 border border-slate-100 dark:border-zinc-800 rounded-2xl bg-slate-50/50 dark:bg-zinc-950/20">
        <div @if(Auth::user()->avatar) @click="showZoomModal = true; zoomScale = 1.0; zoomImageUrl = '{{ Auth::user()->avatar }}'" class="cursor-pointer group relative w-24 h-24 rounded-full overflow-hidden border-2 border-indigo-500 flex-shrink-0 bg-slate-200 dark:bg-zinc-800 flex items-center justify-center" title="Klik untuk memperbesar" @else class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-indigo-500 flex-shrink-0 bg-slate-200 dark:bg-zinc-800 flex items-center justify-center" @endif>
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" alt="Foto Profil" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637zM10.5 7.5v6m3-3h-6" />
                    </svg>
                </div>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            @endif
        </div>
        <div class="text-center sm:text-left">
            <h3 class="font-bold text-lg text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ Auth::user()->email }}</p>
            <span class="inline-block px-2.5 py-0.5 mt-2 rounded-full text-[9px] font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300 uppercase tracking-wider">
                {{ Auth::user()->role->name }}
            </span>
        </div>
    </div>

    <!-- Profile Info Cards (Static Display) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-5 border border-slate-200/60 dark:border-zinc-800 rounded-2xl bg-slate-50 dark:bg-zinc-950/40">
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</span>
            <span class="font-bold text-slate-800 dark:text-slate-200 text-base">{{ Auth::user()->name }}</span>
        </div>
        <div class="p-5 border border-slate-200/60 dark:border-zinc-800 rounded-2xl bg-slate-50 dark:bg-zinc-950/40">
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nomor HP</span>
            <span class="font-bold text-slate-800 dark:text-slate-200 text-base">{{ Auth::user()->phone }}</span>
        </div>
    </div>

    <!-- Profile Verification Card -->
    <div class="p-4 rounded-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border
        {{ Auth::user()->is_verified 
            ? 'bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200/60 dark:border-emerald-900/30 text-emerald-800 dark:text-emerald-400' 
            : 'bg-amber-50 dark:bg-amber-950/20 border-amber-200/60 dark:border-amber-900/30 text-amber-800 dark:text-amber-400' }}">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-8 h-8">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000-16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
            <div>
                <h4 class="font-bold text-sm">Status Verifikasi Akun</h4>
                <p class="text-xs opacity-80 mt-0.5">
                    {{ Auth::user()->is_verified 
                        ? 'Akun Anda telah terverifikasi. Anda dapat menggunakan semua fitur.' 
                        : 'Akun Anda memerlukan verifikasi KTP sebelum Anda dapat menerbitkan properti baru.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Profile Edit Modal -->
<div x-show="showProfileModal" 
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    style="display: none;">
    
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 md:p-8 w-full max-w-lg shadow-2xl relative"
        @click.away="showProfileModal = false">
        
        <button @click="showProfileModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h3 class="heading-font font-bold text-xl text-slate-900 dark:text-white mb-2">Update Informasi Profil</h3>
        <p class="text-xs text-slate-500 mb-6">Ubah data profil Anda. Setiap pembaruan membutuhkan konfirmasi.</p>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
            @submit.prevent="confirmProfileUpdate($event)">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nomor HP</label>
                <input type="text" name="phone" value="{{ Auth::user()->phone }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Foto Profil (Avatar)</label>
                <input type="file" name="avatar" accept="image/*"
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @if(Auth::user()->avatar)
                    <div class="mt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="delete_avatar" value="1" class="rounded border-slate-300 dark:border-zinc-800 text-indigo-600 focus:ring-indigo-500 bg-slate-50 dark:bg-zinc-950">
                            <span class="text-xs text-rose-500 font-bold">Hapus Foto Profil Saat Ini</span>
                        </label>
                    </div>
                @endif
            </div>

            <!-- KTP Upload if not verified and is Agent/Owner -->
            @if(!Auth::user()->is_verified && in_array(Auth::user()->role_id, [3, 4]))
                <div class="p-4 border border-slate-200 dark:border-zinc-800 rounded-xl bg-slate-50 dark:bg-zinc-950">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Unggah Dokumen KTP</label>
                    <p class="text-[10px] text-slate-400 mb-3">Unggah foto KTP Anda dengan format JPG/PNG untuk diverifikasi oleh admin.</p>
                    <input type="file" name="ktp_document" accept="image/*" class="text-xs">
                </div>
            @endif

            <div class="flex gap-3 pt-4">
                <button type="button" @click="showProfileModal = false" class="flex-1 py-2.5 px-4 border border-slate-200 dark:border-zinc-800 text-slate-600 dark:text-slate-350 rounded-xl text-sm font-semibold hover:bg-slate-50 dark:hover:bg-zinc-800/40 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-semibold shadow-lg shadow-indigo-600/10 active:scale-95 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Avatar Zoom Modal Overlay -->
<div x-show="showZoomModal" 
    class="fixed inset-0 z-50 flex flex-col items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    style="display: none;">
    
    <!-- Close Button -->
    <button @click="showZoomModal = false; zoomScale = 1.0" class="absolute top-6 right-6 text-slate-300 hover:text-white transition-colors bg-slate-800/50 p-2.5 rounded-full hover:bg-slate-800">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    <!-- Image Viewport -->
    <div class="relative overflow-hidden flex items-center justify-center max-w-full max-h-[70vh] bg-slate-900/40 rounded-3xl p-4 border border-slate-800">
        <img :src="zoomImageUrl" alt="Zoomed Foto" 
            class="max-w-full max-h-[60vh] object-contain rounded-2xl transition-transform duration-200 ease-out shadow-2xl"
            :style="'transform: scale(' + zoomScale + ')'">
    </div>

    <!-- Zoom Controls -->
    <div class="flex items-center gap-4 mt-6 bg-slate-900/80 border border-slate-800 p-3 rounded-2xl shadow-xl">
        <button @click="zoomOut()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-lg active:scale-95 transition-all" title="Zoom Out">-</button>
        <span class="text-xs font-bold text-slate-300 w-16 text-center" x-text="Math.round(zoomScale * 100) + '%'">100%</span>
        <button @click="zoomIn()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-lg active:scale-95 transition-all" title="Zoom In">+</button>
        <div class="w-px h-6 bg-slate-800"></div>
        <button @click="zoomScale = 1.0" class="px-3.5 py-2 text-xs font-semibold rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all">Reset</button>
    </div>
</div>
