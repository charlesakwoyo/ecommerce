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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('currency');
            $table->string('mpesa_checkout_request_id')->nullable()->unique()->after('stripe_payment_intent_id');
            $table->string('mpesa_merchant_request_id')->nullable()->after('mpesa_checkout_request_id');
            $table->string('mpesa_receipt_number')->nullable()->after('mpesa_merchant_request_id');
            $table->string('mpesa_phone')->nullable()->after('mpesa_receipt_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'mpesa_checkout_request_id',
                'mpesa_merchant_request_id',
                'mpesa_receipt_number',
                'mpesa_phone',
            ]);
        });
    }
};
