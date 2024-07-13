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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // ID
            $table->text('content'); // レビュー内容
            $table->unsignedInteger('score'); // スコア（符号無し）
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete(); // 店舗のID（外部キー制約付き）
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 会員のID（外部キー制約付き）
            $table->timestamps(); // 作成日時と更新日時
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
