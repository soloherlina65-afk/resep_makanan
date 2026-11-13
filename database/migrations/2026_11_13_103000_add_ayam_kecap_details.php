<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $recipeId = DB::table('recipes')
            ->where('nama_resep', 'Ayam Kecap')
            ->value('id');

        if (! $recipeId) {
            return;
        }

        DB::table('bahans')->where('recipe_id', $recipeId)->delete();
        DB::table('steps')->where('recipe_id', $recipeId)->delete();

        $now = now();
        $ingredients = [
            '500 gram ayam, potong menjadi beberapa bagian',
            '6 siung bawang merah, iris tipis',
            '4 siung bawang putih, cincang',
            '3 sendok makan kecap manis',
            '1 sendok makan saus tiram',
            '2 lembar daun salam',
            '1 batang serai, memarkan',
            '2 cm jahe, memarkan',
            '250 ml air',
            'Garam, merica, dan minyak goreng secukupnya',
            'Bawang goreng dan irisan daun bawang untuk taburan',
        ];

        DB::table('bahans')->insert(array_map(
            fn (string $ingredient) => [
                'recipe_id' => $recipeId,
                'nama_bahan' => $ingredient,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $ingredients,
        ));

        $steps = [
            'Cuci bersih ayam, lalu lumuri dengan sedikit garam dan merica.',
            'Panaskan minyak, kemudian goreng ayam sebentar hingga permukaannya berubah warna. Angkat dan sisihkan.',
            'Tumis bawang merah dan bawang putih sampai harum.',
            'Masukkan daun salam, serai, dan jahe. Aduk sebentar hingga bumbu semakin wangi.',
            'Masukkan ayam, kecap manis, saus tiram, garam, dan merica. Aduk sampai ayam terbalut bumbu.',
            'Tuangkan air, lalu masak dengan api kecil sampai ayam matang dan kuah mengental.',
            'Koreksi rasa, kemudian sajikan ayam kecap dengan bawang goreng dan irisan daun bawang.',
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
            ->where('nama_resep', 'Ayam Kecap')
            ->value('id');

        if ($recipeId) {
            DB::table('bahans')->where('recipe_id', $recipeId)->delete();
            DB::table('steps')->where('recipe_id', $recipeId)->delete();
        }
    }
};
