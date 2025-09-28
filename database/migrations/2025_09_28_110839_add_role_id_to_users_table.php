<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom dan foreign key sudah ada di DB, jadi tidak perlu ditambahkan
    }

    public function down(): void
    {
        // Kosong juga, atau drop foreign key jika ingin rollback
    }
};
