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

    // Relasi ke tabel categories
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relasi ke tabel bahans
    public function bahan()
    {
        return $this->hasMany(Bahan::class, 'recipe_id');
    }

    // Relasi ke tabel steps
    public function steps()
    {
        return $this->hasMany(Step::class, 'recipe_id')
                    ->orderBy('urutan');
    }

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
