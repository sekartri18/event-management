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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_type_id')->constrained('ticket_types')->onDelete('cascade');
            $table->string('statusCheckIn')->default('pending');
            $table->dateTime('tanggalCheckIn')->nullable();
            $table->string('nama_pemegang_tiket');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('qr_code')->unique();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
