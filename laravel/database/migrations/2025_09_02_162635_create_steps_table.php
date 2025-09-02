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
        Schema::create('steps', function (Blueprint $table) {
            $table->id()->comment('手順ID');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade')->comment('紐づくレシピID');
            $table->unsignedInteger('order')->comment('手順番号');
            $table->text('instruction')->comment('手順の説明');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
