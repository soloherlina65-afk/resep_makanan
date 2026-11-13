<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

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

    protected $casts = [
        'category_id' => 'integer',
        'waktu_masak' => 'integer',
        'porsi' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bahan()
    {
        return $this->hasMany(ResepBahan::class, 'recipe_id');
    }

    public function steps()
    {
        return $this->hasMany(Step::class, 'recipe_id')
                    ->orderBy('urutan');
    }

    public function likes()
    {
        return $this->hasMany(Favorite::class)->where('type', 'like');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class)->where('type', 'favorite');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function visibleReviews()
    {
        return $this->hasMany(Review::class)->where('is_visible', true)->latest();
    }
}
