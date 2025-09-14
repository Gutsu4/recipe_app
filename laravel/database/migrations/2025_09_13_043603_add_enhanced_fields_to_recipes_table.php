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
        Schema::table('recipes', function (Blueprint $table) {
            $table->float('fiber')->default(0)->comment('食物繊維量(g)')->after('carbs');
            $table->float('sodium')->default(0)->comment('ナトリウム量(mg)')->after('fiber');
            $table->float('sugar')->default(0)->comment('糖質量(g)')->after('sodium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['fiber', 'sodium', 'sugar']);
        });
    }
};
