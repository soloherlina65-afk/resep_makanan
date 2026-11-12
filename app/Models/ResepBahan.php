<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepBahan extends Model
{
    use HasFactory;

    protected $table = 'bahans';

    protected $fillable = [
        'recipe_id',
        'nama_bahan',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id');
    }
}
