<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            // Jenis hidangan
            'Hidangan Utama',
            'Hidangan Pembuka',
            'Hidangan Pendamping',
            'Makanan Ringan',
            'Jajanan Pasar',
            'Kue Kering',
            'Kue Basah',
            'Roti',
            'Pastry',
            'Dessert',
            'Es Krim',
            'Puding',
            'Permen',
            'Selai dan Saus',
            'Acar dan Fermentasi',
            'Sup',
            'Soto',
            'Salad',
            'Sandwich',
            'Burger',
            'Pizza',
            'Pasta',
            'Mie',
            'Nasi',
            'Bubur',
            'Sate',
            'Gorengan',
            'Panggang',
            'Barbeku',
            'Kukus',
            'Rebus',
            'Tumis',
            'Bakar',
            'Masakan Berkuah',
            'Masakan Pedas',
            // Waktu makan
            'Sarapan',
            'Brunch',
            'Makan Siang',
            'Makan Malam',
            'Bekal',
            'Hidangan Lebaran',
            'Hidangan Natal',
            'Hidangan Tahun Baru',
            // Bahan utama
            'Ayam',
            'Daging Sapi',
            'Daging Kambing',
            'Daging Domba',
            'Daging Babi',
            'Seafood',
            'Ikan',
            'Udang',
            'Cumi-Cumi',
            'Telur',
            'Tahu dan Tempe',
            'Sayuran',
            'Buah-Buahan',
            'Kacang-Kacangan',
            'Keju',
            // Diet dan kebutuhan makanan
            'Vegetarian',
            'Vegan',
            'Pescatarian',
            'Halal',
            'Bebas Gluten',
            'Rendah Kalori',
            'Rendah Karbohidrat',
            'Tinggi Protein',
            'Ramah Diet',
            'Makanan Anak',
            // Masakan dunia
            'Masakan Indonesia',
            'Masakan Melayu',
            'Masakan Thailand',
            'Masakan Vietnam',
            'Masakan Filipina',
            'Masakan Malaysia',
            'Masakan Singapura',
            'Masakan Jepang',
            'Masakan Korea',
            'Masakan Tiongkok',
            'Masakan India',
            'Masakan Timur Tengah',
            'Masakan Turki',
            'Masakan Persia',
            'Masakan Mediterania',
            'Masakan Italia',
            'Masakan Prancis',
            'Masakan Spanyol',
            'Masakan Yunani',
            'Masakan Meksiko',
            'Masakan Amerika',
            'Masakan Karibia',
            'Masakan Afrika',
            'Masakan Barat',
            'Masakan Asia',
            // Minuman
            'Minuman Dingin',
            'Minuman Hangat',
            'Jus',
            'Smoothie',
            'Kopi',
            'Teh',
            'Mocktail',
            'Minuman Tradisional',
        ];

        foreach (array_unique($categories) as $name) {
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
        // Categories are shared by recipes, so they are intentionally retained on rollback.
    }
};
