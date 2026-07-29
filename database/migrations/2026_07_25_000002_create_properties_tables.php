<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // cannot delete user with active properties
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('location_id')->constrained('locations')->onDelete('restrict');
            $table->string('title', 200);
            $table->text('description');
            $table->string('listing_type'); // jual, sewa_bulanan, sewa_tahunan
            $table->decimal('price', 15, 2);
            $table->decimal('land_area', 10, 2);
            $table->decimal('building_area', 10, 2);
            $table->smallInteger('bedrooms')->nullable();
            $table->smallInteger('bathrooms')->nullable();
            $table->string('certificate_type', 50)->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('status')->default('draft'); // draft, pending, active, sold, rented, rejected, inactive
            $table->boolean('is_promoted')->default(false);
            $table->timestamps();
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('property_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('property_features');
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
    }
};
