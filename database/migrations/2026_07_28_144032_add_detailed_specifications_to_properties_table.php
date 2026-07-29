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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('condition', 50)->nullable();
            $table->string('facing', 50)->nullable();
            $table->double('floors_count')->nullable();
            $table->string('floor_location', 100)->nullable();
            $table->string('interior_type', 100)->nullable();
            $table->integer('maid_bedrooms')->default(0);
            $table->integer('garages_count')->default(0);
            $table->integer('carports_count')->default(0);
            $table->integer('telephone_lines')->default(0);
            $table->integer('electricity')->nullable(); // VA
            $table->boolean('has_pam_water')->default(false);
            $table->boolean('has_ground_water')->default(false);
            $table->string('road_access', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'condition',
                'facing',
                'floors_count',
                'floor_location',
                'interior_type',
                'maid_bedrooms',
                'garages_count',
                'carports_count',
                'telephone_lines',
                'electricity',
                'has_pam_water',
                'has_ground_water',
                'road_access'
            ]);
        });
    }
};
