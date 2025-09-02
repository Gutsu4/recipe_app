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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id()->comment('レシピID');
            $table->string('name')->comment('レシピ名');
            $table->text('description')->nullable()->comment('レシピの説明文');
            $table->unsignedInteger('servings')->default(1)->comment('何人分か');
            $table->unsignedInteger('cooking_time')->comment('調理時間（分）');
            $table->unsignedInteger('calories')->default(0)->comment('カロリー(kcal)');
            $table->float('protein')->default(0)->comment('タンパク質量(g)');
            $table->float('fat')->default(0)->comment('脂質量(g)');
            $table->float('carbs')->default(0)->comment('炭水化物量(g)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
