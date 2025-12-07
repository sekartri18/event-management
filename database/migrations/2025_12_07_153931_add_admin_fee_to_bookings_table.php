<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::table('bookings', function (Blueprint $table) {
        // Menambahkan kolom admin_fee setelah total_amount
        $table->decimal('admin_fee', 15, 2)->default(0)->after('total_amount');
        });
    }

    public function down()
    {
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn('admin_fee');
        });
    }
};
