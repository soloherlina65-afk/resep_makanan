<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $activity,
        private readonly string $message,
        private readonly int $recipeId,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'activity' => $this->activity,
            'message' => $this->message,
            'recipe_id' => $this->recipeId,
        ];
    }
}
