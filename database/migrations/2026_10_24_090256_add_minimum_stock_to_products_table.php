<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'minimum_stock')) {
                $table->unsignedInteger('minimum_stock')
                    ->default(5);
            }
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'minimum_stock')) {
                $table->dropColumn('minimum_stock');
            }
        });
    }
};
