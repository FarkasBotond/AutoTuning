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
        Schema::create('company_car_model', function (Blueprint $table) {
            $table->foreignId("company_id")->constrained("tuning_companies", "id");
            $table->foreignId("model_id")->constrained("car_models", "id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_car_model');
    }
};
