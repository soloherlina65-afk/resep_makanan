<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'position',
        'initial',
        'name',
        'division',
        'photo',
    ];
}
