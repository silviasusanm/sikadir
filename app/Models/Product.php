<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini (opsional jika sesuai konvensi).
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Atribut/kolom yang diizinkan untuk diisi secara massal (mass assignment).
     *
     * @var array
     */
    protected $fillable = [
        'barcode',
        'name',
        'image',
        'category_id',
        'cost_price',
        'selling_price',
        'price',
        'stock',
        'min_stock',
    ];

    /**
     * Tipe data bawaan yang harus dikonversi (type casting).
     *
     * @var array
     */
    protected $casts = [
        'cost_price' => 'float',
        'selling_price' => 'float',
        'price' => 'float',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    /**
     * URL publik gambar produk (null jika belum ada gambar).
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
