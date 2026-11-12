<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipe_id')->after('user_id')->constrained('recipes')->cascadeOnDelete();
            $table->enum('type', ['like', 'favorite'])->after('recipe_id');
            $table->unique(['user_id', 'recipe_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'recipe_id', 'type']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['recipe_id']);
            $table->dropColumn(['user_id', 'recipe_id', 'type']);
        });
    }
};
