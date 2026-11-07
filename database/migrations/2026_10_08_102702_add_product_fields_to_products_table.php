<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('purchase_price', 12, 2)
                ->default(0)
                ->after('name');

            $table->decimal('selling_price', 12, 2)
                ->default(0)
                ->after('purchase_price');

            $table->string('photo')->nullable()->after('unit');

            $table->enum('status', ['active', 'inactive'])
                ->default('active')
                ->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'purchase_price',
                'selling_price',
                'photo',
                'status',
            ]);
        });
    }
};