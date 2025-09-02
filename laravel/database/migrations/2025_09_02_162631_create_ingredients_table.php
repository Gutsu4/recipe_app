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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id()->comment('材料ID');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade')->comment('紐づくレシピID');
            $table->string('name')->comment('材料名');
            $table->string('amount')->comment('使用量（例：大さじ1、少々、2個）');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
