<?php

namespace App\Notifications;
use Illuminate\Notifications\Notification;

class ReviewRequestNotification extends Notification
{
    public $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // You can add more channels as needed
    }

    public function toMail($notifiable)
    {
        return (new \App\Mail\ReviewRequestMailEndavor($this->record))
                    ->to($notifiable->email);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A review has been requested for the case with ID: ' . $this->record->case_id,
        ];
    }
}
