<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $table = 'steps';

    protected $fillable = [
        'recipe_id',
        'urutan',
        'deskripsi',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
