@extends('layouts.app')

@section('title', 'Cari Rumah, Ruko & Properti Impian Anda - PropertiImpian')

@section('content')
<!-- Hero Section -->
<div class="relative rounded-3xl overflow-hidden mb-12 bg-gradient-to-tr from-indigo-900 via-slate-900 to-zinc-900 text-white py-16 px-8 md:px-16 shadow-2xl">
    <div class="absolute inset-0 opacity-15 mix-blend-overlay bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="relative max-w-3xl">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-6 uppercase tracking-wider">
            Platform Properti No.1 Paling Aman & Terpercaya
        </span>
        <h1 class="heading-font font-extrabold text-4xl md:text-5xl lg:text-6xl tracking-tight leading-tight">
            Temukan Properti <span class="bg-gradient-to-r from-indigo-400 to-violet-300 bg-clip-text text-transparent">Terbaik</span> Anda Bersama Kami
        </h1>
        <p class="mt-4 text-slate-300 text-lg max-w-xl">
            Transaksi aman terproteksi, bebas dari penipuan. Cari rumah tinggal, ruko komersial, apartemen modern, atau kavling tanah strategis.
        </p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="-mt-20 relative z-10 max-w-5xl mx-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-200/60 dark:border-zinc-800 p-6 mb-12">
    <form action="{{ url('/') }}" method="GET" class="space-y-4">
        <!-- Main Search Bar -->
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-grow relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                    </svg>
                </span>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari properti berdasarkan judul atau deskripsi..." 
                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm">
            </div>
            <button type="submit" class="md:w-36 py-3 px-6 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-md shadow-indigo-600/10 active:scale-95 transition-all text-sm">
                Cari Sekarang
            </button>
        </div>

        <!-- Advanced Filters Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2 border-t border-slate-100 dark:border-zinc-800">
            <!-- Category -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tipe Properti</label>
                <select name="category_id" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    <option value="">Semua Tipe</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Location -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Lokasi Wilayah</label>
                <select name="location_id" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ ($filters['location_id'] ?? '') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Transaksi -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Kategori Transaksi</label>
                <select name="listing_type" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    <option value="">Semua Transaksi</option>
                    <option value="jual" {{ ($filters['listing_type'] ?? '') == 'jual' ? 'selected' : '' }}>Jual</option>
                    <option value="sewa_bulanan" {{ ($filters['listing_type'] ?? '') == 'sewa_bulanan' ? 'selected' : '' }}>Sewa Bulanan</option>
                    <option value="sewa_tahunan" {{ ($filters['listing_type'] ?? '') == 'sewa_tahunan' ? 'selected' : '' }}>Sewa Tahunan</option>
                </select>
            </div>

            <!-- Urutan -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Urutkan</label>
                <select name="sort" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    <option value="newest" {{ ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ ($filters['sort'] ?? '') == 'price_asc' ? 'selected' : '' }}>Termurah</option>
                    <option value="price_desc" {{ ($filters['sort'] ?? '') == 'price_desc' ? 'selected' : '' }}>Termahal</option>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Properties Grid -->
