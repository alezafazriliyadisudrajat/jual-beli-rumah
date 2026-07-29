@extends('layouts.app')

@section('title', 'Dashboard Saya - PropertiImpian')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="py-6" x-data="dashboardHandler()" x-init="init()">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Navigation (Responsive) -->
        <aside class="w-full lg:w-64 flex-shrink-0 lg:min-h-[calc(100vh-10rem)] flex">
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-4 shadow-sm space-y-1 w-full flex flex-col min-h-full">
                <!-- User Quick Info -->
                <div class="p-3 mb-4 bg-slate-50 dark:bg-zinc-950 rounded-xl flex items-center gap-3">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-500/20">
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white uppercase">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200 truncate">{{ Auth::user()->name }}</h4>
                        <span class="px-2 py-0.5 mt-0.5 inline-block rounded-full text-[9px] font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300 uppercase tracking-wider">
                            {{ Auth::user()->role->name }}
                        </span>
                    </div>
                </div>

                <!-- Navigation Links (Alpine Tab Toggles) -->
                <button @click="setTab('overview')" :class="tab === 'overview' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    Ringkasan
                </button>
                <button @click="setTab('profile')" :class="tab === 'profile' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Profil Saya
                </button>

                <button @click="setTab('chat')" :class="tab === 'chat' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785 4.5 4.5 0 002.383-.694c.507-.317 1.1-.284 1.624.044 1.137.707 2.485 1.11 3.913 1.11z"/></svg>
                    Chat Percakapan
                </button>

                <!-- Buyer specific tab -->
                @if(Auth::user()->role_id === 5)
                    <button @click="setTab('my-rentals')" :class="tab === 'my-rentals' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        Sewa Saya
                    </button>
                    <button @click="setTab('my-purchases')" :class="tab === 'my-purchases' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75-3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v5.25a2.25 2.25 0 002.25 2.25z"/></svg>
                        Transaksi Saya
                    </button>
                @endif

                <!-- Owner / Agent specific tab -->
                @if(in_array(Auth::user()->role_id, [3, 4]))
                    <button @click="setTab('my-properties')" :class="tab === 'my-properties' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21h8.25M17.25 3.75h-10.5a2.25 2.25 0 00-2.25 2.25v15h15v-15a2.25 2.25 0 00-2.25-2.25z"/></svg>
                        Properti Saya
                    </button>
                    <button @click="setTab('owner-deals')" :class="tab === 'owner-deals' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Persetujuan & Transaksi
                    </button>
                @endif

                <!-- Admin specific tab -->
                @if(in_array(Auth::user()->role_id, [1, 2]))
                    <button @click="setTab('admin-moderation')" :class="tab === 'admin-moderation' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751A11.956 11.956 0 0112 2.714z"/></svg>
                        Moderasi Properti
                    </button>
                    <button @click="setTab('admin-users')" :class="tab === 'admin-users' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.052-.031a11.95 11.95 0 01-.037-.666c0-.225.012-.447.037-.667A11.944 11.944 0 0112 3c2.17 0 4.207.576 5.963 1.584A6.06 6.06 0 0118 5.722v.03z"/></svg>
                        Manajemen User
                    </button>
                    <button @click="setTab('admin-reports')" :class="tab === 'admin-reports' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.515L12 14.182m0 0l.879-.515L15 15.182M12 6v6m0 0l-3-2.818M12 12l3-2.818"/></svg>
                        Laporan Keuangan
                    </button>
                    <button @click="setTab('admin-logs')" :class="tab === 'admin-logs' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        Log Audit Trail
                    </button>
                    <button @click="setTab('admin-master')" :class="tab === 'admin-master' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-zinc-800/50'" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                        Data Master
                    </button>
                @endif
            </div>
        </aside>
 
        <!-- Main Dashboard Viewport -->
        <main class="flex-grow">
            @include('dashboard.overview')
            @include('dashboard.profile')
            @include('dashboard.chat')
            @include('dashboard.my-rentals')
            @include('dashboard.my-purchases')
            @include('dashboard.my-properties')
            @include('dashboard.owner-deals')
            @include('dashboard.admin-moderation')
            @include('dashboard.admin-users')
            @include('dashboard.admin-reports')
            @include('dashboard.admin-logs')
            @include('dashboard.admin-master')
        </main>
    </div>
</div>

