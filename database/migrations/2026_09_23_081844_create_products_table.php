<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('barcode')->unique();
        $table->string('name');
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->decimal('cost_price', 12, 2); // Harga beli/modal
        $table->decimal('selling_price', 12, 2); // Harga jual
        $table->integer('stock');
        $table->integer('min_stock')->default(5);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
