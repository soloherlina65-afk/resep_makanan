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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}