<!-- Mock checkout hidden form or function -->
<script>
    function triggerMockPayment(payableType, payableId, amount, method) {
        Swal.fire({
            title: 'Simulasi Pembayaran',
            text: `Apakah Anda ingin membayar sebesar Rp ${new Intl.NumberFormat('id-ID').format(amount)} via ${method}?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Bayar Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.showLoading();
                
                // 1. Charge tagihan
                fetch('/payments/charge', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        payable_type: payableType,
                        payable_id: payableId,
                        amount: amount,
                        method: method
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const payment = data.data;
                        
                        // Process payment success callback using the signature key returned directly in payment charge data
                        fetch('/payments/webhook', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                gateway_reference: payment.gateway_reference,
                                status: 'success',
                                signature_key: payment.signature
                            })
                        })
                        .then(resWeb => resWeb.json())
                        .then(dataWeb => {
                            if (dataWeb.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Pembayaran berhasil dikonfirmasi secara aman!',
                                    confirmButtonColor: '#10b981'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', dataWeb.message || 'Webhook failed.', 'error');
                            }
                        });
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error!', 'Terjadi kesalahan pembayaran.', 'error');
                });
            }
        });
    }
</script>

<!-- AlpineJS Dashboard Controller & Chat Polling Logic -->
<script>
    function dashboardHandler() {
        return {
            tab: '{{ request()->tab ?? "overview" }}',
            chartLoading: true,
            resizeObserver: null,
            showCreateForm: false,
            isEditMode: false,
            propertyForm: {
                id: null,
                title: '',
                category_id: '',
                listing_type: 'jual',
                price: '',
                formatted_price: '',
                building_area: '',
                land_area: '',
                location_id: '',
                certificate_type: '',
                condition: 'baru',
                facing: 'timur',
                floors_count: '',
                floor_location: '',
                interior_type: 'tak berperabot',
                maid_bedrooms: 0,
                garages_count: 0,
                carports_count: 0,
                telephone_lines: 0,
                electricity: '',
                road_access: '',
                has_pam_water: false,
                has_ground_water: false,
                description: '',
            },
            showProfileModal: false,
            showZoomModal: false,
            zoomScale: 1.0,
            chatImageSelected: '',
            zoomImageUrl: '',
            conversations: [],
            messages: [],
            activeChatId: {{ $activeChatId ?? 'null' }},
            activeChatPartnerName: '',
            activeChatPropertyTitle: '',
            chatInput: '',
            pollingInterval: null,
            presenceInterval: null,
            chartInstance: null,

            editProperty(prop) {
                this.isEditMode = true;
                this.propertyForm = {
                    id: prop.id,
                    title: prop.title,
                    category_id: prop.category_id,
                    listing_type: prop.listing_type,
                    price: prop.price,
                    formatted_price: this.formatRupiahVal(prop.price),
                    building_area: Math.round(prop.building_area),
                    land_area: Math.round(prop.land_area),
                    location_id: prop.location_id,
                    certificate_type: prop.certificate_type || '',
                    condition: prop.condition || 'baru',
                    facing: prop.facing || 'timur',
                    floors_count: prop.floors_count || '',
                    floor_location: prop.floor_location || '',
                    interior_type: prop.interior_type || 'tak berperabot',
                    maid_bedrooms: prop.maid_bedrooms || 0,
                    garages_count: prop.garages_count || 0,
                    carports_count: prop.carports_count || 0,
                    telephone_lines: prop.telephone_lines || 0,
                    electricity: prop.electricity || '',
                    road_access: prop.road_access || '',
                    has_pam_water: !!prop.has_pam_water,
                    has_ground_water: !!prop.has_ground_water,
                    description: prop.description,
                };
                this.showCreateForm = true;
                this.$nextTick(() => {
                    const rp = document.getElementById('raw_price');
                    if (rp) rp.value = prop.price;
                    const pView = document.getElementById('my-properties-view');
                    if (pView) pView.scrollIntoView({ behavior: 'smooth' });
                });
            },
            resetPropertyForm() {
                this.isEditMode = false;
                this.propertyForm = {
                    id: null,
                    title: '',
                    category_id: '',
                    listing_type: 'jual',
                    price: '',
                    formatted_price: '',
                    building_area: '',
                    land_area: '',
                    location_id: '',
                    certificate_type: '',
                    condition: 'baru',
                    facing: 'timur',
                    floors_count: '',
                    floor_location: '',
                    interior_type: 'tak berperabot',
                    maid_bedrooms: 0,
                    garages_count: 0,
                    carports_count: 0,
                    telephone_lines: 0,
                    electricity: '',
                    road_access: '',
                    has_pam_water: false,
                    has_ground_water: false,
                    description: '',
                };
                const rp = document.getElementById('raw_price');
                if (rp) rp.value = '';
            },
            formatRupiahVal(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            },

            zoomIn() {
                this.zoomScale = Math.min(this.zoomScale + 0.25, 3.0);
            },
            zoomOut() {
                this.zoomScale = Math.max(this.zoomScale - 0.25, 0.5);
            },
            showZoomedChatImage(url) {
                this.zoomImageUrl = url;
                this.zoomScale = 1.0;
                this.showZoomModal = true;
            },

            confirmProfileUpdate(event) {
                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: 'Apakah Anda yakin ingin memperbarui data profil Anda?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                });
            },

            init() {
                // Fetch user conversations if they visit chat or on load
                this.fetchConversations();
                if (this.activeChatId) {
                    this.selectConversation(this.activeChatId);
                }
                if (this.tab === 'chat') {
                    this.startPresencePing();
                }
                
                // Initialize ResizeObserver on load
                this.initChart();
            },

            setTab(tabName) {
                this.tab = tabName;
                
                // Update URL query parameter without reloading
                const url = new URL(window.location);
                url.searchParams.set('tab', tabName);
                window.history.replaceState({}, '', url);

                if (tabName === 'overview') {
                    this.chartLoading = true;
                    this.initChart();
                } else {
                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                        this.chartInstance = null;
                    }
                    if (this.resizeObserver) {
                        this.resizeObserver.disconnect();
                        this.resizeObserver = null;
                    }
                }

                if (tabName === 'chat') {
                    this.fetchConversations();
                    this.startPresencePing();
                } else {
                    this.stopPolling();
                    this.stopPresencePing();
                }
            },

            initChart() {
                if (this.tab !== 'overview') return;

                const canvas = document.getElementById('revenueChart');
                if (!canvas) return;

                const parent = canvas.parentElement;
                if (!parent) return;

                // Clean up any existing observer before creating a new one
                if (this.resizeObserver) {
                    this.resizeObserver.disconnect();
                    this.resizeObserver = null;
                }

                // Create a ResizeObserver to draw the chart only when parent element container has positive height/width
                this.resizeObserver = new ResizeObserver((entries) => {
                    for (let entry of entries) {
                        const { width, height } = entry.contentRect;
                        if (width > 0 && height > 0) {
                            this.renderChart(canvas);
                        }
                    }
                });

                this.resizeObserver.observe(parent);
            },

            renderChart(canvas) {
                if (this.chartInstance) return;

                const ctx = canvas.getContext('2d');
                if (!ctx) return;

                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Total Nilai (Rp)',
                            data: @json($chartData),
                            fill: true,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 3,
                            tension: 0.35,
                            pointBackgroundColor: '#4f46e5',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#4f46e5',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: { family: 'Outfit', size: 13 },
                                bodyFont: { family: 'Inter', size: 12 },
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Nilai: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        family: 'Inter',
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        family: 'Inter',
                                        size: 11
                                    },
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            }
                        }
                    }
                });

                // Hide loading spinner
                this.chartLoading = false;
            },

            fetchConversations() {
                fetch('/chat/conversations', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.conversations = data.data;
                            if (this.activeChatId) {
                                const current = this.conversations.find(c => c.id === this.activeChatId);
                                if (current) {
                                    this.activeChatPartnerName = this.getChatPartnerName(current);
                                    this.activeChatPropertyTitle = current.property ? current.property.title : 'Properti';
                                }
                            }
                        }
                    });
            },

            getChatPartner(conv) {
                const currentUserId = {{ Auth::id() }};
                return conv.participant_one === currentUserId ? conv.user_two : conv.user_one;
            },

            getUserStatusText(conv) {
                const partner = this.getChatPartner(conv);
                if (!partner || !partner.last_seen_at) {
                    return 'Offline';
                }
                
                const lastSeen = new Date(partner.last_seen_at);
                const now = new Date();
                const diffMs = now - lastSeen;
                
                // Jika selisih kurang dari 10 detik, user dianggap sedang online di chat
                const diffSec = Math.floor(diffMs / 1000);
                if (diffSec < 10) {
                    return 'Online';
                } else if (diffSec < 60) {
                    return `Offline ${diffSec} detik yang lalu`;
                } else {
                    const diffMin = Math.floor(diffMs / 60000);
                    if (diffMin < 60) {
                        return `Offline ${diffMin} menit yang lalu`;
                    } else {
                        const diffHr = Math.floor(diffMin / 60);
                        if (diffHr < 24) {
                            return `Offline ${diffHr} jam yang lalu`;
                        } else {
                            const diffDay = Math.floor(diffHr / 24);
                            return `Offline ${diffDay} hari yang lalu`;
                        }
                    }
                }
            },

            getActiveChatStatus() {
                if (!this.activeChatId) return '';
                const conv = this.conversations.find(c => c.id === this.activeChatId);
                return conv ? this.getUserStatusText(conv) : '';
            },

            getChatPartnerName(conv) {
                const currentUserId = {{ Auth::id() }};
                return conv.participant_one === currentUserId ? conv.user_two.name : conv.user_one.name;
            },

            selectConversation(id) {
                this.activeChatId = id;
                this.messages = [];
                this.fetchMessages();
                this.startPolling();
                
                // Set partner details
                setTimeout(() => {
                    const conv = this.conversations.find(c => c.id === id);
                    if (conv) {
                        this.activeChatPartnerName = this.getChatPartnerName(conv);
                        this.activeChatPropertyTitle = conv.property ? conv.property.title : 'Properti';
                    }
                }, 100);
            },

            fetchMessages() {
                if (!this.activeChatId) return;
                fetch(`/chat/conversations/${this.activeChatId}/messages`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.messages = data.data;
                            this.scrollToBottom();
                        }
                    });
            },

            sendChatMessage() {
                if (!this.activeChatId) return;
                const fileInput = document.getElementById('chat-image-input');
                const hasFile = fileInput && fileInput.files[0];
                if (!this.chatInput.trim() && !hasFile) return;

                const formData = new FormData();
                formData.append('message', this.chatInput);
                if (hasFile) {
                    formData.append('image', fileInput.files[0]);
                }

                this.chatInput = '';
                this.chatImageSelected = '';
                if (fileInput) fileInput.value = '';
                
                fetch(`/chat/conversations/${this.activeChatId}/messages`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.messages.push(data.data);
                        this.scrollToBottom();
                    } else {
                        Swal.fire('Gagal', data.message || 'Gagal mengirim pesan.', 'error');
                    }
                });
            },

            startPolling() {
                this.stopPolling();
                this.pollingInterval = setInterval(() => {
                    this.fetchMessages();
                }, 3000);
            },

            stopPolling() {
                if (this.pollingInterval) {
                    clearInterval(this.pollingInterval);
                    this.pollingInterval = null;
                }
            },

            startPresencePing() {
                this.stopPresencePing();
                this.pingPresence();
                this.presenceInterval = setInterval(() => {
                    this.pingPresence();
                }, 5000);
            },

            stopPresencePing() {
                if (this.presenceInterval) {
                    clearInterval(this.presenceInterval);
                    this.presenceInterval = null;
                }
            },

            pingPresence() {
                fetch('/chat/ping', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
            },

            scrollToBottom() {
                setTimeout(() => {
                    const box = document.getElementById('chat-messages-box');
                    if (box) {
                        box.scrollTop = box.scrollHeight;
                    }
                }, 100);
            },

            formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' - ' + date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            }
        };
    }

    // Helper functions for deal management with SweetAlert2
    function updateDealStatus(type, id, status) {
        Swal.fire({
            title: 'Konfirmasi Perubahan Status',
            text: `Apakah Anda yakin ingin mengubah status transaksi ini menjadi: ${status}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = type === 'booking' ? `/bookings/${id}/status` : `/transactions/${id}/status`;
                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Terjadi kesalahan.'
                        });
                    }
                });
            }
        });
    }

    // Admin helpers with SweetAlert2
    function adminModerateProperty(id, status) {
        Swal.fire({
            title: 'Moderasi Properti',
            text: `Apakah Anda yakin ingin memoderasi properti ini menjadi ${status}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/properties/${id}/moderate`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Gagal memproses moderasi.'
                        });
                    }
                });
            }
        });
    }

    function adminVerifyUser(id, isVerified) {
        Swal.fire({
            title: 'Status Verifikasi Akun',
            text: 'Apakah Anda yakin ingin memperbarui status verifikasi untuk user ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${id}/verify`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ is_verified: isVerified })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Gagal mengubah status verifikasi.'
                        });
                    }
                });
            }
        });
    }

    function adminBanUser(id) {
        Swal.fire({
            title: 'Tangguhkan Akun',
            text: 'Apakah Anda yakin ingin menonaktifkan/membekukan user ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Blokir!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${id}/ban`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    }).then(() => {
                        location.reload();
                    });
                });
            }
        });
    }

    // Properti Deletion Helper with SweetAlert2
    function confirmDeleteProperty(id) {
        Swal.fire({
            title: 'Hapus Listing Properti?',
            text: 'Tindakan ini permanen. Apakah Anda yakin ingin menghapus properti ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-property-form-${id}`).submit();
            }
        });
    }
</script>
@endsection
