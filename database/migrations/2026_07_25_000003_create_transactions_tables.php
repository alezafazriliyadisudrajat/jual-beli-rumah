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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->string('file_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('restrict');
            $table->foreignId('tenant_id')->constrained('users')->onDelete('restrict');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('duration_type'); // bulanan, tahunan
            $table->decimal('total_price', 15, 2);
            $table->decimal('deposit', 15, 2)->default(0);
            $table->string('status')->default('menunggu'); // menunggu, disetujui, aktif, selesai, dibatalkan
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('restrict');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('restrict');
            $table->decimal('agreed_price', 15, 2);
            $table->decimal('booking_fee', 15, 2)->default(0);
            $table->string('status')->default('negosiasi'); // negosiasi, booking, menunggu_pelunasan, lunas, batal
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id');
            $table->decimal('amount', 15, 2);
            $table->string('method'); // Virtual Account, E-Wallet, Kartu Kredit
            $table->string('gateway_reference')->nullable();
            $table->string('status')->default('pending'); // pending, success, failed, expired
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('contracts');
    }
};
