<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->ensureRecipe([
            'nama_resep' => 'Pancake Klasik Madu',
            'nama_kategori' => 'Cemilan & Kue',
            'deskripsi' => 'Pancake lembut dan empuk disiram dengan madu murni, cocok untuk sarapan pagi.',
            'foto' => 'images/pancakeklasikmadu.png',
            'waktu_masak' => 15,
            'porsi' => 3,
            'tingkat_kesulitan' => 'Mudah',
            'bahan' => [
                '1 cup tepung terigu',
                '1 sdt baking powder',
                '1 butir telur',
                '3/4 cup susu cair',
                '2 sdm gula pasir',
                '2 sdm mentega, lelehkan',
                'Sejumput garam',
                'Madu secukupnya untuk pelengkap',
            ],
            'steps' => [
                'Campurkan tepung terigu, baking powder, gula pasir, dan garam dalam mangkuk.',
                'Di mangkuk terpisah, kocok telur bersama susu cair dan mentega leleh hingga tercampur.',
                'Tuangkan campuran basah ke bahan kering. Aduk perlahan sampai rata; adonan tidak perlu diaduk terlalu lama.',
                'Panaskan wajan datar dengan api kecil-sedang dan olesi tipis dengan mentega.',
                'Tuang satu sendok sayur adonan. Masak sampai muncul gelembung di permukaan, lalu balik dan masak sisi lainnya hingga kecokelatan.',
                'Ulangi sampai adonan habis. Sajikan pancake hangat dengan madu.',
            ],
        ]);

        $this->ensureRecipe([
            'nama_resep' => 'Udang Saus Padang',
            'nama_kategori' => 'Seafood',
            'deskripsi' => 'Udang segar dimasak dengan saus Padang yang gurih, pedas, dan kaya bumbu.',
            'foto' => 'images/udangsauspadang.png',
            'waktu_masak' => 30,
            'porsi' => 4,
            'tingkat_kesulitan' => 'Sedang',
            'bahan' => [
                '500 gram udang ukuran sedang, buang kotoran dan cuci bersih',
                '1/2 buah bawang bombai, iris',
                '4 siung bawang putih, cincang',
                '5 siung bawang merah, haluskan',
                '5 buah cabai merah keriting, haluskan',
                '3 buah cabai rawit merah, sesuaikan dengan selera',
                '3 sdm saus tomat',
                '2 sdm saus sambal',
                '1 sdm saus tiram',
                '1 sdt gula pasir',
                '150 ml air',
                'Garam dan merica secukupnya',
                '2 sdm minyak untuk menumis',
                'Irisan daun bawang untuk taburan',
            ],
            'steps' => [
                'Bersihkan udang, buang kotorannya, lalu tiriskan.',
                'Panaskan minyak. Tumis bawang bombai, bawang putih, bawang merah, dan cabai sampai harum dan matang.',
                'Masukkan saus tomat, saus sambal, saus tiram, gula, garam, dan merica. Aduk rata.',
                'Tuangkan air dan masak saus hingga mendidih.',
                'Masukkan udang. Masak sambil diaduk selama beberapa menit sampai udang berubah warna dan matang; jangan terlalu lama agar tidak alot.',
                'Cicipi dan sesuaikan rasa. Taburi daun bawang, lalu sajikan selagi hangat.',
            ],
        ]);
    }

    public function down(): void
    {
        // Keep recipe content on rollback so later user edits are not removed.
    }

    private function ensureRecipe(array $recipe): void
    {
        $now = now();
        $categoryId = DB::table('categories')
            ->where('nama_kategori', $recipe['nama_kategori'])
            ->value('id');

        if (! $categoryId) {
            $categoryId = DB::table('categories')->insertGetId([
                'nama_kategori' => $recipe['nama_kategori'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $existingRecipe = DB::table('recipes')
            ->where('nama_resep', $recipe['nama_resep'])
            ->first();

        if ($existingRecipe) {
            $recipeId = $existingRecipe->id;
            $updates = [];

            if (empty($existingRecipe->deskripsi)) {
                $updates['deskripsi'] = $recipe['deskripsi'];
            }

            if (empty($existingRecipe->foto)) {
                $updates['foto'] = $recipe['foto'];
            }

            if ($updates !== []) {
                $updates['updated_at'] = $now;
                DB::table('recipes')->where('id', $recipeId)->update($updates);
            }
        } else {
            $recipeId = DB::table('recipes')->insertGetId([
                'category_id' => $categoryId,
                'nama_resep' => $recipe['nama_resep'],
                'deskripsi' => $recipe['deskripsi'],
                'foto' => $recipe['foto'],
                'waktu_masak' => $recipe['waktu_masak'],
                'porsi' => $recipe['porsi'],
                'tingkat_kesulitan' => $recipe['tingkat_kesulitan'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach ($recipe['bahan'] as $ingredient) {
            if (! DB::table('bahans')->where('recipe_id', $recipeId)->where('nama_bahan', $ingredient)->exists()) {
                DB::table('bahans')->insert([
                    'recipe_id' => $recipeId,
                    'nama_bahan' => $ingredient,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        if (! DB::table('steps')->where('recipe_id', $recipeId)->exists()) {
            DB::table('steps')->insert(array_map(
                fn (string $description, int $index) => [
                    'recipe_id' => $recipeId,
                    'urutan' => $index + 1,
                    'deskripsi' => $description,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                $recipe['steps'],
                array_keys($recipe['steps']),
            ));
        }
    }
};
