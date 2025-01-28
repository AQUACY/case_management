<?php

namespace App\Mail;

use App\Models\Achievement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AchievementReviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;
    public $status;
    public $comment;

    public function __construct(Achievement $record, $status, $comment = null)
    {
        $this->emailData = [
            'client_name' => $record->case->user->name,
            'order_number' => $record->case->order_number,
            'status' => $status,
            'comment' => $comment
        ];
    }

    public function build()
    {
        return $this->subject('SOC: Additional Qualifications Review Update')
                    ->view('emails.achievement_review');
    }
}
