<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'cost_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'cost_price' => 'decimal:2',
        ];
    }

    /**
     * Tentukan nama tabel secara dinamis berdasarkan tabel yang tersedia
     */
    public function getTable(): string
    {
        if (Schema::hasTable('transaction_details')) {
            return 'transaction_details';
        }

        if (Schema::hasTable('transaction_items')) {
            return 'transaction_items';
        }

        if (Schema::hasTable('order_details')) {
            return 'order_details';
        }

        return parent::getTable();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
