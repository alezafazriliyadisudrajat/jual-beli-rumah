<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Category;
use App\Models\Location;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    # Menampilkan daftar properti aktif (Pencarian & Filter).
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'q', 'category_id', 'location_id', 'listing_type',
                'min_price', 'max_price', 'bedrooms', 'bathrooms', 'sort', 'limit'
            ]);

            $properties = $this->propertyService->search($filters);
            $categories = Category::all();
            $locations = Location::all();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Daftar properti berhasil diambil',
                    'data' => $properties
                ]);
            }

            return view('welcome', compact('properties', 'categories', 'locations', 'filters'));
        } catch (\Exception $e) {
            Log::error("Error in PropertyController@index: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
            }
            return back()->with('error', 'Gagal memuat daftar properti.');
        }
    }

    # Menampilkan detail dari properti tertentu.
    public function show(Request $request, $id)
    {
        try {
            $property = Property::with(['user', 'category', 'location', 'images', 'features', 'reviews.user'])
                ->findOrFail($id);

            # Izinkan melihat hanya jika aktif atau milik pengguna saat ini atau jika pengguna adalah admin
            if ($property->status !== 'active') {
                $user = Auth::user();
                if (!$user || ($user->id !== $property->user_id && !in_array($user->role_id, [1, 2]))) {
                    abort(404);
                }
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Detail properti berhasil diambil',
                    'data' => $property
                ]);
            }

            return view('properties.show', compact('property'));
        } catch (\Exception $e) {
            Log::error("Error in PropertyController@show: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Properti tidak ditemukan.'], 404);
            }
            abort(404);
        }
    }

    # Menyimpan listing properti baru.
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || !in_array($user->role_id, [3, 4])) {
                return $request->wantsJson()
                    ? response()->json(['success' => false, 'message' => 'Anda tidak diizinkan membuat listing.'], 403)
                    : back()->with('error', 'Hanya penjual atau agen yang dapat membuat listing.');
            }

            if (!$user->is_verified) {
                return back()->with('error', 'Akun Anda belum terverifikasi oleh Admin. Silakan upload KTP Anda di profil.');
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:200',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'location_id' => 'required|exists:locations,id',
                'listing_type' => 'required|in:jual,sewa_bulanan,sewa_tahunan',
                'price' => 'required|numeric|min:0',
                'land_area' => 'required|numeric|min:0',
                'building_area' => 'required|numeric|min:0',
                'bedrooms' => 'nullable|integer|min:0',
                'bathrooms' => 'nullable|integer|min:0',
                'certificate_type' => 'nullable|string|max:50',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
                'features' => 'nullable|array',
                'condition' => 'nullable|string|max:50',
                'facing' => 'nullable|string|max:50',
                'floors_count' => 'nullable|numeric|min:0',
                'floor_location' => 'nullable|string|max:100',
                'interior_type' => 'nullable|string|max:100',
                'maid_bedrooms' => 'nullable|integer|min:0',
                'garages_count' => 'nullable|integer|min:0',
                'carports_count' => 'nullable|integer|min:0',
                'telephone_lines' => 'nullable|integer|min:0',
                'electricity' => 'nullable|integer|min:0',
                'has_pam_water' => 'nullable|boolean',
                'has_ground_water' => 'nullable|boolean',
                'road_access' => 'nullable|string|max:150',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            # Menangani unggahan foto properti
            $imagesData = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('properties', 'public');
                    $imagesData[] = '/storage/' . $path;
                }
            }

            $data = $request->except(['images']);
            $data['images'] = $imagesData;
            $data['has_pam_water'] = $request->has('has_pam_water');
            $data['has_ground_water'] = $request->has('has_ground_water');

            $property = $this->propertyService->create($data, $user->id);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Listing properti berhasil diajukan dan menunggu moderasi.',
                    'data' => $property
                ], 21);
            }

            return redirect()->route('dashboard', ['tab' => 'my-properties'])->with('success', 'Listing properti berhasil diajukan dan sedang menunggu moderasi Admin.');
        } catch (\Exception $e) {
            Log::error("Error in PropertyController@store: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan sistem saat membuat listing.')->withInput();
        }
    }

    # Menyimpan atau menghapus properti dari daftar favorit.
    public function toggleFavorite(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
            }

            $isFavorite = $this->propertyService->toggleFavorite($id, $user->id);

            return response()->json([
                'success' => true,
                'message' => $isFavorite ? 'Ditambahkan ke favorit' : 'Dihapus dari favorit',
                'is_favorite' => $isFavorite
            ]);
        } catch (\Exception $e) {
            Log::error("Error in PropertyController@toggleFavorite: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }

    # Memperbarui listing properti.
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $property = Property::findOrFail($id);

            if (!$user || ($user->id !== $property->user_id && !in_array($user->role_id, [1, 2]))) {
                return $request->wantsJson()
                    ? response()->json(['success' => false, 'message' => 'Anda tidak diizinkan mengubah listing ini.'], 403)
                    : back()->with('error', 'Anda tidak diizinkan mengubah listing ini.');
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:200',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'location_id' => 'required|exists:locations,id',
                'listing_type' => 'required|in:jual,sewa_bulanan,sewa_tahunan',
                'price' => 'required|numeric|min:0',
                'land_area' => 'required|numeric|min:0',
                'building_area' => 'required|numeric|min:0',
                'bedrooms' => 'nullable|integer|min:0',
                'bathrooms' => 'nullable|integer|min:0',
                'certificate_type' => 'nullable|string|max:50',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
                'features' => 'nullable|array',
                'condition' => 'nullable|string|max:50',
                'facing' => 'nullable|string|max:50',
                'floors_count' => 'nullable|numeric|min:0',
                'floor_location' => 'nullable|string|max:100',
                'interior_type' => 'nullable|string|max:100',
                'maid_bedrooms' => 'nullable|integer|min:0',
                'garages_count' => 'nullable|integer|min:0',
                'carports_count' => 'nullable|integer|min:0',
                'telephone_lines' => 'nullable|integer|min:0',
                'electricity' => 'nullable|integer|min:0',
                'has_pam_water' => 'nullable|boolean',
                'has_ground_water' => 'nullable|boolean',
                'road_access' => 'nullable|string|max:150',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            # Menangani unggahan foto properti baru jika ada
            $imagesData = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('properties', 'public');
                    $imagesData[] = '/storage/' . $path;
                }
            }

            $data = $request->except(['images']);
            if (!empty($imagesData)) {
                $data['images'] = $imagesData;
            }
            $data['has_pam_water'] = $request->has('has_pam_water');
            $data['has_ground_water'] = $request->has('has_ground_water');

            $this->propertyService->update($property, $data, $user->id);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Listing properti berhasil diperbarui.',
                    'data' => $property
                ]);
            }

            return redirect()->route('dashboard', ['tab' => 'my-properties'])->with('success', 'Listing properti berhasil diperbarui dan sedang menunggu moderasi ulang.');
        } catch (\Exception $e) {
            Log::error("Error in PropertyController@update: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui properti.')->withInput();
        }
    }

    # Menghapus listing properti.
    public function destroy(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $property = Property::findOrFail($id);

            if (!$user || ($user->id !== $property->user_id && !in_array($user->role_id, [1, 2]))) {
                return $request->wantsJson()
                    ? response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak akses.'], 403)
                    : back()->with('error', 'Anda tidak memiliki hak akses untuk menghapus properti ini.');
            }

            $this->propertyService->delete($property, $user->id);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Listing berhasil dihapus.']);
            }

            return back()->with('success', 'Listing properti berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error in PropertyController@destroy: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }
}
