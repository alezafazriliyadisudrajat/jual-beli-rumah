<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Favorite;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PropertyService
{
    /**
     * Mencari properti dengan filter dan paginasi.
     */
    public function search(array $filters)
    {
        try {
            $query = Property::with(['category', 'location', 'images'])
                ->where('status', 'active');

            if (!empty($filters['q'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['q'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['q'] . '%');
                });
            }

            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            if (!empty($filters['location_id'])) {
                $query->where('location_id', $filters['location_id']);
            }

            if (!empty($filters['listing_type'])) {
                $query->where('listing_type', $filters['listing_type']);
            }

            if (isset($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            }

            if (isset($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }

            if (isset($filters['bedrooms'])) {
                $query->where('bedrooms', $filters['bedrooms']);
            }

            if (isset($filters['bathrooms'])) {
                $query->where('bathrooms', $filters['bathrooms']);
            }

            # Pengurutan
            $sort = $filters['sort'] ?? 'newest';
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('is_promoted', 'desc')->orderBy('created_at', 'desc');
                    break;
            }

            return $query->paginate($filters['limit'] ?? 10);
        } catch (\Exception $e) {
            Log::error("Error in PropertyService@search: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Membuat listing properti baru.
     */
    public function create(array $data, int $userId)
    {
        DB::beginTransaction();
        try {
            $property = Property::create(array_merge($data, [
                'user_id' => $userId,
                'status' => 'pending', # Menunggu moderasi admin
            ]));

            # Tambah fasilitas jika ada
            if (!empty($data['features'])) {
                foreach ($data['features'] as $featureName) {
                    $property->features()->create(['name' => $featureName]);
                }
            }

            # Tambah foto jika ada
            if (!empty($data['images'])) {
                foreach ($data['images'] as $index => $image) {
                    $property->images()->create([
                        'image_path' => $image,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            AuditLogService::log($userId, 'CREATE_PROPERTY', "Membentuk properti ID {$property->id}: {$property->title}");
            DB::commit();
            return $property;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in PropertyService@create: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memperbarui properti yang ada.
     */
    public function update(Property $property, array $data, int $userId)
    {
        DB::beginTransaction();
        try {
            $property->update(array_merge($data, [
                'status' => 'pending', # Reset status to pending to trigger re-moderation
            ]));

            if (isset($data['features'])) {
                $property->features()->delete();
                foreach ($data['features'] as $featureName) {
                    $property->features()->create(['name' => $featureName]);
                }
            }

            # Tambahkan foto baru jika ada
            if (!empty($data['images'])) {
                foreach ($data['images'] as $path) {
                    $property->images()->create(['image_path' => $path]);
                }
            }

            AuditLogService::log($userId, 'UPDATE_PROPERTY', "Memperbarui properti ID {$property->id}");
            DB::commit();
            return $property;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in PropertyService@update: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menghapus listing properti.
     */
    public function delete(Property $property, int $userId)
    {
        DB::beginTransaction();
        try {
            # Periksa transaksi atau sewa aktif
            if ($property->transactions()->whereIn('status', ['booking', 'menunggu_pelunasan'])->exists() ||
                $property->bookings()->whereIn('status', ['aktif', 'disetujui'])->exists()) {
                throw new \Exception("Properti tidak dapat dihapus karena memiliki transaksi atau sewa aktif.");
            }

            # Hapus berkas foto terkait
            foreach ($property->images as $img) {
                if (str_starts_with($img->image_path, 'properties/')) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }

            $property->delete();
            AuditLogService::log($userId, 'DELETE_PROPERTY', "Menghapus properti ID {$property->id}");
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in PropertyService@delete: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Beralih status favorit properti bagi pengguna.
     */
    public function toggleFavorite(int $propertyId, int $userId): bool
    {
        try {
            $fav = Favorite::where('property_id', $propertyId)->where('user_id', $userId)->first();
            if ($fav) {
                $fav->delete();
                return false; # Dihapus dari favorit
            } else {
                Favorite::create([
                    'property_id' => $propertyId,
                    'user_id' => $userId,
                ]);
                return true; # Ditambahkan ke favorit
            }
        } catch (\Exception $e) {
            Log::error("Error in PropertyService@toggleFavorite: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memoderasi properti (Setujui atau Tolak).
     */
    public function moderate(Property $property, string $status, int $adminId)
    {
        try {
            if (!in_array($status, ['active', 'rejected'])) {
                throw new \Exception("Status moderasi tidak valid.");
            }

            $property->update(['status' => $status]);
            AuditLogService::log($adminId, 'MODERATE_PROPERTY', "Moderasi properti ID {$property->id} menjadi status: {$status}");
            return $property;
        } catch (\Exception $e) {
            Log::error("Error in PropertyService@moderate: " . $e->getMessage());
            throw $e;
        }
    }
}
