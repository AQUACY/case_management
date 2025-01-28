<?php

namespace App\Mail;

use App\Models\ProposedEmploymentEndavorRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        return $this->subject('Your Review Request is Pending')
                    ->view('emails.reviewPending')
                    ->with('emailData', $this->emailData);
    }
}
