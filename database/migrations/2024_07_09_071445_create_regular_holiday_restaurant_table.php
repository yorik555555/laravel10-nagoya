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
        Schema::create('regular_holiday_restaurant', function (Blueprint $table) {
            $table->id(); // カラム: id
            $table->foreignId('restaurant_id') // カラム: restaurant_id
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('regular_holiday_id') // カラム: regular_holiday_id
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps(); // カラム: created_at と updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regular_holiday_restaurant');
    }
};
