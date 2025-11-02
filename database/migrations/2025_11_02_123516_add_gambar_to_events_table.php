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
        Schema::table('events', function (Blueprint $table) {
            // Tambahkan kolom 'gambar' setelah kolom 'deskripsi'
            // Dibuat nullable() agar event lama yang tidak punya gambar tidak error
            $table->string('gambar')->nullable()->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Hapus kolom 'gambar' jika migrasi di-rollback
            $table->dropColumn('gambar');
        });
    }
};
