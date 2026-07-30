<!-- 11. Tab: Admin Master Data (Admin) -->
@if(in_array(Auth::user()->role_id, [1, 2]))
    <div x-show="tab === 'admin-master'" class="space-y-8">
        <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm">
            <div>
                <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Manajemen Data Master</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola data kategori properti dan lokasi wilayah secara interaktif</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- panel kiri: Kategori Properti -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
                <div class="pb-4 border-b border-slate-100 dark:border-zinc-800">
                    <h3 class="heading-font font-bold text-lg text-slate-800 dark:text-slate-200">Kategori Properti</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tambah dan hapus kategori tipe bangunan</p>
                </div>

                <!-- Form Tambah Kategori -->
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Nama Kategori Baru</label>
                        <div class="flex gap-2">
                            <input type="text" name="name" required placeholder="Contoh: Villa, Kost, Kavling"
                                class="flex-grow px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                            <button type="submit" class="py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-xs active:scale-95 transition-all">
                                Tambah
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabel Kategori -->
                <div class="overflow-x-auto max-h-[400px]">
                    <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                        <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950 sticky top-0">
                            <tr>
                                <th class="px-4 py-3">Nama Kategori</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse($categories as $cat)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200 text-xs">{{ $cat->name }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <div class="flex justify-end items-center gap-2">
                                            <button type="button" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}')" 
                                                class="text-indigo-600 hover:text-indigo-800 font-bold text-xs" title="Edit Kategori">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 inline">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </button>
                                            <form id="delete-category-{{ $cat->id }}" action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmMasterDelete('delete-category-{{ $cat->id }}', 'Kategori', '{{ $cat->name }}')" 
                                                    class="text-rose-500 hover:text-rose-700 font-bold text-xs" title="Hapus Kategori">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 inline">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-8 text-center italic text-slate-400">Belum ada data kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- panel kanan: Wilayah / Lokasi -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
                <div class="pb-4 border-b border-slate-100 dark:border-zinc-800">
                    <h3 class="heading-font font-bold text-lg text-slate-800 dark:text-slate-200">Wilayah / Lokasi</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tambah dan hapus wilayah administratif properti</p>
                </div>

                <!-- Form Tambah Lokasi -->
                <form action="{{ route('admin.locations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Nama Wilayah</label>
                            <input type="text" name="name" required placeholder="Contoh: Depok, Sumedang"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Tipe Wilayah</label>
                            <select name="type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs outline-none">
                                <option value="provinsi">Provinsi</option>
                                <option value="kota">Kota / Kabupaten</option>
                                <option value="kecamatan">Kecamatan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Wilayah Induk (Parent) - Opsional</label>
                        <select name="parent_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 text-xs outline-none">
                            <option value="">-- Tanpa Induk Wilayah --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }} ({{ ucfirst($loc->type) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="py-2.5 px-6 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-xs shadow-md shadow-indigo-600/10 active:scale-95 transition-all">
                            Tambah Wilayah
                        </button>
                    </div>
                </form>

                <!-- Tabel Lokasi -->
                <div class="overflow-x-auto max-h-[400px]">
                    <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                        <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950 sticky top-0">
                            <tr>
                                <th class="px-4 py-3">Wilayah</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3">Induk</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse($locations as $loc)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200 text-xs">{{ $loc->name }}</td>
                                    <td class="px-4 py-3 text-xs"><span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-zinc-800 text-[10px] capitalize">{{ $loc->type }}</span></td>
                                    <td class="px-4 py-3 text-xs text-slate-400">{{ $loc->parent->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <div class="flex justify-end items-center gap-2">
                                            <button type="button" onclick="editLocation({{ $loc->id }}, '{{ $loc->name }}', '{{ $loc->type }}', {{ $loc->parent_id ?? 'null' }})" 
                                                class="text-indigo-600 hover:text-indigo-800 font-bold text-xs" title="Edit Wilayah">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 inline">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </button>
                                            <form id="delete-location-{{ $loc->id }}" action="{{ route('admin.locations.destroy', $loc->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmMasterDelete('delete-location-{{ $loc->id }}', 'Wilayah', '{{ $loc->name }}')" 
                                                    class="text-rose-500 hover:text-rose-700 font-bold text-xs" title="Hapus Wilayah">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 inline">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center italic text-slate-400">Belum ada data wilayah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Konfirmasi Hapus Data Master -->
    <script>
        function confirmMasterDelete(formId, type, name) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus ${type}: "${name}". Aksi ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.showLoading();
                    document.getElementById(formId).submit();
                }
            });
        }

        async function editCategory(id, currentName) {
            const { value: newName } = await Swal.fire({
                title: 'Ubah Nama Kategori',
                input: 'text',
                inputLabel: 'Nama Kategori',
                inputValue: currentName,
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama kategori tidak boleh kosong!';
                    }
                }
            });

            if (newName && newName !== currentName) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/categories/${id}`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                form.appendChild(method);

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'name';
                nameInput.value = newName;
                form.appendChild(nameInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        async function editLocation(id, currentName, currentType, currentParentId) {
            let parentOptions = '<option value="">(Tidak ada)</option>';
            @foreach($locations as $parent)
                if ({{ $parent->id }} != id) {
                    const selected = {{ $parent->id }} == currentParentId ? 'selected' : '';
                    parentOptions += `<option value="{{ $parent->id }}" ${selected}>{{ $parent->name }} ({{ $parent->type }})</option>`;
                }
            @endforeach

            const { value: formValues } = await Swal.fire({
                title: 'Edit Wilayah',
                html:
                    `<div class="text-left space-y-3">` +
                    `<div><label class="block text-xs font-semibold text-slate-500 mb-1">NAMA WILAYAH</label>` +
                    `<input id="swal-loc-name" class="swal2-input !m-0 !w-full text-sm" value="${currentName}"></div>` +
                    `<div><label class="block text-xs font-semibold text-slate-500 mb-1">TIPE WILAYAH</label>` +
                    `<select id="swal-loc-type" class="swal2-input !m-0 !w-full text-sm">` +
                        `<option value="provinsi" ${currentType === 'provinsi' ? 'selected' : ''}>Provinsi</option>` +
                        `<option value="kota" ${currentType === 'kota' ? 'selected' : ''}>Kota / Kabupaten</option>` +
                        `<option value="kecamatan" ${currentType === 'kecamatan' ? 'selected' : ''}>Kecamatan</option>` +
                    `</select></div>` +
                    `<div><label class="block text-xs font-semibold text-slate-500 mb-1">WILAYAH INDUK (PARENT)</label>` +
                    `<select id="swal-loc-parent" class="swal2-input !m-0 !w-full text-sm">${parentOptions}</select></div>` +
                    `</div>`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const name = document.getElementById('swal-loc-name').value;
                    const type = document.getElementById('swal-loc-type').value;
                    const parent_id = document.getElementById('swal-loc-parent').value;
                    if (!name) {
                        Swal.showValidationMessage('Nama wilayah tidak boleh kosong!');
                        return false;
                    }
                    return { name, type, parent_id };
                }
            });

            if (formValues) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/locations/${id}`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                form.appendChild(method);

                for (const key in formValues) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = formValues[key];
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endif
