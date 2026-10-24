
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_histories', function (Blueprint $table) {
            $table->enum('type', ['in', 'out', 'adjustment'])->change();
        });
    }

    public function down(): void
    {
        // Jangan mengembalikan enum ke in/out jika masih ada
        // riwayat dengan type adjustment.
    }
};
