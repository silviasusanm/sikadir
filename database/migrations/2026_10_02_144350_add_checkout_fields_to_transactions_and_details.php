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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('transaction_number')->nullable()->unique()->after('user_id');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('payment_method', 20)->default('cash');
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('change_due', 15, 2)->default(0);
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique(['transaction_number']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn([
                'transaction_number',
                'subtotal',
                'discount_amount',
                'total_amount',
                'payment_method',
                'amount_paid',
                'change_due',
            ]);
        });
    }
};
