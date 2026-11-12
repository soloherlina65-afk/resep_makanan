<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            'Masakan Nusantara',
            'Cemilan & Kue',
            'Minuman',
            'Sarapan',
            'Makan Siang',
            'Makan Malam',
            'Masakan Barat',
            'Masakan Asia',
            'Dessert',
            'Salad',
            'Sup',
            'Vegetarian',
            'Seafood',
        ];

        foreach ($categories as $name) {
            if (! DB::table('categories')->where('nama_kategori', $name)->exists()) {
                DB::table('categories')->insert([
                    'nama_kategori' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('categories')
            ->whereIn('nama_kategori', [
                'Sarapan',
                'Makan Siang',
                'Makan Malam',
                'Masakan Barat',
                'Masakan Asia',
                'Dessert',
                'Salad',
                'Sup',
                'Vegetarian',
                'Seafood',
            ])
            ->delete();
    }
};
