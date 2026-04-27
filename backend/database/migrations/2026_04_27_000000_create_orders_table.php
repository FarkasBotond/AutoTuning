<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name', 100);
            $table->string('email', 150);
            $table->string('phone', 30);
            $table->enum('delivery_method', ['capital_store', 'home_delivery']);
            $table->string('country', 80);
            $table->string('city', 80)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('street_name', 120)->nullable();
            $table->string('house_number', 30)->nullable();
            $table->text('note')->nullable();
            $table->enum('payment_method', ['mobile_pay', 'cash_on_delivery']);
            $table->integer('payment_fee')->default(0);
            $table->integer('products_total');
            $table->integer('total_price');
            $table->string('status', 30)->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
