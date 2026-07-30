<!-- 5. Tab: Properti Saya (Owner/Agent) -->
@if(in_array(Auth::user()->role_id, [3, 4]))
    <div id="my-properties-view" x-show="tab === 'my-properties'" class="bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="heading-font font-bold text-2xl text-slate-900 dark:text-white">Properti yang Saya Pasarkan</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola listing properti Anda di sini</p>
            </div>
            
            <!-- Open Create Form Button (Alpine) -->
            <button @click="resetPropertyForm(); showCreateForm = true" class="py-2.5 px-5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-xs shadow-md shadow-indigo-600/10 active:scale-95 transition-all">
                Tambah Listing Baru
            </button>
        </div>

        <!-- Add Listing Inline Form (Alpine state toggled) -->
        <div x-show="showCreateForm" x-transition class="p-6 border border-slate-200 dark:border-zinc-800 rounded-2xl bg-slate-50 dark:bg-zinc-950 space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-200 dark:border-zinc-800">
                <h3 class="heading-font font-bold text-base text-slate-800 dark:text-slate-200" x-text="isEditMode ? 'Edit Listing Properti' : 'Buat Listing Properti Baru'">Buat Listing Properti Baru</h3>
                <button @click="showCreateForm = false" class="text-slate-400 hover:text-slate-600">Batal</button>
            </div>

            <form :action="isEditMode ? '/properties/' + propertyForm.id : '{{ route('properties.store') }}'" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" :value="isEditMode ? 'PUT' : 'POST'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Judul Listing</label>
                        <input type="text" name="title" required x-model="propertyForm.title" placeholder="Contoh: Rumah Minimalis 2 Lantai"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Kategori Properti</label>
                        <select name="category_id" required x-model="propertyForm.category_id" class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Kategori Transaksi</label>
                        <select name="listing_type" required x-model="propertyForm.listing_type" class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                            <option value="jual">Beli / Jual</option>
                            <option value="sewa_bulanan">Sewa Bulanan</option>
                            <option value="sewa_tahunan">Sewa Tahunan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Harga Properti (Rp)</label>
                        <input type="hidden" name="price" id="raw_price" required min="0" x-model="propertyForm.price">
                        <input type="text" id="formatted_price" required x-model="propertyForm.formatted_price" placeholder="Contoh: 1.500.000.000"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none focus:ring-2 focus:ring-indigo-500/20"
                            oninput="formatRupiah(this, 'raw_price')">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Luas Bangunan (m²)</label>
                        <input type="number" name="building_area" required min="0" x-model="propertyForm.building_area" placeholder="120"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Luas Tanah (m²)</label>
                        <input type="number" name="land_area" required min="0" x-model="propertyForm.land_area" placeholder="150"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Lokasi Wilayah</label>
                        <select name="location_id" required x-model="propertyForm.location_id" class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Sertifikat</label>
                        <input type="text" name="certificate_type" x-model="propertyForm.certificate_type" placeholder="Contoh: SHM / HGB"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Kondisi Bangunan</label>
                        <select name="condition" x-model="propertyForm.condition" class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                            <option value="baru">Baru</option>
                            <option value="bekas">Bekas / Bagus</option>
                            <option value="butuh renovasi">Butuh Renovasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Menghadap (Arah Hadap)</label>
                        <select name="facing" x-model="propertyForm.facing" class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                            <option value="timur">Timur</option>
                            <option value="barat">Barat</option>
                            <option value="selatan">Selatan</option>
                            <option value="utara">Utara</option>
                            <option value="timur laut">Timur Laut</option>
                            <option value="tenggara">Tenggara</option>
                            <option value="barat daya">Barat Daya</option>
                            <option value="barat laut">Barat Laut</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Jumlah Lantai</label>
                        <input type="number" name="floors_count" min="0" step="0.5" x-model="propertyForm.floors_count" placeholder="Contoh: 1 atau 2"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Floor Location (Lokasi Lantai)</label>
                        <input type="text" name="floor_location" x-model="propertyForm.floor_location" placeholder="Contoh: Lantai Dasar, Lantai 3"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Interior</label>
                        <select name="interior_type" x-model="propertyForm.interior_type" class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                            <option value="tak berperabot">Tak Berperabot</option>
                            <option value="semi-perabot">Semi Perabot</option>
                            <option value="lengkap">Lengkap (Fully Furnished)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Kamar Pembantu</label>
                        <input type="number" name="maid_bedrooms" min="0" x-model="propertyForm.maid_bedrooms" placeholder="0"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Garasi</label>
                        <input type="number" name="garages_count" min="0" x-model="propertyForm.garages_count" placeholder="0"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Carport</label>
                        <input type="number" name="carports_count" min="0" x-model="propertyForm.carports_count" placeholder="0"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Saluran Telepon</label>
                        <input type="number" name="telephone_lines" min="0" x-model="propertyForm.telephone_lines" placeholder="0"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Daya Listrik (VA)</label>
                        <input type="number" name="electricity" min="0" x-model="propertyForm.electricity" placeholder="Contoh: 1300"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase">Akses Jalan / Jalur Mobil</label>
                        <input type="text" name="road_access" x-model="propertyForm.road_access" placeholder="Contoh: Jalur Mobil / 2 Mobil"
                            class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none">
                    </div>

                    <div class="flex items-center gap-6 mt-4 md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">
                            <input type="checkbox" name="has_pam_water" value="1" x-model="propertyForm.has_pam_water" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Air PAM
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">
                            <input type="checkbox" name="has_ground_water" value="1" x-model="propertyForm.has_ground_water" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Air Tanah
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase">Deskripsi Properti</label>
                    <textarea name="description" required rows="3" x-model="propertyForm.description" placeholder="Jelaskan spesifikasi detail, kondisi, dan keunggulan properti..."
                        class="w-full px-3 py-2 mt-1 rounded-xl border border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs outline-none focus:ring-2 focus:ring-indigo-500/20"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase">Upload Foto Properti</label>
                    <input type="file" name="images[]" multiple :required="!isEditMode" accept="image/*" class="text-xs mt-1 block">
                </div>

                <button type="submit" class="py-2.5 px-5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-xs active:scale-95 transition-all shadow-sm">
                    <span x-text="isEditMode ? 'Simpan Perubahan' : 'Simpan & Ajukan Listing'">Simpan & Ajukan Listing</span>
                </button>
            </form>
        </div>

        <!-- Properties Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="text-xs uppercase text-slate-400 bg-slate-50 dark:bg-zinc-950">
                    <tr>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($myProperties as $prop)
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">
                                <a href="{{ route('properties.show', $prop->id) }}" class="hover:underline">
                                    {{ $prop->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $prop->category->name }} ({{ $prop->listing_type }})
                            </td>
                            <td class="px-4 py-3 text-xs font-bold">
                                Rp {{ number_format($prop->price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded font-bold uppercase
                                    {{ $prop->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                    {{ $prop->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs flex gap-2">
                                <button type="button" @click="editProperty({{ json_encode($prop) }})" class="p-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Edit Properti">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                                <form action="{{ route('properties.destroy', $prop->id) }}" method="POST" id="delete-property-form-{{ $prop->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteProperty({{ $prop->id }})" class="p-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg transition-all flex items-center justify-center shadow" title="Hapus Properti">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center italic text-slate-400">Belum ada properti dipasarkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
