<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $recipeId = DB::table('recipes')
            ->where('nama_resep', 'Waterlemon')
            ->value('id');

        if (!$recipeId) {
            return;
        }

        DB::table('recipes')
            ->where('id', $recipeId)
            ->update([
                'deskripsi' => 'Minuman lemon segar dengan rasa asam manis yang cocok disajikan dingin.',
                'waktu_masak' => 10,
                'porsi' => 2,
                'updated_at' => now(),
            ]);

        DB::table('bahans')->where('recipe_id', $recipeId)->delete();
        DB::table('bahans')->insert([
            [
                'recipe_id' => $recipeId,
                'nama_bahan' => '2 buah lemon',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $recipeId,
                'nama_bahan' => '500 ml air matang dingin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $recipeId,
                'nama_bahan' => '3 sendok makan gula pasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $recipeId,
                'nama_bahan' => 'Es batu secukupnya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('steps')->where('recipe_id', $recipeId)->delete();
        DB::table('steps')->insert([
            [
                'recipe_id' => $recipeId,
                'urutan' => 1,
                'deskripsi' => 'Cuci lemon, lalu peras airnya dan buang bijinya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $recipeId,
                'urutan' => 2,
                'deskripsi' => 'Larutkan gula dengan sedikit air hangat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $recipeId,
                'urutan' => 3,
                'deskripsi' => 'Campurkan air lemon, larutan gula, dan air matang dingin.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'recipe_id' => $recipeId,
                'urutan' => 4,
                'deskripsi' => 'Tambahkan es batu, aduk rata, lalu sajikan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        $recipeId = DB::table('recipes')
            ->where('nama_resep', 'Waterlemon')
            ->value('id');

        if (!$recipeId) {
            return;
        }

        DB::table('steps')->where('recipe_id', $recipeId)->delete();
        DB::table('bahans')->where('recipe_id', $recipeId)->delete();
    }
};
