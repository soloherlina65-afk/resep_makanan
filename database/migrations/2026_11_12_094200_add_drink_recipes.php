<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('categories')
            ->where('nama_kategori', 'Minuman')
            ->value('id');

        if (!$categoryId) {
            $categoryId = DB::table('categories')->insertGetId([
                'nama_kategori' => 'Minuman',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $recipes = [
            [
                'nama_resep' => 'Mocalatte Spesial',
                'deskripsi' => 'Minuman kopi cokelat yang lembut dan nikmat untuk menemani waktu santai.',
                'waktu_masak' => 10,
                'porsi' => 1,
                'bahan' => [
                    '1 shot espresso',
                    '200 ml susu cair',
                    '2 sendok makan sirup cokelat',
                    'Es batu secukupnya',
                ],
            ],
            [
                'nama_resep' => 'Waterlemon',
                'deskripsi' => 'Minuman semangka segar dengan rasa manis alami yang cocok diminum saat cuaca panas.',
                'waktu_masak' => 10,
                'porsi' => 2,
                'bahan' => [
                    '500 gram semangka tanpa biji',
                    '1 sendok makan air jeruk nipis',
                    'Es batu secukupnya',
                    'Daun mint untuk hiasan',
                ],
            ],
        ];

        foreach ($recipes as $recipe) {
            if (DB::table('recipes')->where('nama_resep', $recipe['nama_resep'])->exists()) {
                continue;
            }

            $recipeId = DB::table('recipes')->insertGetId([
                'category_id' => $categoryId,
                'nama_resep' => $recipe['nama_resep'],
                'deskripsi' => $recipe['deskripsi'],
                'waktu_masak' => $recipe['waktu_masak'],
                'porsi' => $recipe['porsi'],
                'tingkat_kesulitan' => 'Mudah',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($recipe['bahan'] as $namaBahan) {
                DB::table('bahans')->insert([
                    'recipe_id' => $recipeId,
                    'nama_bahan' => $namaBahan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $recipeIds = DB::table('recipes')
            ->whereIn('nama_resep', ['Mocalatte Spesial', 'Waterlemon'])
            ->pluck('id');

        if ($recipeIds->isNotEmpty()) {
            DB::table('bahans')->whereIn('recipe_id', $recipeIds)->delete();
            DB::table('recipes')->whereIn('id', $recipeIds)->delete();
        }
    }
};
