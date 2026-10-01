<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Tentukan nama tabel secara dinamis berdasarkan tabel yang tersedia
     */
    public function getTable()
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}