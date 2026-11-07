<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'purchase_price',
        'selling_price',
        'stock',
        'unit',
        'photo',
        'status',
        'description',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function stockHistories() {
        return $this->hasMany(StockHistory::class);
        return $this->hasMany(TransactionDetail::class);
    }
}