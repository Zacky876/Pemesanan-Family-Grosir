<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'purchase_price')) {
                $table->decimal('purchase_price', 12, 2)
                    ->default(0)
                    ->after('name');
            }

            if (!Schema::hasColumn('products', 'selling_price')) {
                $table->decimal('selling_price', 12, 2)
                    ->default(0)
                    ->after('purchase_price');
            }

            if (!Schema::hasColumn('products', 'photo')) {
                $table->string('photo')
                    ->nullable()
                    ->after('unit');
            }

            if (!Schema::hasColumn('products', 'status')) {
                $table->enum('status', ['active', 'inactive'])
                    ->default('active')
                    ->after('photo');
            }

            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 12, 2)
                    ->default(0)
                    ->after('name');
            }

            $columns = [];

            foreach ([
                'purchase_price',
                'selling_price',
                'photo',
                'status',
            ] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};