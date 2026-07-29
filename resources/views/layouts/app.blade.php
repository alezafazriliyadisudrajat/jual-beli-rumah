<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Jual Beli & Sewa Properti Premium')</title>
    <meta name="description" content="Platform terpercaya untuk mencari, menyewa, dan membeli rumah, ruko, apartemen, atau tanah impian Anda dengan aman dan transparan.">

    <!-- Fonts: Outfit and Inter from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite & CDN Fallback for absolute rendering stability) -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            outfit: ['Outfit', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif

    <!-- Leaflet JS & CSS for Free Interactive Maps (OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 for premium alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .heading-font {
            font-family: 'Outfit', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .dark .glass {
            background: rgba(22, 22, 21, 0.7);
        }

        /* Premium SweetAlert2 Global Customizations */
        .swal2-popup:not(.swal2-toast) {
            border-radius: 24px !important;
            font-family: 'Inter', sans-serif !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
            padding: 2rem 1.5rem !important;
        }
        .dark .swal2-popup:not(.swal2-toast) {
            background-color: #18181b !important;
            border: 1px solid rgba(63, 63, 70, 0.6) !important;
            color: #f4f4f5 !important;
        }
        .swal2-title:not(.swal2-toast *) {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            font-size: 1.5rem !important;
            margin-top: 1rem !important;
        }
        .dark .swal2-title:not(.swal2-toast *) {
            color: #ffffff !important;
        }
        .swal2-html-container:not(.swal2-toast *) {
            color: #475569 !important;
            font-size: 0.95rem !important;
            line-height: 1.6 !important;
            margin-top: 0.5rem !important;
        }
        .dark .swal2-html-container:not(.swal2-toast *) {
            color: #a1a1aa !important;
        }
        .swal2-confirm:not(.swal2-toast *) {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
            padding: 12px 28px !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease-in-out !important;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25) !important;
            outline: none !important;
        }
        .swal2-confirm:not(.swal2-toast *):hover {
            background-color: #4338ca !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.35) !important;
        }
        .swal2-confirm:not(.swal2-toast *):active {
            transform: translateY(1px) !important;
        }
        .swal2-cancel:not(.swal2-toast *) {
            background-color: #f1f5f9 !important;
            color: #64748b !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
            padding: 12px 28px !important;
            font-size: 0.875rem !important;
            transition: all 0.2s ease-in-out !important;
            outline: none !important;
        }
        .dark .swal2-cancel:not(.swal2-toast *) {
            background-color: #27272a !important;
            color: #a1a1aa !important;
        }
        .swal2-cancel:not(.swal2-toast *):hover {
            background-color: #e2e8f0 !important;
        }
        .dark .swal2-cancel:not(.swal2-toast *):hover {
            background-color: #3f3f46 !important;
        }
        .swal2-icon.swal2-success:not(.swal2-toast *) {
            border-color: #10b981 !important;
        }
        .swal2-icon.swal2-success:not(.swal2-toast *) .swal2-success-ring {
            border: .25em solid rgba(16, 185, 129, 0.2) !important;
        }
        .swal2-icon.swal2-success:not(.swal2-toast *) [class^=swal2-success-line] {
            background-color: #10b981 !important;
        }
        .swal2-icon.swal2-error:not(.swal2-toast *) {
            border-color: #ef4444 !important;
        }
        .swal2-icon.swal2-error:not(.swal2-toast *) [class^=swal2-x-mark-line] {
            background-color: #ef4444 !important;
        }
        .swal2-icon.swal2-info:not(.swal2-toast *) {
            border-color: #3b82f6 !important;
        }
        .swal2-icon.swal2-info:not(.swal2-toast *) .swal2-info-content {
            color: #3b82f6 !important;
        }
        .swal2-icon.swal2-warning:not(.swal2-toast *) {
            border-color: #f59e0b !important;
        }
        .swal2-icon.swal2-warning:not(.swal2-toast *) .swal2-warning-content {
            color: #f59e0b !important;
        }
        .swal2-actions:not(.swal2-toast *) {
            margin-top: 1.5rem !important;
            gap: 0.75rem !important;
        }

        /* Sleek & Compact Toasts specific overrides */
        .swal2-popup.swal2-toast {
            border-radius: 12px !important;
            padding: 0.5rem 0.75rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            background-color: #ffffff !important;
        }
        .dark .swal2-popup.swal2-toast {
            background-color: #18181b !important;
            border: 1px solid rgba(63, 63, 70, 0.6) !important;
        }
        .swal2-popup.swal2-toast .swal2-title {
            font-family: 'Inter', sans-serif !important;
            font-size: 0.825rem !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            margin: 0 0.5rem !important;
            padding: 0 !important;
            line-height: 1.4 !important;
        }
        .dark .swal2-popup.swal2-toast .swal2-title {
            color: #f4f4f5 !important;
        }
        .swal2-popup.swal2-toast .swal2-icon {
            margin: 0 0.5rem 0 0 !important;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 text-slate-900 dark:text-slate-100 h-full flex flex-col transition-colors duration-300">
    <div class="flex-grow">
        <!-- Premium Navigation Header -->
        <header class="sticky top-0 z-40 w-full border-b border-slate-200/80 dark:border-zinc-800/80 glass">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </div>
                    <span class="heading-font font-bold text-xl tracking-tight bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-white dark:to-indigo-200 bg-clip-text text-transparent">PropertiImpian</span>
                </a>

                <!-- User Navigation Actions -->
                <nav class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400 transition-colors">Cari Properti</a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400 transition-colors">Dashboard</a>
                        
                        <!-- Role Badge & User Info -->
                        <div class="flex items-center gap-2 mr-1">
                            <div class="hidden md:flex flex-col items-end text-xs">
                                <span class="font-semibold">{{ Auth::user()->name }}</span>
                                <span class="px-2 py-0.5 mt-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300 uppercase tracking-wider">
                                    {{ Auth::user()->role->name ?? 'User' }}
                                </span>
                            </div>
                            @if(Auth::user()->avatar)
                                <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-500/20">
                            @else
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-[11px] uppercase tracking-wider">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                            @endif
                        </div>

                        <!-- Logout Action -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="button" onclick="confirmLogout()" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-rose-600 dark:text-slate-400 dark:hover:bg-zinc-800 dark:hover:text-rose-400 transition-colors" title="Keluar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20 active:scale-95 transition-all">Daftar</a>
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Flash Messages (SweetAlert2 premium toast alerts) -->
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            window.Toast = Toast;

            document.addEventListener('DOMContentLoaded', function () {
                @if(session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('success') }}"
                    });
                @endif

                @if(session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: "{{ session('error') }}"
                    });
                @endif

                @if(session('info'))
                    Toast.fire({
                        icon: 'info',
                        title: "{{ session('info') }}"
                    });
                @endif

                @if($errors->any())
                    Toast.fire({
                        icon: 'error',
                        title: "{{ $errors->first() }}"
                    });
                @endif
            });
        </script>

        <!-- Main Page Content -->
        <main class="{{ request()->routeIs('dashboard') ? 'w-full px-4 sm:px-6 lg:px-8 py-6' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6' }}">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-zinc-900 border-t border-slate-200 dark:border-zinc-800 py-8 mt-12 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between text-slate-500 dark:text-slate-400 text-sm">
            <p>&copy; {{ date('Y') }} PropertiImpian. Hak Cipta Dilindungi Undang-Undang.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-indigo-600 transition-colors">Ketentuan Layanan</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Bantuan</a>
            </div>
        </div>
    </footer>
    <!-- Prevent Double Submit Globally -->
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                btn.style.opacity = '0.6';
                btn.style.cursor = 'not-allowed';
            });
        });
    </script>
    <!-- Rupiah Formatter Script -->
    <script>
        function formatRupiah(element, rawInputId) {
            let value = element.value.replace(/[^0-9]/g, '');
            let rawInput = document.getElementById(rawInputId);
            if (rawInput) {
                rawInput.value = value;
            }
            
            if (value) {
                let numberString = value.toString();
                let sisa = numberString.length % 3;
                let rupiah = numberString.substr(0, sisa);
                let ribuan = numberString.substr(sisa).match(/\d{3}/gi);
                
                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                
                element.value = rupiah;
            } else {
                element.value = '';
            }
        }

        function initFormattedRupiah(formattedElementId, rawInputId) {
            let element = document.getElementById(formattedElementId);
            let rawInput = document.getElementById(rawInputId);
            if (element && rawInput && rawInput.value) {
                element.value = rawInput.value;
                formatRupiah(element, rawInputId);
            }
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari akun Anda?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('logout-form');
                    if (form) {
                        form.submit();
                    }
                }
            });
        }
    </script>
</body>
</html>
