@extends('layouts.app')

@section('title', $property->title . ' - Detail Properti')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumbs & Back Button -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <nav class="flex text-sm text-slate-500 dark:text-slate-400 font-medium">
            <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">Properti</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800 dark:text-slate-200 truncate max-w-[200px]">{{ $property->title }}</span>
        </nav>
        
        <a href="{{ url()->previous() == url()->current() ? url('/') : url()->previous() }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Property Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Basic Details Header -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="px-3 py-1 rounded-lg text-xs font-extrabold uppercase tracking-wide
                        {{ $property->listing_type === 'jual' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/40 dark:text-indigo-400' }}">
                        {{ $property->listing_type === 'jual' ? 'Dijual' : ($property->listing_type === 'sewa_bulanan' ? 'Sewa Bulanan' : 'Sewa Tahunan') }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300">
                        {{ $property->category->name }}
                    </span>
                    @if($property->is_promoted)
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-amber-500 text-white uppercase tracking-wider flex items-center gap-1">
                            Highlight
                        </span>
                    @endif
                </div>

                <h1 class="heading-font font-bold text-2xl md:text-3xl text-slate-900 dark:text-white leading-tight">
                    {{ $property->title }}
                </h1>
                
                <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-slate-500 dark:text-slate-400 font-medium border-t border-slate-100 dark:border-zinc-800 pt-3">
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        {{ $property->location->name }}
                    </span>
                    <span class="flex items-center gap-1 text-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.6 3.102-1.196 4.622c-.22.85.704 1.522 1.4 1.077L10 15.547l4.183 2.573c.695.427 1.62-.24 1.4-1.077l-1.197-4.623 3.6-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.83-4.401z" clip-rule="evenodd" />
                        </svg>
                        {{ $property->average_rating }} ({{ $property->reviews->count() }} Ulasan)
                    </span>
                </div>
            </div>

            <!-- Image Gallery -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <div class="relative overflow-hidden rounded-xl aspect-[16/9] bg-slate-100 dark:bg-zinc-800">
                    <img id="main-image" src="{{ $property->primary_image }}" alt="{{ $property->title }}" class="object-cover w-full h-full transition-all duration-300">
                </div>
                
                @if($property->images->count() > 1)
                    <div class="flex gap-3 mt-4 overflow-x-auto pb-2 snap-x">
                        @foreach($property->images as $img)
                            <button onclick="document.getElementById('main-image').src = '{{ asset($img->image_path) }}'" 
                                    class="w-24 h-16 rounded-lg overflow-hidden flex-shrink-0 border-2 border-transparent hover:border-indigo-500 focus:border-indigo-600 transition-all snap-start">
                                <img src="{{ asset($img->image_path) }}" class="object-cover w-full h-full">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Key Specifications Grid -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <h3 class="heading-font font-bold text-lg text-slate-900 dark:text-white mb-6">Spesifikasi Properti</h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-lg bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h16.5v16.5H3.75V3.75z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Luas Bangunan</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ (int)$property->building_area }} m²</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-lg bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m11.142 0L21.75 12l-4.179-2.25M12 5.75L7.821 8 12 10.25 16.179 8 12 5.75z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Luas Tanah</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ (int)$property->land_area }} m²</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-lg bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Kamar Tidur</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $property->bedrooms ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-lg bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Kamar Mandi</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $property->bathrooms ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-lg bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Sertifikat</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $property->certificate_type ?? 'SHM' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-lg bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Daya Listrik</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $property->electricity ? $property->electricity . ' VA' : '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Secondary specs list -->
                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-zinc-800 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-zinc-800">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider">Kondisi Bangunan</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">{{ $property->condition ?? 'baru' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-zinc-800">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider">Arah Hadap</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">{{ $property->facing ?? 'timur' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-zinc-800">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider">Interior</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">{{ $property->interior_type ?? 'tak berperabot' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-zinc-800">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider">Jumlah Lantai</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $property->floors_count ?? '1' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-zinc-800">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider">Garasi / Carport</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $property->garages_count ?? 0 }} / {{ $property->carports_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-zinc-800">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider">Akses Jalan</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">{{ $property->road_access ?? 'Dua Mobil' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-zinc-800">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider">Pasokan Air PAM</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $property->has_pam_water ? 'Tersedia' : 'Tidak Ada' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50 dark:border-zinc-800">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider">Pasokan Air Tanah</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $property->has_ground_water ? 'Tersedia' : 'Tidak Ada' }}</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <h3 class="heading-font font-bold text-lg text-slate-900 dark:text-white mb-4">Deskripsi Lengkap</h3>
                <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-line">
                    {{ $property->description }}
                </div>
            </div>

            <!-- Extra Features / Facilities -->
            @if($property->features->isNotEmpty())
                <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                    <h3 class="heading-font font-bold text-lg text-slate-900 dark:text-white mb-4">Fasilitas Lainnya</h3>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($property->features as $feature)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-indigo-50 dark:bg-zinc-800 text-indigo-700 dark:text-indigo-300">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                {{ $feature->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- KPR Simulation Calculator (Only for Jual / For-Sale) -->
            @if($property->listing_type === 'jual')
                <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                    <h3 class="heading-font font-bold text-lg text-slate-900 dark:text-white mb-2">Simulasi KPR (Kredit Pemilikan Rumah)</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Hitung perkiraan angsuran bulanan Anda untuk mempermudah perencanaan pembelian.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Input Parameters -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">
                                    Harga Properti (Rp) <span class="text-[10px] text-slate-400 lowercase italic font-normal">(hanya untuk simulasi hitungan)</span>
                                </label>
                                <input type="hidden" id="kpr_price" value="{{ (int)$property->price }}">
                                <input type="text" id="formatted_kpr_price" 
                                       value="{{ number_format($property->price, 0, ',', '.') }}" 
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-sm font-semibold focus:ring-2 focus:ring-indigo-500/20 outline-none"
                                       oninput="formatRupiah(this, 'kpr_price'); calculateKPR();">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Uang Muka / DP (%)</label>
                                    <input type="number" id="kpr_dp_percent" value="20" min="0" max="100"
                                           class="w-full px-3 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-sm font-semibold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Suku Bunga (% / Tahun)</label>
                                    <input type="number" id="kpr_interest" value="6.5" step="0.1" min="0"
                                           class="w-full px-3 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-sm font-semibold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Jangka Waktu (Tenor)</label>
                                <select id="kpr_tenor" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-sm font-semibold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                                    <option value="5">5 Tahun</option>
                                    <option value="10">10 Tahun</option>
                                    <option value="15" selected>15 Tahun</option>
                                    <option value="20">20 Tahun</option>
                                    <option value="25">25 Tahun</option>
                                    <option value="30">30 Tahun</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Output Calculations -->
                        <div class="bg-indigo-50/40 dark:bg-zinc-800/40 border border-indigo-100/50 dark:border-zinc-800 rounded-xl p-5 flex flex-col justify-between space-y-4">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-xs text-slate-450 block uppercase tracking-wider font-semibold">Uang Muka (DP)</span>
                                    <span id="kpr_dp_rp" class="text-lg font-bold text-slate-800 dark:text-slate-200">Rp 0</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-450 block uppercase tracking-wider font-semibold">Jumlah Pinjaman Pokok</span>
                                    <span id="kpr_loan_amount" class="text-lg font-bold text-slate-800 dark:text-slate-200">Rp 0</span>
                                </div>
                            </div>
                            
                            <div class="border-t border-slate-200/60 dark:border-zinc-700 pt-4">
                                <span class="text-xs text-indigo-600 dark:text-indigo-400 block uppercase tracking-wider font-bold">Angsuran Per Bulan</span>
                                <span id="kpr_installment" class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Location Map -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <h3 class="heading-font font-bold text-lg text-slate-900 dark:text-white mb-4">Peta Lokasi</h3>
                <div id="map" class="w-full h-80 rounded-xl overflow-hidden border border-slate-200 dark:border-zinc-800 z-0"></div>
            </div>

            <!-- Reviews -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <h3 class="heading-font font-bold text-lg text-slate-900 dark:text-white mb-6">Ulasan & Umpan Balik Pengguna</h3>
                
                @if($property->reviews->isEmpty())
                    <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada ulasan untuk properti ini. Jadilah yang pertama memberikan pendapat!</p>
                @else
                    <div class="space-y-6">
                        @foreach($property->reviews as $review)
                            <div class="border-b border-slate-100 dark:border-zinc-800 last:border-0 pb-6 last:pb-0">
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-zinc-800 flex items-center justify-center font-bold text-sm uppercase text-indigo-700 dark:text-indigo-400">
                                            {{ substr($review->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $review->user->name }}</h4>
                                            <p class="text-[10px] text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-0.5 text-amber-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" class="w-4 h-4">
                                                <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.6 3.102-1.196 4.622c-.22.85.704 1.522 1.4 1.077L10 15.547l4.183 2.573c.695.427 1.62-.24 1.4-1.077l-1.197-4.623 3.6-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.83-4.401z" clip-rule="evenodd" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-sm text-slate-655 dark:text-slate-350 pl-13">
                                    {{ $review->comment }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- New Review Form -->
                @auth
                    @if(Auth::id() !== $property->user_id && !$property->reviews()->where('user_id', Auth::id())->exists())
                        <div class="mt-8 pt-6 border-t border-slate-200/80 dark:border-zinc-800">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200 mb-4">Tulis Ulasan Anda</h4>
                            <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="property_id" value="{{ $property->id }}">
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-2">Rating</label>
                                    <select name="rating" required class="px-3 py-2 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                                        <option value="5">5 Bintang (Sangat Baik)</option>
                                        <option value="4">4 Bintang (Baik)</option>
                                        <option value="3">3 Bintang (Cukup)</option>
                                        <option value="2">2 Bintang (Kurang)</option>
                                        <option value="1">1 Bintang (Buruk)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-2">Komentar / Ulasan</label>
                                    <textarea name="comment" rows="3" required placeholder="Tuliskan pendapat atau kesan Anda tentang properti ini..." 
                                              class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none"></textarea>
                                </div>
                                
                                <button type="submit" class="py-2 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg shadow-md active:scale-95 transition-all text-xs">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="mt-8 pt-6 border-t border-slate-200/80 dark:border-zinc-800 text-center">
                        <p class="text-xs text-slate-550 dark:text-slate-400">Silakan <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">masuk ke akun Anda</a> untuk menulis ulasan.</p>
                    </div>
                @endauth
            </div>

        </div>

        <!-- Right Side: Pricing & CTA Actions (1/3 width) -->
        <div class="space-y-6">
            
            <!-- Pricing / Direct action card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-md sticky top-24">
                <div class="mb-6">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Harga Penawaran</span>
                    <div class="flex items-baseline gap-1 mt-1">
                        <span class="text-3xl font-extrabold text-slate-900 dark:text-white">
                            Rp {{ number_format($property->price, 0, ',', '.') }}
                        </span>
                        @if(str_contains($property->listing_type, 'sewa'))
                            <span class="text-sm font-semibold text-slate-450">/ {{ str_contains($property->listing_type, 'bulanan') ? 'bulan' : 'tahun' }}</span>
                        @endif
                    </div>
                </div>

                @auth
                    @if(Auth::id() === $property->user_id)
                        <!-- Owner View -->
                        <div class="bg-indigo-50 dark:bg-zinc-800/40 border border-indigo-150 dark:border-zinc-800 rounded-xl p-4 text-center">
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase mb-2">
                                Status Properti Anda
                            </span>
                            <p class="text-xs font-bold uppercase py-1.5 px-3 rounded-lg text-white bg-slate-700 dark:bg-zinc-700 shadow-sm">
                                Status: {{ $property->status }}
                            </p>
                            <p class="text-[11px] text-slate-450 dark:text-slate-400 mt-3">
                                Anda adalah pemilik listing properti ini. Kelola data dan status transaksi ini melalui dashboard utama Anda.
                            </p>
                            <a href="{{ route('dashboard', ['tab' => 'my-properties']) }}" class="mt-4 block w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-md text-xs active:scale-95 transition-all text-center">
                                Kelola Properti di Dashboard
                            </a>
                        </div>
                    @else
                        <!-- Customer View -->
                        <div class="space-y-4">
                            <!-- Contact Agent Section -->
                            <div>
                                <form action="{{ route('chat.start') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                                    <input type="hidden" name="message" value="Halo, saya tertarik dengan properti ini: {{ $property->title }}. Apakah masih tersedia?">
                                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-md transition-all active:scale-95 text-center flex items-center justify-center gap-2 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785 4.5 4.5 0 002.383-.694c.507-.317 1.1-.284 1.624.044 1.137.707 2.485 1.11 3.913 1.11z" />
                                        </svg>
                                        Tanya Detail via Chat
                                    </button>
                                </form>
                            </div>

                            <hr class="border-slate-100 dark:border-zinc-800 my-4">

                            <!-- Direct Action Section (Sewa/Beli) -->
                            <div>
                                @if($property->listing_type === 'jual')
                                    <!-- Jual / Beli Section -->
                                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 uppercase mb-3">Ajukan Minat Beli</h4>
                                    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-3">
                                        @csrf
                                        <input type="hidden" name="property_id" value="{{ $property->id }}">
                                        
                                        <div>
                                            <label class="block text-[10px] font-semibold text-slate-450 dark:text-slate-400 uppercase mb-1">
                                                Harga Penawaran Anda (Rp) <span class="text-[9px] text-slate-400 lowercase italic font-normal">(bisa ditawar/nego, tidak mengubah harga asli properti)</span>
                                            </label>
                                            <div class="relative rounded-lg shadow-sm">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-slate-450 text-xs">Rp</span>
                                                </div>
                                                <input type="hidden" name="agreed_price" id="raw_agreed_price" value="{{ (int)$property->price }}">
                                                <input type="text" id="formatted_agreed_price" required
                                                       value="{{ number_format($property->price, 0, ',', '.') }}"
                                                       class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none font-semibold"
                                                       oninput="formatRupiah(this, 'raw_agreed_price')">
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-md transition-all active:scale-95 text-center flex items-center justify-center gap-2 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Ajukan Penawaran Beli
                                        </button>
                                    </form>
                                @else
                                    <!-- Sewa Section -->
                                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-400 uppercase mb-3">Ajukan Permintaan Sewa</h4>
                                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-3">
                                        @csrf
                                        <input type="hidden" name="property_id" value="{{ $property->id }}">
                                        <input type="hidden" name="duration_type" value="{{ $property->listing_type === 'sewa_bulanan' ? 'bulanan' : 'tahunan' }}">
                                        
                                        <div class="grid grid-cols-1 gap-2.5">
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-450 dark:text-slate-400 uppercase mb-1">Tanggal Mulai Sewa</label>
                                                <input type="date" id="rent_start_date" name="start_date" required min="{{ date('Y-m-d') }}"
                                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-450 dark:text-slate-400 uppercase mb-1">Tanggal Selesai Sewa</label>
                                                <input type="date" id="rent_end_date" name="end_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                                            </div>
                                        </div>

                                        <!-- RENTAL CALCULATOR SUMMARY (DYNAMIC) -->
                                        <div id="rent-calc-box" class="hidden bg-slate-50 dark:bg-zinc-800/60 rounded-xl p-3 border border-slate-100 dark:border-zinc-800/80 text-xs space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-slate-400">Durasi Sewa:</span>
                                                <span id="rent-calc-duration" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-400">Harga Sewa:</span>
                                                <span id="rent-calc-price" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-400">Uang Jaminan (Deposit):</span>
                                                <span id="rent-calc-deposit" class="font-bold text-slate-800 dark:text-slate-200">-</span>
                                            </div>
                                            <div class="flex justify-between border-t border-slate-200/60 dark:border-zinc-700 pt-2 font-bold text-sm">
                                                <span class="text-indigo-600 dark:text-indigo-400">Total Pembayaran:</span>
                                                <span id="rent-calc-total" class="text-indigo-700 dark:text-indigo-300">-</span>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-md transition-all active:scale-95 text-center flex items-center justify-center gap-2 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                            Ajukan Sewa Properti
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Guests View -->
                    <div class="text-center bg-slate-50 dark:bg-zinc-800/40 rounded-xl p-4 border border-slate-100 dark:border-zinc-800">
                        <p class="text-xs text-slate-550 dark:text-slate-400 mb-3">
                            Silakan masuk atau daftar akun untuk melakukan chat dengan pemilik atau mengajukan transaksi sewa/beli.
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" class="py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg shadow-sm text-xs text-center transition-all">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="py-2 bg-slate-200 hover:bg-slate-350 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-white font-bold rounded-lg shadow-sm text-xs text-center transition-all">
                                Daftar
                            </a>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Agent/Owner details box -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Diposting Oleh</h4>
                
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-zinc-800 flex items-center justify-center font-bold text-sm uppercase text-indigo-700 dark:text-indigo-400">
                        {{ substr($property->user->name, 0, 2) }}
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white flex items-center gap-1">
                            {{ $property->user->name }}
                            @if($property->user->is_verified)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-blue-500" title="Terverifikasi">
                                    <path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-6.996-3.993a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-2.47 2.47-9.7-9.7z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </h4>
                        <p class="text-[10px] font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mt-0.5">{{ $property->user->role->name }}</p>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</div>

<!-- Initialize Leaflet Interactive Map -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var lat = {{ $property->latitude ?? -6.2088 }};
        var lng = {{ $property->longitude ?? 106.8456 }};
        
        var map = L.map('map').setView([lat, lng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        var marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup("<b>{{ $property->title }}</b><br>{{ $property->location->name }}").openPopup();
    });
</script>

<!-- Calculators Implementation -->
<script>
    // 1. Rental Price Calculator
    function calculateRental() {
        const startVal = document.getElementById('rent_start_date').value;
        const endVal = document.getElementById('rent_end_date').value;
        const calcBox = document.getElementById('rent-calc-box');
        
        if (!startVal || !endVal) {
            calcBox.classList.add('hidden');
            return;
        }
        
        const start = new Date(startVal);
        const end = new Date(endVal);
        
        if (start >= end) {
            calcBox.classList.add('hidden');
            return;
        }
        
        // Calculate difference in months
        const yearsDiff = end.getFullYear() - start.getFullYear();
        const monthsDiff = (yearsDiff * 12) + (end.getMonth() - start.getMonth());
        
        // Adjust for partial months
        let adjustedMonths = monthsDiff;
        if (end.getDate() < start.getDate()) {
            adjustedMonths = Math.max(0, adjustedMonths - 1);
        }
        
        const listingType = "{{ $property->listing_type }}";
        const pricePerUnit = {{ $property->price }};
        
        let durationText = "";
        let totalPrice = 0;
        let deposit = 0;
        
        if (listingType === 'sewa_bulanan') {
            const duration = adjustedMonths <= 0 ? 1 : adjustedMonths;
            durationText = duration + " Bulan";
            totalPrice = pricePerUnit * duration;
            deposit = pricePerUnit * 0.5;
        } else {
            const years = Math.floor(adjustedMonths / 12);
            const duration = years <= 0 ? 1 : years;
            durationText = duration + " Tahun";
            totalPrice = pricePerUnit * 12 * duration;
            deposit = pricePerUnit * 1.0;
        }
        
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        
        document.getElementById('rent-calc-duration').textContent = durationText;
        document.getElementById('rent-calc-price').textContent = formatter.format(pricePerUnit) + (listingType === 'sewa_bulanan' ? ' / bulan' : ' / bulan');
        document.getElementById('rent-calc-deposit').textContent = formatter.format(deposit);
        document.getElementById('rent-calc-total').textContent = formatter.format(totalPrice + deposit);
        
        calcBox.classList.remove('hidden');
    }
    
    // 2. KPR (Mortgage) Simulation Calculator
    function calculateKPR() {
        const kprPriceEl = document.getElementById('kpr_price');
        if (!kprPriceEl) return;
        
        const price = parseFloat(kprPriceEl.value) || 0;
        const dpPercent = parseFloat(document.getElementById('kpr_dp_percent').value) || 0;
        const annualRate = parseFloat(document.getElementById('kpr_interest').value) || 0;
        const tenorYears = parseInt(document.getElementById('kpr_tenor').value) || 15;
        
        const dpAmount = price * (dpPercent / 100);
        const loanAmount = Math.max(0, price - dpAmount);
        
        // Monthly interest rate
        const monthlyRate = (annualRate / 100) / 12;
        // Total payment periods (months)
        const totalMonths = tenorYears * 12;
        
        let monthlyInstallment = 0;
        if (loanAmount > 0) {
            if (monthlyRate === 0) {
                monthlyInstallment = loanAmount / totalMonths;
            } else {
                // formula: [P * r * (1+r)^n] / [(1+r)^n - 1]
                monthlyInstallment = (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, totalMonths)) / (Math.pow(1 + monthlyRate, totalMonths) - 1);
            }
        }
        
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        
        document.getElementById('kpr_dp_rp').textContent = formatter.format(dpAmount);
        document.getElementById('kpr_loan_amount').textContent = formatter.format(loanAmount);
        document.getElementById('kpr_installment').textContent = formatter.format(monthlyInstallment);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Init Rental Listeners
        const startEl = document.getElementById('rent_start_date');
        const endEl = document.getElementById('rent_end_date');
        if (startEl && endEl) {
            startEl.addEventListener('change', calculateRental);
            endEl.addEventListener('change', calculateRental);
        }
        
        // Init KPR Listeners
        const kprPrice = document.getElementById('kpr_price');
        const kprDp = document.getElementById('kpr_dp_percent');
        const kprRate = document.getElementById('kpr_interest');
        const kprTenor = document.getElementById('kpr_tenor');
        
        if (kprPrice && kprDp && kprRate && kprTenor) {
            kprPrice.addEventListener('input', calculateKPR);
            kprDp.addEventListener('input', calculateKPR);
            kprRate.addEventListener('input', calculateKPR);
            kprTenor.addEventListener('change', calculateKPR);
            
            // Run initial KPR calculation
            calculateKPR();
        }
    });
</script>
@endsection
