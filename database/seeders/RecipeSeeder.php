<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Bahan;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Kategori
        $kat1 = Category::create(['nama_kategori' => 'Masakan Nusantara']);
        $kat2 = Category::create(['nama_kategori' => 'Cemilan & Kue']);

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
        Bahan::create(['recipe_id' => $resep1->id, 'nama_bahan' => '2 piring nasi putih']);
        Bahan::create(['recipe_id' => $resep1->id, 'nama_bahan' => '2 siung bawang putih, cincang']);
        Bahan::create(['recipe_id' => $resep1->id, 'nama_bahan' => '1 butir telur ayam']);
        Bahan::create(['recipe_id' => $resep1->id, 'nama_bahan' => '2 sendok makan kecap manis']);

        // 3. Buat Contoh Resep 2
        $resep2 = Recipe::create([
            'category_id' => $kat2->id,
            'nama_resep' => 'Pancake Klasik Madu',
            'deskripsi' => 'Pancake lembut dan empuk disiram dengan madu murni, cocok untuk sarapan pagi.',
            'waktu_masak' => 15,
            'porsi' => 3,
            'tingkat_kesulitan' => 'Sedang',
        ]);

        // Masukkan bahan untuk Resep 2
        Bahan::create(['recipe_id' => $resep2->id, 'nama_bahan' => '1 cup tepung terigu']);
        Bahan::create(['recipe_id' => $resep2->id, 'nama_bahan' => '1 sdt baking powder']);
        Bahan::create(['recipe_id' => $resep2->id, 'nama_bahan' => '1 butir telur']);
        Bahan::create(['recipe_id' => $resep2->id, 'nama_bahan' => '3/4 cup susu cair']);
    }
}