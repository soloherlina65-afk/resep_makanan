<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes';

    protected $fillable = [
        'user_id',
        'category_id',
        'nama_resep',
        'deskripsi',
        'foto',
        'waktu_masak',
        'porsi',
        'tingkat_kesulitan',
    ];

    // Relasi kategori resep
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relasi bahan-bahan resep
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'recipe_id');
    }

    // Relasi langkah-langkah memasak
    public function steps()
    {
        return $this->hasMany(Step::class, 'recipe_id');
    }
}