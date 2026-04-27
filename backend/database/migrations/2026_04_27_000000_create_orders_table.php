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
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('email', 150);
            $table->string('phone', 20);
            $table->enum('delivery_method', ['capital_store', 'home_delivery']);
            $table->string('country', 80);
            $table->string('city', 80);
            $table->string('postal_code', 20);
            $table->string('street_name', 120);
            $table->string('house_number', 30);
            $table->string('billing_country', 80);
            $table->string('billing_city', 80);
            $table->string('billing_postal_code', 20);
            $table->string('billing_street_name', 120);
            $table->string('billing_house_number', 30);
            $table->string('note', 150)->nullable();
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
