<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectReviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;
    public $status;
    public $comment;

    public function __construct(Project $record, $status, $comment = null)
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
        return $this->subject('SOC: Project Contributions Review Update')
                    ->view('emails.project_review');
    }
}
