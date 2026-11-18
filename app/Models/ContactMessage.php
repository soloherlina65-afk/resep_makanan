<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_READ = 'read';

    public const STATUS_REPLIED = 'replied';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
    ];
}
