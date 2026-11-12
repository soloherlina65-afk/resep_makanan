<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $recipeId = DB::table('recipes')
            ->where('nama_resep', 'Mocalatte Spesial')
            ->value('id');

        if (! $recipeId || DB::table('steps')->where('recipe_id', $recipeId)->exists()) {
            return;
        }

        $now = now();
        $steps = [
            'Seduh kopi dengan air panas, lalu aduk hingga larut.',
            'Panaskan susu bersama gula dengan api kecil. Jangan sampai mendidih.',
            'Tuangkan susu hangat ke dalam gelas berisi kopi.',
            'Tambahkan es batu jika ingin disajikan dingin.',
            'Aduk rata, lalu tambahkan busa susu atau taburan cokelat sebagai pelengkap.',
        ];

        DB::table('steps')->insert(array_map(
            fn (string $description, int $index) => [
                'recipe_id' => $recipeId,
                'urutan' => $index + 1,
                'deskripsi' => $description,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $steps,
            array_keys($steps),
        ));
    }

    public function down(): void
    {
        $recipeId = DB::table('recipes')
            ->where('nama_resep', 'Mocalatte Spesial')
            ->value('id');

        if ($recipeId) {
            DB::table('steps')->where('recipe_id', $recipeId)->delete();
        }
    }
};
