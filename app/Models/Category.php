<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'nama_kategori',
    ];

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}