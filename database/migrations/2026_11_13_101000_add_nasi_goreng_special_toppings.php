<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $recipeId = DB::table('recipes')
            ->where('nama_resep', 'Nasi Goreng Spesial')
            ->value('id');

        if (! $recipeId) {
            return;
        }

        DB::table('bahans')->where('recipe_id', $recipeId)->delete();
        DB::table('steps')->where('recipe_id', $recipeId)->delete();

        $now = now();
        $ingredients = [
            '2 piring nasi putih dingin',
            '100 gram udang, kupas dan bersihkan',
            '100 gram daging sapi, potong dadu kecil',
            '100 gram cumi-cumi, potong berbentuk cincin',
            '2 butir telur ayam',
            '3 siung bawang putih, cincang',
            '5 butir bawang merah, iris tipis',
            '3 buah cabai merah, iris serong',
            '2 sendok makan kecap manis',
            '1 sendok makan saus tiram',
            'Garam, merica, dan minyak goreng secukupnya',
            'Daun bawang dan bawang goreng untuk taburan',
            'Kerupuk dan irisan timun untuk pelengkap',
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
            'Siapkan nasi putih dingin. Bersihkan udang dan cumi-cumi, lalu potong daging sapi menjadi dadu kecil.',
            'Panaskan sedikit minyak. Tumis bawang merah, bawang putih, dan cabai sampai harum.',
            'Masukkan daging sapi, udang, dan cumi-cumi. Masak hingga matang, tetapi jangan terlalu lama agar seafood tetap lembut.',
            'Sisihkan tumisan ke tepi wajan, lalu masukkan telur dan orak-arik hingga matang.',
            'Masukkan nasi putih, kecap manis, saus tiram, garam, dan merica. Aduk dengan api besar sampai bumbu merata dan nasi terasa panas.',
            'Tambahkan daun bawang, lalu aduk sebentar. Koreksi rasa dan matikan api.',
            'Sajikan nasi goreng dengan topping udang, daging sapi, dan cumi-cumi. Tambahkan bawang goreng, kerupuk, dan irisan timun sebagai pelengkap.',
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

        DB::table('recipes')
            ->where('id', $recipeId)
            ->update([
                'deskripsi' => 'Nasi goreng spesial dengan topping udang, daging sapi, cumi-cumi, telur, dan pelengkap khas seperti kerupuk serta irisan timun.',
                'updated_at' => $now,
            ]);
    }

    public function down(): void
    {
        $recipeId = DB::table('recipes')
            ->where('nama_resep', 'Nasi Goreng Spesial')
            ->value('id');

        if ($recipeId) {
            DB::table('bahans')->where('recipe_id', $recipeId)->delete();
            DB::table('steps')->where('recipe_id', $recipeId)->delete();
        }
    }
};
