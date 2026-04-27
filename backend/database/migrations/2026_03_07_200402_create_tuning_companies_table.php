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
        Schema::create('tuning_companies', function (Blueprint $table) {
            $table->id();
            $table->string("name", 50);
            $table->string("description",255);
            $table->string("website_url");
            $table->string("image_url")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuning_companies');
    }
};
