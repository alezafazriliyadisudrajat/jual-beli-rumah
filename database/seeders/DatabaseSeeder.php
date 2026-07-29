<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Super Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Agen Properti', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Penjual/Pemilik', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Pembeli/Penyewa', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Seed Categories
        \Illuminate\Support\Facades\DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Rumah', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Ruko', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Apartemen', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Tanah', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Gudang', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed Locations (cuma 2 data sesuai permintaan user)
        \Illuminate\Support\Facades\DB::table('locations')->insert([
            ['id' => 1, 'name' => 'DKI Jakarta', 'type' => 'kota', 'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Bandung', 'type' => 'kota', 'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Seed Sample Users
        $password = \Illuminate\Support\Facades\Hash::make('password');
        \Illuminate\Support\Facades\DB::table('users')->insert([
            [
                'name' => 'Admin Properti',
                'email' => 'admin@properti.com',
                'password' => $password,
                'role_id' => 2,
                'phone' => '081234567890',
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pemilik Ruko',
                'email' => 'owner@properti.com',
                'password' => $password,
                'role_id' => 4,
                'phone' => '081234567891',
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Agen Properti',
                'email' => 'agent@properti.com',
                'password' => $password,
                'role_id' => 3,
                'phone' => '081234567892',
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pembeli Properti',
                'email' => 'buyer@properti.com',
                'password' => $password,
                'role_id' => 5,
                'phone' => '081234567893',
                'is_verified' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 5. Seed Properties
        \Illuminate\Support\Facades\DB::table('properties')->insert([
            [
                'id' => 1,
                'user_id' => 2, // Pemilik Ruko (owner@properti.com)
                'category_id' => 1, // Rumah
                'location_id' => 1, // DKI Jakarta
                'title' => 'Rumah Modern Minimalis Kemang',
                'description' => 'Rumah modern minimalis di kawasan premium Kemang. Memiliki pencahayaan alami yang sangat baik, taman belakang yang luas, serta lingkungan yang aman dengan sistem keamanan satu gerbang (one gate system). Dekat dengan berbagai mal dan sekolah internasional.',
                'listing_type' => 'jual',
                'price' => 2500000000.00,
                'land_area' => 180.00,
                'building_area' => 150.00,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'certificate_type' => 'SHM',
                'latitude' => -6.2735,
                'longitude' => 106.8205,
                'status' => 'active',
                'is_promoted' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'user_id' => 2, // Pemilik Ruko (owner@properti.com)
                'category_id' => 2, // Ruko
                'location_id' => 2, // Bandung
                'title' => 'Ruko Premium Dago 3 Lantai',
                'description' => 'Ruko komersial sangat strategis di jalan utama Dago, Bandung. Sangat cocok untuk kantor, kafe, butik, atau tempat usaha kuliner. Lokasi ramai lalu lintas, parkir luas, dan bebas banjir.',
                'listing_type' => 'sewa_tahunan',
                'price' => 85000000.00,
                'land_area' => 120.00,
                'building_area' => 300.00,
                'bedrooms' => 0,
                'bathrooms' => 3,
                'certificate_type' => 'HGB',
                'latitude' => -6.8920,
                'longitude' => 107.6168,
                'status' => 'active',
                'is_promoted' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'user_id' => 3, // Agen Properti (agent@properti.com)
                'category_id' => 3, // Apartemen
                'location_id' => 1, // DKI Jakarta
                'title' => 'Apartemen Sudirman Suites Cozy 2BR',
                'description' => 'Apartemen modern 2 kamar tidur di jantung kota Jakarta (Sudirman). Dilengkapi dengan fasilitas lengkap kelas atas seperti infinity pool, fitness center, sauna, dan akses langsung ke stasiun MRT.',
                'listing_type' => 'sewa_bulanan',
                'price' => 8500000.00,
                'land_area' => 60.00,
                'building_area' => 60.00,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'certificate_type' => 'Strata Title',
                'latitude' => -6.2189,
                'longitude' => 106.8180,
                'status' => 'active',
                'is_promoted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'user_id' => 3, // Agen Properti (agent@properti.com)
                'category_id' => 5, // Gudang
                'location_id' => 1, // DKI Jakarta
                'title' => 'Gudang Logistik Modern Cakung',
                'description' => 'Gudang logistik baru dengan akses jalan tronton/kontainer 40 feet. Keamanan 24 jam dengan CCTV, loading dock lebar, dan struktur bangunan yang sangat kokoh.',
                'listing_type' => 'jual',
                'price' => 7500000000.00,
                'land_area' => 1200.00,
                'building_area' => 950.00,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'certificate_type' => 'SHM',
                'latitude' => -6.1783,
                'longitude' => 106.9429,
                'status' => 'pending', // Menunggu moderasi admin
                'is_promoted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 6. Seed Property Images
        \Illuminate\Support\Facades\DB::table('property_images')->insert([
            ['id' => 1, 'property_id' => 1, 'image_path' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80', 'is_primary' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'property_id' => 1, 'image_path' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80', 'is_primary' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'property_id' => 2, 'image_path' => 'https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=800&q=80', 'is_primary' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'property_id' => 3, 'image_path' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80', 'is_primary' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'property_id' => 4, 'image_path' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80', 'is_primary' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 7. Seed Property Features
        \Illuminate\Support\Facades\DB::table('property_features')->insert([
            ['property_id' => 1, 'name' => 'Kolam Renang Pribadi', 'created_at' => now(), 'updated_at' => now()],
            ['property_id' => 1, 'name' => 'Garasi 2 Mobil', 'created_at' => now(), 'updated_at' => now()],
            ['property_id' => 1, 'name' => 'Taman Depan & Belakang', 'created_at' => now(), 'updated_at' => now()],
            ['property_id' => 2, 'name' => 'Keamanan 24 Jam', 'created_at' => now(), 'updated_at' => now()],
            ['property_id' => 2, 'name' => 'Parkir Luas 6 Mobil', 'created_at' => now(), 'updated_at' => now()],
            ['property_id' => 3, 'name' => 'Full AC & Water Heater', 'created_at' => now(), 'updated_at' => now()],
            ['property_id' => 3, 'name' => 'Akses Gym & Kolam Renang', 'created_at' => now(), 'updated_at' => now()],
            ['property_id' => 4, 'name' => 'Akses Tronton & Kontainer', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 8. Seed Chat Conversations & Messages
        \Illuminate\Support\Facades\DB::table('chat_conversations')->insert([
            [
                'id' => 1,
                'property_id' => 1, // Rumah Modern
                'participant_one' => 4, // Pembeli (buyer@properti.com)
                'participant_two' => 2, // Pemilik (owner@properti.com)
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'property_id' => 3, // Apartemen
                'participant_one' => 4, // Pembeli (buyer@properti.com)
                'participant_two' => 3, // Agen (agent@properti.com)
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        \Illuminate\Support\Facades\DB::table('chat_messages')->insert([
            ['conversation_id' => 1, 'sender_id' => 4, 'message' => 'Halo Pak, apakah Rumah Modern Minimalis di Kemang ini masih tersedia?', 'read_at' => now(), 'created_at' => now()->subHours(2), 'updated_at' => now()->subHours(2)],
            ['conversation_id' => 1, 'sender_id' => 2, 'message' => 'Halo! Iya betul, rumah masih tersedia dan siap huni. Apakah tertarik untuk survei lokasi?', 'read_at' => now(), 'created_at' => now()->subHours(1), 'updated_at' => now()->subHours(1)],
            ['conversation_id' => 1, 'sender_id' => 4, 'message' => 'Iya sangat tertarik, apakah boleh saya tawar sedikit harganya?', 'read_at' => null, 'created_at' => now()->subMinutes(10), 'updated_at' => now()->subMinutes(10)],
            
            ['conversation_id' => 2, 'sender_id' => 4, 'message' => 'Halo Agen, untuk Apartemen Sudirman ini apakah sewanya minimal harus 1 tahun?', 'read_at' => now(), 'created_at' => now()->subDays(1), 'updated_at' => now()->subDays(1)],
            ['conversation_id' => 2, 'sender_id' => 3, 'message' => 'Halo! Tidak perlu, apartemen ini bisa disewa bulanan dengan deposit di awal.', 'read_at' => now(), 'created_at' => now()->subHours(12), 'updated_at' => now()->subHours(12)],
        ]);

        // 9. Seed Transactions (Jual-Beli)
        \Illuminate\Support\Facades\DB::table('transactions')->insert([
            [
                'id' => 1,
                'property_id' => 1, // Rumah Modern
                'buyer_id' => 4, // buyer@properti.com
                'agreed_price' => 2450000000.00,
                'booking_fee' => 24500000.00,
                'status' => 'negosiasi',
                'contract_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 10. Seed Bookings (Sewa-Menyewa)
        \Illuminate\Support\Facades\DB::table('bookings')->insert([
            [
                'id' => 1,
                'property_id' => 3, // Apartemen
                'tenant_id' => 4, // buyer@properti.com
                'start_date' => now()->addDays(5)->format('Y-m-d'),
                'end_date' => now()->addDays(35)->format('Y-m-d'),
                'duration_type' => 'bulanan',
                'total_price' => 8500000.00,
                'deposit' => 4250000.00,
                'status' => 'menunggu',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // 11. Seed Reviews
        \Illuminate\Support\Facades\DB::table('reviews')->insert([
            [
                'property_id' => 2,
                'user_id' => 4,
                'rating' => 5,
                'comment' => 'Ruko Dago ini sangat strategis! Fasilitas parkirnya memadai dan lingkungannya sangat kondusif untuk bisnis.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
