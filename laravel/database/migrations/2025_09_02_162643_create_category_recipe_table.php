<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_recipe', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade')->comment('レシピID');
            $table->foreignId('category_id')->constrained()->onDelete('cascade')->comment('カテゴリID');
            $table->timestamps();

            $table->unique(['recipe_id', 'category_id'], 'recipe_category_unique');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_recipe');
    }
};
