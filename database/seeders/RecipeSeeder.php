<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\ResepBahan;
use App\Models\Step;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Kategori
        $kat1 = Category::create(['nama_kategori' => 'Masakan Nusantara']);
        $kat2 = Category::firstOrCreate(['nama_kategori' => 'Cemilan & Kue']);

        // 2. Buat Contoh Resep 1
        $resep1 = Recipe::create([
            'category_id' => $kat1->id,
            'nama_resep' => 'Nasi Goreng Spesial',
            'deskripsi' => 'Nasi goreng lezat dengan tambahan telur, ayam, dan bumbu rempah pilihan khas Nusantara.',
            'waktu_masak' => 20,
            'porsi' => 2,
            'tingkat_kesulitan' => 'Mudah',
        ]);

        // Masukkan bahan untuk Resep 1
        ResepBahan::create(['recipe_id' => $resep1->id, 'nama_bahan' => '2 piring nasi putih']);
        ResepBahan::create(['recipe_id' => $resep1->id, 'nama_bahan' => '2 siung bawang putih, cincang']);
        ResepBahan::create(['recipe_id' => $resep1->id, 'nama_bahan' => '1 butir telur ayam']);
        ResepBahan::create(['recipe_id' => $resep1->id, 'nama_bahan' => '2 sendok makan kecap manis']);

        // 3. Buat Contoh Resep 2
        $resep2 = Recipe::firstOrCreate(
            ['nama_resep' => 'Pancake Klasik Madu'],
            [
                'category_id' => $kat2->id,
                'deskripsi' => 'Pancake lembut dan empuk disiram dengan madu murni, cocok untuk sarapan pagi.',
                'foto' => 'images/pancakeklasikmadu.png',
                'waktu_masak' => 15,
                'porsi' => 3,
                'tingkat_kesulitan' => 'Mudah',
            ],
        );

        // Masukkan bahan untuk Resep 2
        foreach ([
            '1 cup tepung terigu',
            '1 sdt baking powder',
            '1 butir telur',
            '3/4 cup susu cair',
            '2 sdm gula pasir',
            '2 sdm mentega, lelehkan',
            'Sejumput garam',
            'Madu secukupnya untuk pelengkap',
        ] as $ingredient) {
            ResepBahan::firstOrCreate([
                'recipe_id' => $resep2->id,
                'nama_bahan' => $ingredient,
            ]);
        }

        foreach ([
            'Campurkan tepung terigu, baking powder, gula pasir, dan garam dalam mangkuk.',
            'Di mangkuk terpisah, kocok telur bersama susu cair dan mentega leleh hingga tercampur.',
            'Tuangkan campuran basah ke bahan kering. Aduk perlahan sampai rata; adonan tidak perlu diaduk terlalu lama.',
            'Panaskan wajan datar dengan api kecil-sedang dan olesi tipis dengan mentega.',
            'Tuang satu sendok sayur adonan. Masak sampai muncul gelembung di permukaan, lalu balik dan masak sisi lainnya hingga kecokelatan.',
            'Ulangi sampai adonan habis. Sajikan pancake hangat dengan madu.',
        ] as $index => $description) {
            Step::firstOrCreate(
                ['recipe_id' => $resep2->id, 'urutan' => $index + 1],
                ['deskripsi' => $description],
            );
        }
    }
}
