
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->foreignId('recipe_id')
                  ->after('id')
                  ->constrained('recipes')
                  ->cascadeOnDelete();

            $table->integer('urutan')->after('recipe_id');
            $table->text('deskripsi')->after('urutan');
        });
    }

    public function down(): void
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
            $table->dropColumn([
                'recipe_id',
                'urutan',
                'deskripsi'
            ]);
        });
    }
};
