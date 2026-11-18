<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_REVIEWING = 'reviewing';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUS_DISMISSED = 'dismissed';

    public const REASONS = [
        'spam' => 'Spam atau promosi',
        'inappropriate' => 'Konten tidak pantas',
        'misinformation' => 'Informasi menyesatkan',
        'other' => 'Lainnya',
    ];

    protected $fillable = [
        'user_id',
        'reason',
        'details',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}
