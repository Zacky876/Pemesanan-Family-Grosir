<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Transaction extends Model {
    //
    Protected $table = 'transactions';

    protected $fillable = [
        'transaction_code',
        'user_id',
        'transaction_date',
        'total',
        'payment',
        'change_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'paid_at',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'total' => 'decimal:2',
        'payment' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany {
        return $this->hasMany(
            TransactionDetail::class,
            'transaction_id'
        );
    }
}