<div class="mb-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="heading-font font-bold text-2xl md:text-3xl text-slate-900 dark:text-white">Properti yang Tersedia</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Jelajahi listing properti pilihan kami</p>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-100 dark:bg-zinc-800 px-3 py-1 rounded-full">
            Total: {{ $properties->total() }} Listing
        </span>
    </div>

    @if($properties->isEmpty())
        <div class="text-center py-16 bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200/60 dark:border-zinc-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-slate-400 mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <h3 class="heading-font font-bold text-lg text-slate-700 dark:text-slate-300">Tidak ada properti ditemukan</h3>
            <p class="text-sm text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter Anda.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($properties as $prop)
                <!-- Listing Card -->
                <div class="relative group cursor-pointer bg-white dark:bg-zinc-900 border border-slate-200/70 dark:border-zinc-800 rounded-2xl overflow-hidden hover:-translate-y-1.5 transition-all duration-300 shadow-sm hover:shadow-xl flex flex-col h-full">
                    <!-- Photo Header -->
                    <div class="relative overflow-hidden aspect-[4/3] bg-slate-100 dark:bg-zinc-800">
                        <img src="{{ $prop->primary_image }}" alt="{{ $prop->title }}" 
                             class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="px-3 py-1 rounded-lg text-xs font-extrabold uppercase tracking-wide shadow-md
                                {{ $prop->listing_type === 'jual' ? 'bg-emerald-600 text-white' : 'bg-indigo-600 text-white' }}">
                                {{ $prop->listing_type === 'jual' ? 'Beli' : 'Sewa' }}
                            </span>
                            @if($prop->is_promoted)
                                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-amber-500 text-white shadow-md uppercase tracking-wider flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.6 3.102-1.196 4.622c-.22.85.704 1.522 1.4 1.077L10 15.547l4.183 2.573c.695.427 1.62-.24 1.4-1.077l-1.197-4.623 3.6-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.83-4.401z" clip-rule="evenodd" />
                                    </svg>
                                    Highlight
                                </span>
                            @endif
                        </div>

                        <!-- Price Badge -->
                        <div class="absolute bottom-4 right-4 bg-slate-900/80 backdrop-blur-sm px-3.5 py-1.5 rounded-xl text-white font-extrabold text-sm shadow-md">
                            Rp {{ number_format($prop->price, 0, ',', '.') }}
                            @if(str_contains($prop->listing_type, 'sewa'))
                                <span class="text-[10px] font-normal text-slate-300">/ {{ str_contains($prop->listing_type, 'bulanan') ? 'bln' : 'thn' }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="p-6 flex flex-col flex-grow">
                        <!-- Category / Rating -->
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-400 mb-2">
                            <span>{{ $prop->category->name }} &bull; {{ $prop->location->name }}</span>
                            <span class="flex items-center gap-1 text-amber-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.6 3.102-1.196 4.622c-.22.85.704 1.522 1.4 1.077L10 15.547l4.183 2.573c.695.427 1.62-.24 1.4-1.077l-1.197-4.623 3.6-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.83-4.401z" clip-rule="evenodd" />
                                </svg>
                                {{ $prop->average_rating }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="heading-font font-bold text-lg text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 transition-colors">
                            <a href="{{ route('properties.show', $prop->id) }}" class="after:absolute after:inset-0 after:z-10">{{ $prop->title }}</a>
                        </h3>

                        <!-- Description snippet -->
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2">
                            {{ $prop->description }}
                        </p>

                        <!-- Features icons row -->
                        <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-slate-100 dark:border-zinc-800/80 text-xs text-slate-500 dark:text-slate-400 font-medium">
                            <span class="flex items-center gap-1.5 justify-center bg-slate-50 dark:bg-zinc-800/40 py-1.5 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21h8.25" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h16.5v16.5H3.75V3.75z" />
                                </svg>
                                LB: {{ (int)$prop->building_area }}m²
                            </span>
                            <span class="flex items-center gap-1.5 justify-center bg-slate-50 dark:bg-zinc-800/40 py-1.5 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0012 20c-1.077 0-2.115-.15-3.099-.431m0-1.286c.433-.956.762-1.996.953-3.093m0 3.093c-.49-.247-.93-.578-1.309-.976M2.298 9.09a.5.5 0 01-.1-.318C2.203 7.848 3.518 7 5 7c1.482 0 2.797.848 2.802 1.772a.5.5 0 01-.1.318l-1.313 1.97A3.499 3.499 0 005.132 12h-.264a3.499 3.499 0 00-1.258-.91L2.298 9.09z" />
                                </svg>
                                KT: {{ $prop->bedrooms ?? 0 }}
                            </span>
                            <span class="flex items-center gap-1.5 justify-center bg-slate-50 dark:bg-zinc-800/40 py-1.5 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                KM: {{ $prop->bathrooms ?? 0 }}
                            </span>
                        </div>

                        <!-- Card Footer: Owner Info -->
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100 dark:border-zinc-800/80">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-zinc-800 flex items-center justify-center font-bold text-xs uppercase text-indigo-700 dark:text-indigo-400">
                                    {{ substr($prop->user->name, 0, 2) }}
                                </div>
                                <div class="text-xs">
                                    <p class="font-bold flex items-center gap-1 text-slate-800 dark:text-slate-200">
                                        {{ $prop->user->name }}
                                        @if($prop->user->is_verified)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-blue-500" title="Terverifikasi">
                                                <path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-6.996-3.993a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-2.47 2.47-9.7-9.7z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-slate-400">{{ $prop->user->role->name }}</p>
                                </div>
                            </div>

                            <!-- Favorite button -->
                            @auth
                                <button onclick="toggleFavorite(this, {{ $prop->id }})" 
                                    class="relative z-20 p-2 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="{{ Auth::user()->favorites()->where('property_id', $prop->id)->exists() ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 {{ Auth::user()->favorites()->where('property_id', $prop->id)->exists() ? 'text-rose-500' : '' }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $properties->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
    function toggleFavorite(button, propertyId) {
        fetch(`/properties/${propertyId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const svg = button.querySelector('svg');
                if (data.is_favorite) {
                    svg.setAttribute('fill', 'currentColor');
                    svg.classList.add('text-rose-500');
                } else {
                    svg.setAttribute('fill', 'none');
                    svg.classList.remove('text-rose-500');
                }
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: data.message || 'Gagal menambahkan ke favorit.'
                });
            }
        })
        .catch(err => {
            console.error('Error toggling favorite:', err);
        });
    }
</script>
@endsection

